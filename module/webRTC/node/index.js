// HTTP 환경

// const express = require('express');
// const app = express();

// const server = require('http').Server(app);
// const io = require('socket.io')(server);
// const http_port = 55420;

// app.use(express.static('html'));

// server.listen(http_port, () => {
//   console.log('Running server on 127.0.0.1:' + http_port);
// });

// io.on('connection', function(socket) {
//     socket.on('test', (data) => {
//       console.log('received: "' + data + '" from client' + socket.id);
//       socket.emit('test', "Ok, i got it, " + socket.id);
//     });
  
//     socket.on('disconnect', () => {
//       console.log('disconnected from ', socket.id);
//     });
// });

// HTTPS 환경
const express = require('express');
const app = express();
const fs = require('fs');

const options = {
    cert: fs.readFileSync('./kirbs.crt'),
    key: fs.readFileSync('./kirbs.key')
}
const https = require('https').createServer(options, app);

const io = require('socket.io')(https);
const HTTPS_PORT = 55421;

app.use('/', express.static('html'));

io.on('connection', (socket) => {
    socket.on('join', (roomId) => {
      let roomClients = io.sockets.adapter.rooms.get(roomId) || { length : 0 };
      var numberOfClients = 0;
      if(roomClients.size !== undefined) {
        numberOfClients = roomClients.size;
       }

      // These events are emitted only to the sender socket.
      if (numberOfClients == 0) {
        console.log(`Creating room ${roomId} and emitting room_created socket event`)
        socket.join(roomId)
        socket.emit('room_created', roomId)
      }
      else if (numberOfClients == 1) {
        console.log(`Joining room ${roomId} and emitting room_joined socket event`)
        socket.join(roomId)
        socket.emit('room_joined', roomId)
      } else {
        console.log(`Can't join room ${roomId}, emitting full_room socket event`)
        socket.emit('full_room', roomId)
      }
    })
  
    // These events are emitted to all the sockets connected to the same room except the sender.
    socket.on('start_call', (roomId) => {
      console.log(`Broadcasting start_call event to peers in room ${roomId}`)
      socket.broadcast.to(roomId).emit('start_call')
    })
    socket.on('webrtc_offer', (event) => {
      console.log(`Broadcasting webrtc_offer event to peers in room ${event.roomId}`)
      socket.broadcast.to(event.roomId).emit('webrtc_offer', event.sdp)
    })
    socket.on('webrtc_answer', (event) => {
      console.log(`Broadcasting webrtc_answer event to peers in room ${event.roomId}`)
      socket.broadcast.to(event.roomId).emit('webrtc_answer', event.sdp)
    })
    socket.on('webrtc_ice_candidate', (event) => {
      console.log(`Broadcasting webrtc_ice_candidate event to peers in room ${event.roomId}`)
      socket.broadcast.to(event.roomId).emit('webrtc_ice_candidate', event)
    })

    socket.on('admin_set', () =>{
      let roomclients = io.sockets.adapter.rooms;

      var userKeyArray = new Array();
      roomclients.forEach((v, k, map) => {

        if(k.length != 20){
          userKeyArray.push(k);
        }
      })

      socket.emit('admin_set', userKeyArray);
    })

    // main 페이지 응시자 감독 join
    socket.on('admin_joined', (roomId) => {
      console.log(`Joining room ${roomId} and emitting admin_joined socket event`)
      socket.join(roomId)
      socket.emit('admin_joined', roomId)
    })

    socket.on('chatting', (data) => {
      console.log(`Broadcasting chatting event to peers in room ${data.roomId}`)

      socket.broadcast.to(data.roomId).emit('chatting', data)
    });
  })

  io.on('disconnect', (socket) => {
    console.log('disconnected from ', socket.id);
    
  })

https.listen(HTTPS_PORT);