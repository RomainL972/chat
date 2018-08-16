function ajaxRequest()
{
	try // Navigateur non IE?
	{   // Oui
		var request = new XMLHttpRequest()
	}
	catch(e1)
	{
		try // IE 6+?
		{   // Oui
			request = new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch(e2)
		{
			try // IE 5?
			{   // Oui
				request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch(e3) // Pas de prise en charge d'AJAX
			{ 
				request = false
			}
		}
	}
	return request
}

function ajax(url, params, callback)
{
	if(!(request = ajaxRequest()))
		alert("Votre navigateur n'est pas supporté")
	request.open("POST", url, true)
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	request.onreadystatechange = stateChange
	request.callback = callback
	request.send(params)
}

function stateChange(){
    if (this.readyState == 4)
    {
        if (this.status == 200)
        {
            if (this.responseText != null)
            {
                this.callback(this.responseText);
            }
            else alert("Erreur Ajax : Aucune donnée reçue")
        }
        else alert( "Erreur Ajax : " + this.statusText)
    }
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

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}