// DOM elements.
const roomSelectionContainer = document.getElementById('room-selection-container')
const roomInput = document.getElementById('room-input')
const connectButton = document.getElementById('connect-button')
const videoChatContainer = document.getElementById('video-chat-container')
const localVideoComponent = document.getElementById('local-video')
const remoteVideoComponent = document.getElementById('remote-video')
const roomTextContainer = document.getElementById('room-text-container')
const chattingText = document.getElementById('chattingText')
const chattingInput = document.getElementById('chattingInput')
const nameText = document.getElementById('name-text')
const sendButton = document.getElementById('send-button')
const myName = document.getElementById('myName')
// Variables.
const socket = io()
const mediaConstraints = { audio: true, video: true }
let localStream
let remoteStream
let isRoomCreator
let rtcPeerConnection // Connection between the loand the remote peer.
let roomId
let userName

// Free public STUN servers provided by Google.
const iceServers = {
  iceServers: [
    { urls: 'stun:stun.l.google.com:19302' },
    { urls: 'stun:stun1.l.google.com:19302' },
    { urls: 'stun:stun2.l.google.com:19302' },
    { urls: 'stun:stun3.l.google.com:19302' },
    { urls: 'stun:stun4.l.google.com:19302' },
  ],
}

// BUTTON LISTENER ============================================================
connectButton.addEventListener('click', () => {
  joinRoom(roomInput.value)
})

// SOCKET EVENT CALLBACKS =====================================================
socket.on('room_created', async () => {
  console.log('Socket event callback: room_created')

  await setLocalStream(mediaConstraints)
  socket.emit('start_call', roomId)
  isRoomCreator = true
})

socket.on('room_joined', async () => {
  console.log('Socket event callback: room_joined')

  await setLocalStream(mediaConstraints)
  socket.emit('start_call', roomId)
})

socket.on('full_room', () => {
  console.log('Socket event callback: full_room')
  alert('The room is full, please try another one')
})

// FUNCTIONS ==================================================================
function joinRoom(room) {
  if (room === '') {
    alert('제공받은 코드를 입력해주세요.')
  } else if(nameText.value === '') {
    alert('이름을 입력해주세요.')
  } else {
    roomId = room
    userName = nameText.value
    myName.innerText = `내 이름 : ${nameText.value}`
    socket.emit('join', room)
    showVideoConference()
  }
}

function showVideoConference() {
  roomSelectionContainer.style = 'display: none'
  videoChatContainer.style = 'display: block'
  roomTextContainer.style = 'display: block'
}

async function setLocalStream(mediaConstraints) {
  let stream
  try {
    stream = await navigator.mediaDevices.getDisplayMedia(mediaConstraints)
  } catch (error) {
    console.error('Could not get user media', error)
  }

  localStream = stream
  localVideoComponent.srcObject = stream
}


// SOCKET EVENT CALLBACKS =====================================================
socket.on('start_call', async () => {
  console.log('Socket event callback: start_call')

  if (isRoomCreator) {
    rtcPeerConnection = new RTCPeerConnection(iceServers)
    addLocalTracks(rtcPeerConnection)
    rtcPeerConnection.ontrack = setRemoteStream
    rtcPeerConnection.onicecandidate = sendIceCandidate
    await createOffer(rtcPeerConnection)
  }
})

socket.on('webrtc_offer', async (event) => {

  console.log('Socket event callback: webrtc_offer')

  if (!isRoomCreator) {
    rtcPeerConnection = new RTCPeerConnection(iceServers)
    addLocalTracks(rtcPeerConnection)
    rtcPeerConnection.ontrack = setRemoteStream
    rtcPeerConnection.onicecandidate = sendIceCandidate
    rtcPeerConnection.setRemoteDescription(new RTCSessionDescription(event))
    await createAnswer(rtcPeerConnection)
  }
})

socket.on('webrtc_answer', (event) => {
  console.log('Socket event callback: webrtc_answer')

  rtcPeerConnection.setRemoteDescription(new RTCSessionDescription(event))
})

socket.on('webrtc_ice_candidate', (event) => {
  console.log('Socket event callback: webrtc_ice_candidate')

  chattingText.innerHTML += `[알림] ${event.userName}님이 접속하셨습니다.\n`
  // ICE candidate configuration.
  var candidate = new RTCIceCandidate({
    sdpMLineIndex: event.label,
    candidate: event.candidate,
  })
  
  rtcPeerConnection.addIceCandidate(candidate)
})

chattingInput.addEventListener('keydown', (event) => {
  if (event.keyCode === 13) {
    sendButton.click()
  }
});

sendButton.addEventListener('click', (event) => {
  let data = {
    roomId: roomId,
    userId: userName,
    msg: chattingInput.value,
  }
  socket.emit('chatting', data)
  chattingText.innerHTML += `${data.userId} : ${data.msg}\n`
  chattingInput.value = ''
  chattingText.scrollTop = chattingText.scrollHeight
})

socket.on('chatting', (data) => {
  chattingText.innerHTML += `${data.userId} : ${data.msg}\n`
  chattingText.scrollTop = chattingText.scrollHeight
})

// FUNCTIONS ==================================================================
function addLocalTracks(rtcPeerConnection) {
  localStream.getTracks().forEach((track) => {
    rtcPeerConnection.addTrack(track, localStream)
  })
}

async function createOffer(rtcPeerConnection) {
  let sessionDescription
  try {
    sessionDescription = await rtcPeerConnection.createOffer()
    rtcPeerConnection.setLocalDescription(sessionDescription)
  } catch (error) {
    console.error(error)
  }

  socket.emit('webrtc_offer', {
    type: 'webrtc_offer',
    sdp: sessionDescription,
    roomId,
  })
}

async function createAnswer(rtcPeerConnection) {
  let sessionDescription
  try {
    sessionDescription = await rtcPeerConnection.createAnswer()
    rtcPeerConnection.setLocalDescription(sessionDescription)
  } catch (error) {
    console.error(error)
  }

  socket.emit('webrtc_answer', {
    type: 'webrtc_answer',
    sdp: sessionDescription,
    roomId,
  })
}

function setRemoteStream(event) {
  remoteVideoComponent.srcObject = event.streams[0]
  remoteStream = event.stream
}

function sendIceCandidate(event) {
  if (event.candidate) {
    socket.emit('webrtc_ice_candidate', {
      roomId,
      label: event.candidate.sdpMLineIndex,
      candidate: event.candidate.candidate,
      userName: userName,
    })
  }
}