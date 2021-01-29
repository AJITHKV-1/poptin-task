
var webUrl = "http://ec2-3-83-46-88.compute-1.amazonaws.com/poptin-task/public/checkRules";
console.log("webUrl", webUrl);
const urlParams = new URLSearchParams(
    document.currentScript.getAttribute("src")
);
const token = getParameterByName("token",document.currentScript.getAttribute("src"));

if (token) {
    var path = window.location.pathname;
    const params = new FormData();
    params.append("token", token);
    params.append("path", path);
    httpPost(webUrl, params, function (res) {
        try {
            if (res) {
                var data = JSON.parse(res);
                if (data) {
                    var message = data.message;
                    if (message) {
                        alert(message);
                    }
                }
            }
        } catch (error) {
            console.log(error);
        }
        console.log("res");
        console.log(res);
    });
}

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function httpPost(theUrl, data, callback) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    };
    xmlHttp.open("POST", theUrl, true); // true for asynchronous
    xmlHttp.send(data);
}