var app = require('express')()
var https = require('https')
var fs = require('fs')
var path = require('path')
var bcrypt = require('bcrypt')

var server = https.createServer({ 
                key: fs.readFileSync('/etc/letsencrypt/live/computer.lebbadi.fr/privkey.pem'),
                cert: fs.readFileSync('/etc/letsencrypt/live/computer.lebbadi.fr/fullchain.pem') 
             },app);
server.listen(3000, function(){
	console.log("Listening on *:3000")
});

var io = require('socket.io').listen(server)

app.get('/', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket){
  console.log(socket.id + ' connected');

  socket.emit("auth")
  console.log(socket.id + ": Sending auth request")

  socket.on('disconnect', function(){
    console.log(socket.id + ' disconnected');
  });

  socket.on('auth', function(data){
  	// console.log(data)
  	filePath = path.join(__dirname, 'passwd');

  	passwords = fs.readFileSync(filePath, 'utf-8');

  	passwords = passwords.split("\n")
  	for (var i = 0; i < passwords.length; i++) {
  		auth = passwords[i].split(":")
  		if(auth[0] == data[0]) {
  			usernameFound = 1
  			hash = auth[1].replace(/^\$2y(.+)$/i, '$2a$1');
  			// console.log(hash)
  			// console.log(passwords)
			if(!bcrypt.compareSync(data[1], hash)) {
				console.log(socket.id + ": Incorrect password")
			}
			else {
				socket.loggedIn = 1
				socket.username = auth[0]
				socket.emit("success", {"type": "auth","message": "Logged in successfully"})
				console.log(socket.username + ": Logged in successfully")
			}
  		}
  	}
  	if(!socket.loggedIn) {
  		if(!usernameFound) {
  			console.log(socket.id + ": Username " + data[0] + "not found")
	  	}
	  	socket.emit("error", "Wrong username or password")
	}
  });

  socket.on('room', function(data){
    if(!socket.loggedIn) {
      socket.emit("error", "Not logged in")
      return
    }
    socket.join(data)
    console.log(socket.username + ": Joined room " + data)
  })

  socket.on('message', function(data){
  	if(!socket.loggedIn) {
  		socket.emit("error", "Not logged in")
      return
    }
    let rooms = Object.keys(socket.rooms);
    rooms.forEach(function(room){
      io.to(room).emit("message", data)
    })
    console.log(rooms)
  	console.log(socket.username + ": Sent " + data)
  })
});