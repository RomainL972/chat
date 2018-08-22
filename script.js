user = ""

days = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]


$(function() {
    $.post("/get_messages.php", get_messages)
    $("#text_box").submit(send_message);
});

function get_messages(messages) {
    messages = JSON.parse(messages)
    var elDiv =O("scroll_box");
    user = messages[1]

    if(!elDiv.hasChildNodes() || elDiv.scrollTop == elDiv.scrollHeight-elDiv.offsetHeight)
        goDown = 1
    else
        goDown = 0

    messages[0].forEach(parse_message)

    if(goDown)
        elDiv.scrollTop = elDiv.scrollHeight-elDiv.offsetHeight;

    id = "id=" + ((elDiv.hasChildNodes()) ? O("scroll_box").lastChild.id : 0)
    $.post("/get_messages.php", id, get_messages)
}

function send_message(e) {
    $.post('send_message.php','message='+$('#input_box').val())
    $("#input_box").val("")
    return false
}

function parse_message(message, index, mode) {
    date = new Date(message["time"]*1000)
    if (!O("scroll_box").hasChildNodes()) {
        this.currentDay = [date.getDate(), date.getMonth(), date.getFullYear()]
        day = currentDay
        this.currentTime = [date.getHours(), date.getMinutes()]
        time = currentTime
        new_date(date)
        newTime = "<em>" + date.getHours() + ":" + date.getMinutes() + "</em>"
    }
    else {
        day = [date.getDate(), date.getMonth(), date.getFullYear()]
        time = [date.getHours(), date.getMinutes()]
    }
    if (!currentDay.equals(day)) {
        dayChanged = 1
        new_date(date)
        currentDay = day
    }
    else
        dayChanged = 0

    if(user == message["user"])
        msgClass = "sender"
    else
        msgClass = "recever"

    if (currentTime.equals(time) && O("scroll_box").lastChild.className == msgClass && !dayChanged) {
        O("scroll_box").lastChild.lastChild.remove()
    }
    currentTime = time

    newTime = "<em>" + date.getHours() + ":" + date.getMinutes() + "</em>"

    O("scroll_box").innerHTML += "<li class='" + msgClass + "' id='" + message["id"] + "'><div><span>" + escapeHtml(message["message"]) + "</span></div>"  + newTime + "</li>"
}

function new_date(date) {
    O("scroll_box").innerHTML += "<li class='date'><br><span>" + days[date.getDay()] + " " + date.getDate() + " " + months[date.getMonth()] + " " + date.getFullYear()
}

// function reload() {
//     if (O("scroll_box").hasChildNodes())
//         id = O("scroll_box").lastChild.id
//     else
//         id = 0
//     ajax("/get_messages.php", "id=" + id, get_messages)
// }