
let videoChatContainer = document.getElementById('video-chat-container')
let infoText = document.getElementById('infoText')
let onClickButton = document.getElementById('onClickButton')
const socket = io()
const mediaConstraints = { audio: true, video: true }
let localStream
let remoteStream
let isRoomCreator
let rtcPeerConnection // Connection between the loand the remote peer.
let roomId
let i = 0
const iceServers = {
    iceServers: [
      { urls: 'stun:stun.l.google.com:19302' },
      { urls: 'stun:stun1.l.google.com:19302' },
      { urls: 'stun:stun2.l.google.com:19302' },
      { urls: 'stun:stun3.l.google.com:19302' },
      { urls: 'stun:stun4.l.google.com:19302' },
    ],
  }

// socket 전부 연결
onClickButton.addEventListener('click', async () => {

  socket.emit('admin_set')
});

function showVideos(){
    videoChatContainer.style = 'display:block'
}

socket.on('admin_set', (roomclients)=>{

  console.log('Socket event callback: admin_set')

  if(roomclients.length === 0){
    infoText.innerText = '응시자가 아무도 없습니다.'
  }else{

    console.log(roomclients[i]);
    if(roomclients[i] != undefined){

      roomId = roomclients[i]
      console.log(roomId);
      showVideos()
      socket.emit('join', roomId)
      i++
    }


    // roomclients.forEach((key) => {
    //   roomId = key

    //   showVideos()

    //   socket.emit('join', roomId)
    // })
  }
})

socket.on('room_joined', async () => {
  console.log('Socket event callback: room_joined')
  socket.emit('start_call', roomId)
})

socket.on('webrtc_offer', async (event) => {

  console.log('Socket event callback: webrtc_offer')
  if (!isRoomCreator) {
    rtcPeerConnection = new RTCPeerConnection(iceServers)
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

  // ICE candidate configuration.
  var candidate = new RTCIceCandidate({
    sdpMLineIndex: event.label,
    candidate: event.candidate,
  })
  
  rtcPeerConnection.addIceCandidate(candidate)
})

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

  let userVideo = document.createElement('video')
  userVideo.classList.add('user-video')
  userVideo.srcObject = event.streams[0]
  videoChatContainer.append(userVideo)

  userVideo.play()
}

function sendIceCandidate(event) {
  if (event.candidate) {
    socket.emit('webrtc_ice_candidate', {
      roomId,
      label: event.candidate.sdpMLineIndex,
      candidate: event.candidate.candidate,
    })
  }
}