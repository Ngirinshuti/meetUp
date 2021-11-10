function makeRequest({ method, url, params = "" }) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest()

        xhr.open(method, url, true)
        xhr.setRequestHeader("Content-type", "application/x-wwww-formurlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(JSON.parse(this.responseText))
            }
        }
        xhr.send(params)
    })
}

class Message {
    constructor(me) {
        this.me = me;
        this.url = 'test.php';
    }

    static async send(message, reciever) {
        const request = {
            method: 'POST',
            url: this.url,
            params: `sendMessage&content=${message}&reciever=${reciever}`
        }
        const sendMessage = await makeRequest(request);
        if (sendMessage) {
            alert('sent');
        }
    }

    static async unread()
}