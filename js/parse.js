var days = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
var months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]

function parseMessage(message, index) {
    date = new Date(message["time"]*1000)
    main = O("scroll_box")
    if (!main.hasChildNodes()) {
        this.currentDay = [date.getDate(), date.getMonth(), date.getFullYear()]
        day = currentDay
        this.currentTime = [date.getHours(), date.getMinutes()]
        time = currentTime
        newDate(date)
        last = ""
    }
    else {
        day = [date.getDate(), date.getMonth(), date.getFullYear()]
        time = [date.getHours(), date.getMinutes()]
    }
    if (!currentDay.equals(day)) {
        dayChanged = 1
        newDate(date)
        currentDay = day
    }
    else
        dayChanged = 0

    if(user == message["user"])
        msgClass = "sender"
    else
        msgClass = "recever"


    if (index && currentTime.equals(time) && last.className.includes(msgClass) && !dayChanged) {
        last.lastChild.remove()
    }
    currentTime = time

    li = document.createElement("li")
    li.setAttribute("class", msgClass + " hyphenate")
    li.setAttribute("id", message["id"])
    div = li.appendChild(document.createElement("div"))
    span = div.appendChild(document.createElement("span"))
    span.appendChild(document.createTextNode(message["message"]))
    em = li.appendChild(document.createElement("em"))
    em.appendChild(document.createTextNode(date.getHours() + ":" + date.getMinutes()))

    if(mode)
        O("scroll_box").insertBefore(li, first)
    else
        O("scroll_box").appendChild(li)

    last = li
}

function newDate(date) {
    li = document.createElement("li")
    li.setAttribute("class", "date")
    li.appendChild(document.createElement("br"))
    span = li.appendChild(document.createElement("span"))
    span.appendChild(document.createTextNode(days[date.getDay()] + " " + date.getDate() + " " + months[date.getMonth()] + " " + date.getFullYear()))

    if(mode)
        O("scroll_box").insertBefore(li, first)
    else
        O("scroll_box").appendChild(li)
}