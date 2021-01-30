
var webUrl = "http://ec2-3-83-46-88.compute-1.amazonaws.com/poptin-task/public/checkRules";
// var webUrl = "http://127.0.0.1:8000/checkRules"; // for test in local

const token = getTokenFromScript("token",document.currentScript.getAttribute("src"));

if (token) {
    var path = window.location.pathname;
    const params = new FormData();
    params.append("token", token);
    params.append("path", path);
    httpPostRequest(webUrl, params, function (response) {
        try {
            if (response) {
                var data = JSON.parse(response);
                if (data) {
                    var message = data.message;
                    if (message) { alert(message); }
                    if (data.checked) { 
                        window.addEventListener("scroll", function() {
                            const scrollable = document.documentElement.scrollHeight - window.innerHeight;
                            const scrolled = window.scrollY;
                            const halfwayScrollable = scrollable/2;
                            if(scrolled > halfwayScrollable) {
                                alert(data.checked_message);
                            }
                        }, false);
                    }
                }
            }
        } catch (error) {
            console.log(error);
        }
    });
}

//get token from the script
function getTokenFromScript(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

// http post request function
function httpPostRequest(theUrl, data, callback) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
        callback(xmlHttp.responseText);
    };
    xmlHttp.open("POST", theUrl, true);
    xmlHttp.send(data);
}
