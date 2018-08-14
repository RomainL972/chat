params  = ""
request = new XMLHttpRequest()
request.open("POST", "get_messages.php", true)
request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
// request.setRequestHeader("Content-length", params.length)
// request.setRequestHeader("Connection", "close")

days = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
user = "romain"
       
request.onreadystatechange = function()
{
    if (this.readyState == 4)
    {
        if (this.status == 200)
        {
            if (this.responseText != null)
            {
                display_messages(this.responseText);
            }
            else alert("Erreur Ajax : Aucune donnée reçue")
        }
        else alert( "Erreur Ajax : " + this.statusText)
    }
}
      
request.send(params)

function display_messages(messages) {
    messages = JSON.parse(messages)
    messages.forEach(parse_message)
}

function parse_message(message, index) {
    // newTime = ""
    UTCDate = new Date(message["time"]*1000)
    date = new Date(Date.UTC(UTCDate.getFullYear(), UTCDate.getMonth(), UTCDate.getDate(), UTCDate.getHours(), UTCDate.getMinutes(), UTCDate.getSeconds(), UTCDate.getMilliseconds()))
    console.log(date)
    if (index == 0) {
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
        new_date(date)
        currentDay = day
    }

    if(user == message["user"])
        msgClass = "sender"
    else
        msgClass = "recever"

    console.log(currentTime.equals(time))
    if(!currentTime.equals(time)) {
        newTime = "<em>" + date.getHours() + ":" + date.getMinutes() + "</em>"
        currentTime = time
    }

    O("scroll_box").innerHTML += "<li class='" + msgClass + "'><div><span>" + message["message"] + "</span></div>"  + newTime + "</li>"
}

function new_date(date) {
    O("scroll_box").innerHTML += "<li class='date'><br><span>" + days[date.getDay()] + " " + date.getDate() + " " + months[date.getMonth()] + " " + date.getFullYear()
}

// Warn if overriding existing method
if(Array.prototype.equals)
    console.warn("Overriding existing Array.prototype.equals. Possible causes: New API defines the method, there's a framework conflict or you've got double inclusions in your code.");
// attach the .equals method to Array's prototype to call it on any array
Array.prototype.equals = function (array) {
    // if the other array is a falsy value, return
    if (!array)
        return false;

    // compare lengths - can save a lot of time 
    if (this.length != array.length)
        return false;

    for (var i = 0, l=this.length; i < l; i++) {
        // Check if we have nested arrays
        if (this[i] instanceof Array && array[i] instanceof Array) {
            // recurse into the nested arrays
            if (!this[i].equals(array[i]))
                return false;       
        }           
        else if (this[i] != array[i]) { 
            // Warning - two different object instances will never be equal: {x:20} != {x:20}
            return false;   
        }           
    }       
    return true;
}
// Hide method from for-in loops
Object.defineProperty(Array.prototype, "equals", {enumerable: false});

function O(i) { return typeof i == 'object' ? i : document.getElementById(i) }
function S(i) { return O(i).style                                            }
function C(i) { return document.getElementsByClassName(i)                    }