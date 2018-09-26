var user
var mode
var socket
var room

$(function() {
    // $.post("/get_messages.php", getMessages)
    $("#text_box").submit(sendMessage);
    $("#scroll_box").scroll(detectTop)

    socket = io("https://computer.lebbadi.fr:3000")
    socket.on("auth", function(){
      var a = []
      if(a[0] = localStorage.getItem("username"));
      else
      a[0] = prompt("User")
      if(a[1] = localStorage.getItem("password"));
      else
      a[1] = prompt("Password")
      // console.log(a)
      socket.emit("auth", a)
      localStorage.setItem("username", a[0])
      localStorage.setItem("password", a[1])
    })

    socket.on("success", function(data){
      console.log("Success : " + data.message)

      switch(data.type) {
        case "auth":
        joinRoom()
      }
    })

    socket.on("error", function(message){
      console.log("Error: " + message)
    })

    document.addEventListener( "contextmenu", function(e) {
    console.log(e);
  });
});

function detectTop() {
    if ($(this).scrollTop() == 0 && $(this)[0].hasChildNodes()) {

        mode = 1
        O("scroll_box").firstChild.remove()
        $.post('/get_messages.php', 'mode=1&id='+O("scroll_box").firstChild.id, getMessages)
    }
}

function getMessages(messages) {
    messages = JSON.parse(messages)
    console.log(messages)
    var elDiv =O("scroll_box");
    user = messages[1]

    if(!elDiv.hasChildNodes() || elDiv.scrollTop >= elDiv.scrollHeight-elDiv.offsetHeight)
        goDown = 1
    else
        goDown = 0

    if(mode) {
        first = elDiv.firstChild
        height = elDiv.scrollHeight
    }
    
    messages[0].reverse()
    messages[0].forEach(parseMessage)

    if(goDown)
        elDiv.scrollTop = elDiv.scrollHeight-elDiv.offsetHeight;
    if(mode)
        elDiv.scrollTop = elDiv.scrollHeight-height


    id = "id=" + ((elDiv.hasChildNodes()) ? O("scroll_box").lastChild.id : 0)

    $('.sender, .recever').linkify({
        target: "_blank"
    });
    Hyphenator.run()

    mode = 0
}

function sendMessage(e) {
    $.post('send_message.php','message='+$('#input_box').val())
    $("#input_box").val("")
    return false
}

function joinRoom(rooms){
    if(!rooms) {
        $.get("/rooms", "", function(a){
            joinRoom(a)
        })
        return
    }
    ul = $("body")[0].appendChild(document.createElement("ul"))
    for(var i = 0; i < rooms.length; i++) {
        li = ul.appendChild(document.createElement("li"))
        button = li.appendChild(document.createElement("button"))
        button.setAttribute("id", "room_"+rooms[i]["id"])
        button.appendChild(document.createTextNode(rooms[i]["name"]))
        button.onclick = function(){
            var e = window.event,
                btn = e.target || e.srcElement;
            room = btn.id.split("_")[1]
            console.log(room)
            socket.emit('room', room)
        }
    }
    li = ul.appendChild(document.createElement("li"))
    button = li.appendChild(document.createElement("button"))
    button.appendChild(document.createTextNode("Create a room"))
    button.onclick = function(){
        $.post("/rooms", "name="+prompt("Name"))
        $.get("/rooms", "", function(a){
            $("body")[0].lastChild.remove()
            joinRoom(a)
        })
    }
}