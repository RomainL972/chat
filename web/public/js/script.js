var user
var mode

$(function() {
    $.post("/get_messages.php", getMessages)
    $("#text_box").submit(sendMessage);
    $("#scroll_box").scroll(detectTop)

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

    if(!mode)
        $.post("/get_messages.php", id, getMessages)
    else
        mode = 0
}

function sendMessage(e) {
    $.post('send_message.php','message='+$('#input_box').val())
    $("#input_box").val("")
    return false
}