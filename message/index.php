<?php require '../classes/init.php';
require_once "../classes/friend_system.php";
$friends_obj = new Friends($db_connection, $me->username);


if (isset($_POST['getMessages']) && isset($_POST['reciever'])) {
    exit(json_encode($msg_obj->get_messages($_POST['reciever'], true)));
}
if (isset($_POST['getActive'])) {
    $active = $friends_obj->active_friends();
    exit(json_encode($active));
}
if (isset($_POST['getUsers'])) {
    exit(json_encode($user_obj->get_all_users(true)));
}
if (isset($_POST['sendMessage']) && isset($_POST['reciever']) && isset($_POST['content'])) {
    $result = $msg_obj->send_message($_POST['reciever'], $_POST['content']);
    exit(json_encode($result));
}
if (isset($_POST["getUserData"]) && isset($_POST["user"])) {
    exit(json_encode($user_obj->get_user_data($_POST["user"])));
}
if (isset($_POST["getUnread"]) && isset($_POST["user"])) {
    exit(json_encode($msg_obj->get_unread($_POST["user"])));
}
if (isset($_POST["getAllUnread"])) {
    exit(json_encode($msg_obj->get_all_unread()));
}
if (isset($_POST["readMessage"]) && isset($_POST["user"])) {
    exit(json_encode($msg_obj->read_message($_POST["user"])));
}
if (isset($_POST['getAllMessages'])) {
    exit(json_encode($msg_obj->get_all_messages()));
}
if (isset($_POST['getUserMessages']) && isset($_POST['user'])) {
    exit(json_encode($msg_obj->get_user_messages($_POST['user'])));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" propert="viewport" content="width=device-width, initial-scale=1" />
    <!-- <link rel="stylesheet" href="../css/login_signup.css"/> -->
    <link rel="stylesheet" href="../css/w3.css">
    <link rel="stylesheet" href="../css/font-awesome-4.5.0/css/font-awesome.min.css" />
    <?php require_once '../theme.php'; ?>
    <link rel="stylesheet" href="../css/style2.css" />
    <title>JSON | PHP</title>
    <script src="../js/lib/jquery-3.4.1.min.js"></script>
    <style>
        /* My Global style */
        body {
            margin: 0;
            padding: 0;
        }

        /* my massages wrapper style (chatBox) */

        .messages-wrapper {
            padding-top: 10px !important;
            height: 350px;
            margin: 3px 0;
            scroll-behavior: initial !important;
            display: none;
            border-radius: 4px;
            scroll-behavior: smooth;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            width: 100%;
        }

        .user-messages-wrapper,
        .my-messages-wrapper {
            display: flex;
            align-items: flex-end;
            width: 100%;
            padding: 0;
            justify-content: flex-start;
        }

        .my-messages-wrapper {
            justify-content: flex-end;
        }

        .my-message-date-wrapper {
            display: flex;
            margin: 2px 23px;
            justify-content: flex-end;
        }

        .user-message-date-wrapper {
            display: flex;
            margin: 2px 23px;
            justify-content: flex-start;
        }

        .user-image-wrapper,
        .my-image-wrapper {
            width: 20px;
            border: 1.2px solid #febaba;
            margin: 2px;
            height: 20px;
            object-fit: cover;
            object-position: top;
        }

        .my-image-wrapper {
            margin-right: 1px !important;
        }

        .user-message-body-wrapper,
        .my-message-body-wrapper {
            max-width: 70%;
            word-wrap: break-word;
            border-radius: 20px;
            margin-bottom: 3px;
            padding: 6px 8px !important;
        }

        .my-bottom-message-margin {
            border-radius: 20px 2px 20px 20px !important;
            margin-bottom: 3px;
        }

        .user-bottom-message-margin {
            border-radius: 2px 20px 20px 20px !important;
            margin-bottom: 3px !important;
            margin-left: -1px;
        }

        .my-top-message-margin {
            margin-right: 23px;
            margin-bottom: 3px;
            border-radius: 20px 20px 2px 23px !important;
        }

        .user-top-message-margin {
            margin-left: 23px;
            margin-bottom: 3px;
            border-radius: 20px 20px 20px 2px !important;
        }

        .my-middle-message-margin {
            margin-right: 23px;
            border-radius: 20px 2px 2px 20px !important;
            margin-bottom: 3px;
        }

        .user-middle-message-margin {
            margin-left: 23px;
            border-radius: 2px 20px 20px 2px !important;
            margin-bottom: 3px;
        }

        .user-message-margin {
            margin-left: 23px;
        }

        .message-sender-wrapper form {
            width: 100%;
            display: none;
            position: static;
            bottom: 0;
            margin: 0;
            padding: 0;
            align-items: flex-start !important;
            justify-content: space-between;
            /* align-items: ; */
            gap: .25rem;
        }

        .message-sender-wrapper textarea {
            width: 100%;
            resize: auto;
            border-radius: 4px;
            font-size: 13px;
            padding: 4px;
            border: 1px solid gray;
        }

        .message-sender-wrapper #send_message {
            margin-top: 10px;
        }

        /* active users wrapper style */
        .active-users-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .all-active-users-wrapper {
            display: flex;
            width: 100%;
            margin: 0;
            justify-content: flex-start;
            align-items: center;
            overflow-x: auto;
            position: relative;
            padding: 5px 14px;
            overflow-y: hidden;
            height: 70px;
        }

        .active-users-wrapper h4 {
            margin: 2px;
            width: 100%;
            text-align: center;
        }

        .active-user-wrapper {
            padding: 3px 6px;
            position: relative;
            margin: 0 4px;
            width: 60px;
            cursor: pointer;
            display: flex;
            text-transform: capitalize;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .active-user-image-wrapper img {
            border: 2px solid grey;
            object-position: top;
            height: 30px;
            width: 30px;
            object-fit: cover;
        }

        .active-user-image-wrapper {
            position: relative;
            background: transparent !important;
            width: 30px;
        }

        .online-users {
            border: 3px inset transparent;
            border-radius: 50%;
            margin: 10px 0 !important;
            padding: 2.3px 6px;
            width: auto !important;
            height: auto !important;
            font-size: 13px;
        }

        .online-dot {
            background: linear-gradient(orange, green, yellow);
            padding: 6px;
            border: 1px solid green;
            max-width: 3px;
            max-height: 3px;
            font-size: 7px;
            box-shadow: 2px 2px 10px black, 5px 10px 20px black;
            bottom: 5%;
            border-radius: 50%;
            left: 70%;
            position: absolute;
        }

        .toggle-active .fa {
            font-size: 22px;
            cursor: pointer;
            padding: 0 !important;
            cursor: pointer;
        }

        .toggle-active p {
            margin: 1.5px 3px;
            padding-left: 5px !important;
        }

        .toggle-active button {
            border-width: 0;
            background: transparent;
            margin: 0;
        }

        .toggle-active {
            display: flex;
            padding: 0;
            margin: 0;
            justify-content: space-between;
            align-items: center;
        }

        /* chat wrapper style */
        .current-chat-wrapper {
            position: relative;
            display: none;
            flex-direction: row;
            padding: 2px 7px;
            height: 60px;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .current-chat-data {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .chat-image-wrapper {
            height: 100%;
            display: flex;
            object-fit: cover;
            background: linear-gradient(to bottom right orange, wheat, yellow);
            position: relative;
            align-items: center;
            justify-content: flex-start;
            margin-right: 12px !important;
        }

        .chat-image-wrapper img {
            width: 37px;
            background: linear-gradient(to top left, orange, red, wheat, yellow);
            padding: 2px;
            object-fit: cover;
            object-position: center;
            height: 37px;
        }

        #chatUser {
            text-transform: capitalize;
        }

        .chat-toggle {
            position: relative;
            margin-bottom: 10px;
            width: 100%;
            padding: 5px;
            display: block;
        }

        .chat-toggle .fa {
            position: absolute;
            right: 3px;
            top: 0;
            z-index: 5;
            font-size: 25px;
            cursor: pointer;
            color: black;
            padding: 0;
        }

        /* messages notification badge */
        .message-badge {
            position: absolute;
            border-radius: 50%;
            height: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 11px;
            color: wheat;
            border: 1px solid darkred;
            padding: 7px;
            margin: 0;
            top: 0;
            left: 90%;
            width: 15px;
            background-color: red;
        }

        /* Recent Messages */
        .recent-messages {
            width: 100%;
            box-sizing: border-box;
            border: 1.5px solid gray;
            margin: 3px 0;
            padding: 4px;
        }

        .recent-user-wrapper {
            width: 100%;
            display: flex;
            margin: 4px 1.2px;
            padding: 5px;
        }

        .recent-user-name {
            text-transform: capitalize;
        }

        .recent-user-data-wrapper {
            display: flex;
            cursor: pointer;
            width: 100%;
            justify-content: space-between;
            align-items: center;
        }

        .recent-user-data-grouping {
            display: flex;
        }

        .recent-user-additional-data {
            padding: 0 4px;
        }

        .recent-user-time-wrapper {
            position: relative;
        }

        .recent-user-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .recent-user-image-wrapper img {
            width: 34px;
            padding: 2px;
            border: 1px solid teal;
            height: 34px;
            object-fit: cover;
            object-position: top;
        }

        /* messages search wrapper style */
        .messages-search-wrapper {
            margin: 5px 0 3px 0;
        }

        .messages-search-result-wrapper img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            object-position: top;
            -o-object-fit: cover;
            -o-object-position: top;
        }

        .messages-search-user-wrapper {
            display: flex;
            text-transform: capitalize;
            margin: 0 3px !important;
            justify-content: flex-start;
            align-items: center;
            padding: 0 !important;
        }

        /* new recent style */

        .recent-messages {
            display: block;
            width: 100%;
            margin: 0;
            padding: 3.5px;
        }

        .recent-user {
            position: relative;
            display: flex;
            width: 100%;
            align-items: center;
        }

        .recent-user-image {
            padding: 2px;
        }

        .recent-user-image img {
            width: 35px;
            height: 35px;
            padding: 1.4px;
            object-fit: cover;
            object-position: top;
            -o-object-fit: cover;
            -o-object-position: top;
        }

        .w3-small {
            font-size: 11px !important;
        }

        .recent-user-data {
            padding: 0 3px;
            display: block;
            width: 100%;
        }

        .recent-sample {
            display: flex;
            padding: 0;
            margin: 0;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
    </style>
    <?php require_once __DIR__ . "/../lib/createEmiji.php";
    ?>
</head>

<body class="w3-theme-l2">
    <div class="container">
        <?php require '../menu/menu.php'; ?>
        <div class="messages-search-wrapper w3-border-theme w3-round">
            <input type="search" id="mySearch" onkeyup="filterTable(this.value.trim())" class="w3-input w3-round-medium w3-khaki w3-padding" placeholder="Search here" maxlength="40" />
            <div class="w3-round">
                <div id="usersTable" class="w3-round w3-round messages-search-result-wrapper">
                </div>
            </div>
        </div>

        <div class="active-users-wrapper w3-round">
            <!-- <h4>Active friends</h4> -->
            <div class="toggle-active">
                <span class="fa fa-arrow-circle-o-left" id="recentToggle"></span>
                <span id="online" class="diplay: block; margin: 10px;">
                    <span style="margin-right: 4px;">Online</span>
                </span>
                <button>
                    <i id="show-hide" class="fa fa-caret-down  fa-caret-up" title="hide/show active friends"></i>
                </button>
            </div>
            <div class="all-active-users-wrapper w3-panel w3-theme-d3 w3-round"></div>
        </div>
        <input type="hidden" id="me" value="<?php echo $me->username; ?>" />
        <div class="recent-messages w3-theme-d1 w3-round">
        </div>
        <div class="current-chat-wrapper w3-theme-dark w3-border w3-animate-zoom">
            <div class="current-chat-data">
                <div class="chat-image-wrapper">
                    <img id="chat-image" class="w3-circle" />
                </div>
                <span id="chatUser"></span>
            </div>
            <span id="close-chat"><i style="color:red;padding-right:4px;cursor:pointer;" class="fa fa-times"></i></span>
        </div>
        <div class="messages-wrapper w3-theme-l1 w3-border" id="chatBox">
        </div>

        <div class="message-sender-wrapper w3-margin-bottom">
            <form id="m_form" style="align-items:center;">
                <input id="reciever" type="hidden" />
                <input id="me" type="hidden" value="<?php echo $me->username; ?>">
                <div class="inputContainer" style="flex: 1;padding:0;">
                    <textarea style="height:100%;" title="Type message in there.." placeholder="Type msg..." id="message" class="w3-theme-l3" required data-emojiable="true" data-emoji-input="unicode"></textarea>
                </div>
                <button id="send_message" title="Send message" class="btn w3-theme-dark w3-hover-theme">
                    <i class="fa fa-send"></i>
                </button>
            </form>
        </div>
    </div>
</body>
<!--<script>Code</script> -->
<script>
    window.addEventListener('DOMContentLoaded', function() {
        loadActive()
        recentMessages()
    })

    document.getElementById("recentToggle").addEventListener('click', function() {
        userMessages()
        document.getElementsByClassName('recent-messages')[0].style.display = 'block'
        document.querySelector("#chatBox").style.display = 'none'
        document.querySelector("#m_form").style.display = 'none'
        document.getElementsByClassName("current-chat-wrapper")[0].style.display = "none"
        this.style.cursor = 'pointer'
        recentMessages()
        loadActive()
    })

    async function filterTable(value) {
        const allUsers = await makeRequest({
            method: 'POST',
            url: 'index.php',
            params: 'getUsers'
        })
        const usersWrapper = document.getElementById("usersTable")
        usersWrapper.innerHTML = ''
        var counter = 0;
        allUsers.forEach(user => {
            var userDetails = user.username + ' ' + user.fname + ' ' + user.lname
            if (userDetails.toLowerCase().indexOf(value.toLowerCase()) > -1 && value !== "") {
                counter += 1
                const span = document.createElement("TR")
                span.id = user.username
                span.addEventListener("click", function() {
                    recentActions({
                        id: this.id
                    })
                })
                span.setAttribute("class", "w3-theme-d1 w3-round messages-search-user-wrapper")
                var details = `<span class="w3-padding-small"><img src="../images/${user.profile_pic}" class="w3-circle w3-border w3-border-red" /></span><span class="w3-medium">${user.username}</span>`
                span.innerHTML = details
                var elements = usersWrapper.childNodes
                for (let i = 0; i < elements.length; i++) {
                    if (elements[i].id === span.id) {
                        usersWrapper.removeChild(elements[i])
                    }
                }
                console.log(userDetails)
                usersWrapper.appendChild(span)
            }
        })
        if (counter == 0 && value.trim() !== '') {
            usersWrapper.innerHTML = `<h4 class="w3-red w3-round w3-padding-small">No results found</h4>`
        }
    }

    // toggle between chat box and messages
    function recentActions({
        id,
        element = ''
    }) {
        getAllUnread();
        document.getElementsByClassName('recent-messages')[0].style.display = 'none';
        document.querySelector("#chatBox").style.display = 'block';
        document.querySelector("#m_form").style.display = 'flex';
        getUserData(id);
        document.getElementById("message").focus();
        document.getElementsByClassName("current-chat-wrapper")[0].style.display = "flex";
        document.getElementById('reciever').value = id;
        loadMessages(id);
        readMessage({
            id: id,
            element: element
        });
        document.getElementById("recentToggle").style.cursor = "pointer";
        loadActive()
    }

    // get recent sent and recieved messages

    async function recentMessages() {
        const recentWrapper = document.querySelectorAll(".recent-messages")[0]
        const me = document.getElementById("me").value
        recentWrapper.innerHTML = ''
        const result = await loadRecent()
        var senders = []

        if (typeof result !== "object") {
            var h4 = document.createElement("H4")
            h4.setAttribute("class", "w3-padding w3-round w3-red w3-center")
            recentWrapper.appendChild(h4)
            return 0
        }

        for (let i = 0; i < result.length; i++) {
            const obj = {
                method: 'POST',
                url: 'index.php',
                params: `getMessages&reciever=${result[i]}`
            }
            const resp = await makeRequest(obj)
            senders.push(resp[resp.length - 1])
        }

        for (const i in senders) {
            var rctUser, rctImg, rctImgImg, _class, unread, data, rctData, rctName, rctSample, rctSampleVal, rctDate, sfx = ''
            var user = {}

            elmnt = document.createElement("SPAN")
            rctUser = document.createElement("DIV")
            rctImg = document.createElement("DIV")
            rctImgImg = document.createElement("IMG")
            rctData = document.createElement("DIV")
            rctName = document.createElement("SPAN")
            rctSample = document.createElement("DIV")
            rctSampleVal = document.createElement("SPAN")
            rctDate = document.createElement("SPAN")

            rctUser.append(rctImg, rctData)
            rctImg.appendChild(rctImgImg)
            rctData.append(rctName, rctSample)
            rctSample.append(rctSampleVal, rctDate)

            rctUser.className = "recent-user w3-border w3-border-theme w3-theme-l3 w3-round"
            rctImg.className = "recent-user-image"
            rctImgImg.className = "w3-border-theme w3-border w3-circle w3-theme-action"
            rctData.className = "recent-user-data"
            rctName.className = "w3-medium"
            rctSample.className = "recent-sample"
            rctSampleVal.className = "w3-small"
            rctDate.className = "w3-text-theme w3-small"
            elmnt.className = "message-badge"
            bl = (senders[i].sender === me) ? true : false

            if (bl) {
                data = await makeRequest({
                    method: 'POST',
                    url: 'index.php',
                    params: `getUserData&user=${senders[i].reciever}`
                })
                sfx = 'You:'
                user.profile_pic = data.profile_pic
                user.name = data.username
            } else {
                user.profile_pic = senders[i].profile_pic
                user.name = senders[i].sender
            }

            unread = await makeRequest({
                method: 'POST',
                url: 'index.php',
                params: `getUnread&user=${user.name}`
            });

            (unread <= 0) ? elmnt.style.display = "none": null
            elmnt.textContent = unread

            rctData.appendChild(elmnt)

            rctUser.id = user.name
            rctUser.addEventListener("click", function() {
                recentActions({
                    id: this.id
                })
            })
            rctImgImg.src = `../images/${user.profile_pic}`
            rctImgImg.alt = user.name
            rctName.textContent = user.name
            rctDate.textContent = cvtDate(senders[i].date_)
            rctSampleVal.textContent = `${sfx} ${sliceLong(senders[i].body)}`

            recentWrapper.append(rctUser)
        }
    }

    // async function recentMessages() {
    // 	const recentWrapper = document.querySelectorAll(".recent-messages")[0]
    // 	const me = document.getElementById("me").value
    // 	recentWrapper.innerHTML = ''
    // 	const result = await loadRecent()
    // 	var senders = []
    // 	let output = ''
    // 	if (typeof result !== "object") {
    // 		output = `<h4 class='w3-padding w3-theme-light w3-round w3-center'>${result}</h4>`
    // 	} else {
    // 		for (let i = 0; i < result.length; i++) {
    // 			const obj = {
    // 				method: 'POST',
    // 				url: 'index.php',
    // 				params: `getMessages&reciever=${result[i]}`
    // 			}
    // 			const resp = await makeRequest(obj)
    // 			senders.push(resp[resp.length - 1])
    // 		}

    // 		for (const i in senders) {
    // 			if (senders[i].sender !== me) {
    // 				const unread = await makeRequest({
    // 					method: 'POST',
    // 					url: 'index.php',
    // 					params: `getUnread&user=${senders[i].sender}`
    // 				})
    // 				const myClass = (unread > 0) ? 'message-badge' : ''

    // 				output += `<div class="recent-user-wrapper w3-theme-l3 w3-round w3-display-container" id="${senders[i].sender}" onclick="recentActions({id: this.id})"><div class="recent-user-data-wrapper"><div class="recent-user-data-grouping"><div class="recent-user-image-wrapper w3-circle"><img src="../images/${senders[i].profile_pic}" alt="${senders[i].sender}" class="w3-circle"></div><div class="recent-user-additional-data"><span class="w3-medium recent-user-name">${senders[i].sender}</span><div class="recent-message-sample-wrapper w3-small"></span><span class="recent-sample w3-small">${sliceStr(senders[i].sender)}: ${sliceLong(senders[i].body)}</span></div></div></div><div class="recent-user-time-wrapper w3-small"><span>${cvtDate(senders[i].date_)}</span></div></div><span class='${myClass}'>${unread}<span></div>`

    // 			} else {
    // 				const unread = await makeRequest({
    // 					method: 'POST',
    // 					url: 'index.php',
    // 					params: `getUnread&user=${senders[i].reciever}`
    // 				})
    // 				const myClass = (unread > 0) ? 'message-badge' : ''

    // 				const obj = {
    // 					method: 'POST',
    // 					url: 'index.php',
    // 					params: `getUserData&user=${senders[i].reciever}`
    // 				}
    // 				const user = await makeRequest(obj)

    // 				output += `<div class="recent-user-wrapper w3-round w3-theme-l3 w3-display-container" id="${user.username}" onclick="recentActions({id: this.id})"><div class="recent-user-data-wrapper"><div class="recent-user-data-grouping"><div class="recent-user-image-wrapper w3-circle"><img src="../images/${user.profile_pic}" alt="${user.username}" class="w3-circle"></div><div class="recent-user-additional-data"><span class="w3-medium recent-user-name">${user.username}</span><div class="recent-message-sample-wrapper w3-small"></span><span class="recent-sample w3-small">You: ${sliceLong(senders[i].body)}</span></div></div></div><div class="recent-user-time-wrapper w3-small"><span>${cvtDate(senders[i].date_)}</span></div></div><span class='${myClass}'>${unread}<span></div>`
    // 			}
    // 		}
    // 	}
    // 	recentWrapper.innerHTML = output
    // }

    // Toggle hide Active friends
    $(document).ready(function() {
        $(".current-chat-wrapper").css("display", "none");
        $("#show-hide").click(function() {
            $(".all-active-users-wrapper").toggle(800, function() {
                $("#show-hide").toggleClass("fa-caret-up");
            });
        });
        $("#close-chat").click(function() {
            $(".current-chat-wrapper").slideUp();
        });
    });

    // make any json post request
    function makeRequest({
        method,
        url,
        params = ''
    }) {
        return new Promise((resolve, reject) => {
            var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

            xhr.open(method, url, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    resolve(JSON.parse(this.responseText));
                }
            }
            xhr.send(params);
        })
    }

    // anonymous fnction
    (function() {
        getAllUnread()
        document.getElementById("message").addEventListener("keyup", function(e) {
            if (e.keyCode === 13) {
                e.preventDefault()
                beforeSend()
            }
        })
        document.getElementById("send_message").addEventListener("click", beforeSend)
    })()

    async function loadRecent() {
        return new Promise(async function(resolve, reject) {
            const me = document.getElementById("me").value
            const people = []
            const newObj = {
                method: 'POST',
                url: 'index.php',
                params: 'getAllMessages'
            }
            const responses = await makeRequest(newObj)
            if (responses.infor) {
                console.log(responses.infor);
                resolve(responses.infor)
            } else {
                responses.forEach(async function(response) {
                    const user = (response.sender === me) ? response.reciever : response.sender
                    if (!people.includes(user)) {
                        people.push(user)
                    }
                })
                resolve(people)
            }
        })
    }
    // read user message
    async function readMessage({
        id,
        element = ''
    }) {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: `readMessage&user=${id}`
        }
        await makeRequest(obj)
        loadActive()
        loadUnread({
            id: id,
            element: element
        })
        getAllUnread()
    }

    // load each user unload messages
    async function loadUnread({
        id,
        element = ''
    }) {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: `getUnread&user=${id}`
        }
        const res = await makeRequest(obj);

        if (res > 0 && element !== '') {
            const child = document.createElement("span")
            child.setAttribute("class", "message-badge w3-card-4")
            const grandChild = document.createElement('span')
            const innerValue = document.createTextNode(res)
            grandChild.appendChild(innerValue)
            child.appendChild(grandChild)

            if (element.lastElementChild.classList.contains('message-badge') === true) {
                const lastResult = parseInt(element.lastElementChild.childNodes[0].innerHTML)
                if (lastResult !== res) {
                    const prevChild = element.lastElementChild
                    const reciever = document.querySelector('#reciever').value
                    element.replaceChild(child, prevChild)
                }
            }
            if (element.lastElementChild.classList.contains('message-badge') === false) {
                element.appendChild(child)
            }
        }
    }

    // onclick get specified user messages
    function userMessages() {
        const activeUsers = document.getElementsByClassName('active-user-wrapper')
        getAllUnread()
        for (var i = 0; i < activeUsers.length; i++) {
            const image_wrapper = activeUsers[i].getElementsByClassName("active-user-image-wrapper")[0]
            loadUnread({
                id: activeUsers[i].id,
                element: image_wrapper
            })

            activeUsers[i].onclick = function() {
                const imageWrapper = this.querySelectorAll(".active-user-image-wrapper")[0]
                recentActions({
                    id: this.id,
                    element: image_wrapper
                })
            }
        }
    }

    // get user data
    function getUserData(data) {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: `getUserData&user=${data}`
        }
        makeRequest(obj).then(function(res) {
            var chatImg = document.getElementById("chat-image");
            var chatUser = document.getElementById("chatUser");
            chatUser.innerHTML = res.username;
            chatImg.src = `../images/${res.profile_pic}`;
        });
    }

    async function getAllUnread() {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: `getAllUnread`
        }
        const unreadWrapper = document.getElementById("unread");
        const data = parseInt(await makeRequest(obj));
        if (data > 0) {
            unreadWrapper.style.display = 'flex';
            unreadWrapper.innerHTML = data;
        } else {
            if (unreadWrapper.style.display !== 'none') {
                unreadWrapper.style.display = 'none';
            }
        }
    }

    // Element hide toggle flex
    function showHide(element) {
        if (element.style.display !== 'none') {
            element.style.display = 'none'
        } else {
            element.style.display = 'flex'
        }
    }

    // function for tesing purporse
    function beforeSend() {
        var message = document.getElementById("message").value;
        const reciever = document.getElementById('reciever').value;
        if (reciever !== "") {
            if (message.trim().length > 0) {
                sendMessage(message, reciever);
                document.getElementById("message").value = "";
                document.getElementById("message").focus;
            }
        } else {
            alert("Ooops! Select user first..");
        }
    }

    // sends a message
    async function sendMessage(message, reciever) {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: `sendMessage&content=${message}&reciever=${reciever}`
        }
        await makeRequest(obj);
        loadMessages(reciever);
    }

    //Get active users function
    async function loadActive() {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: 'getActive'
        }
        activeFriends = await makeRequest(obj);
        var activeWrapper = document.getElementsByClassName("all-active-users-wrapper")[0];
        var element = document.getElementById("online");
        var output = "";

        if (activeFriends.length > 0) {
            var child, attr;
            activeWrapper.style.display = "flex";
            element.style.position = "relative";
            child = document.createElement("span");
            child.setAttribute("class", "w3-theme-l5 online-users w3-border-green");
            child.innerHTML = activeFriends.length;

            if (element.lastElementChild.classList.contains('online-users')) {
                const first = document.getElementsByClassName("online-users")[0]
                element.replaceChild(child, first)
            } else {
                element.appendChild(child)
            }
        } else {
            activeWrapper.style.display = 'none'
        }

        for (i in activeFriends) {
            output += '<div class="active-user-wrapper w3-round-large" id="' + activeFriends[i].username + '"><div class="active-user-data-wrapper"><div class="active-user-image-wrapper w3-white w3-circle"><img src="../images/' + activeFriends[i].profile_pic + '" class="w3-image w3-circle" alt="' + activeFriends[i].username + '"/><span class="online-dot"></span></div><span class="name">' + sliceStr(activeFriends[i].username) + '</span></div></div>';
        }
        activeWrapper.innerHTML = output;
        userMessages();
    }

    function myDate(dt1, dt2) {
        const d1 = Date.parse(dt1);
        const d2 = Date.parse(dt2);
        var diff = (d1 - d2) / 1000;
        return diff;
    }

    function getUserMessages(user) {
        return new Promise(function(resolve, reject) {
            const obj = {
                method: 'POST',
                url: 'index.php',
                params: `getUserMessages&user=${user}`
            }
            makeRequest(obj).then(function(resp) {
                resolve(resp[0])
            })
        })

    }
    // get user messages

    async function loadMessages(user) {
        const obj = {
            method: 'POST',
            url: 'index.php',
            params: `getMessages&reciever=${user}`
        }
        const chatBox = document.getElementById("chatBox");
        chatBox.textContent = null;
        messages = await makeRequest(obj);

        if (messages.infor) {
            var h3 = document.createElement("H3");
            h3.className = "w3-center w3-padding w3-margin w3-round w3-red";
            h3.textContent = messages.infor;
            chatBox.appendChild(h3);
            return 0;
        }

        for (var i in messages) {
            const nxt = parseInt(i) + 1;
            const prv = parseInt(i) - 1;
            const nowDate = new Date();

            var elements, senderImage, msgBody, csender, msgContainer, bl, sign, msgDate, theme, dateVal, id, date_, message, nxtMsg, prvMsg, pfx, addDate = false;
            elements = document.createElement("DIV");
            msgContainer = document.createElement("DIV");
            senderImage = document.createElement("IMG");
            msgBody = document.createElement("SPAN");
            msgDate = document.createElement("DIV");
            dateVal = document.createElement("SPAN");
            dateSep = document.createElement("SPAN");
            msg = messages[i];
            nxtMsg = messages[nxt];
            prvMsg = messages[prv];
            bl = (msg.sender !== user) ? true : false;
            pfx = (bl) ? 'my-' : 'user-';
            theme = (bl) ? 'w3-theme-d2' : 'w3-theme-l3';
            csender = msg.sender;
            dateSep.className = "w3-text-amber timer-wrapper w3-center w3-round";

            if (msg.body.trim() !== '') {
                msgContainer.id = msg.id;
                msgBody.textContent = msg.body;
                dateVal.innerHTML = cvtDate(msg.date_);
                senderImage.src = `../images/${msg.profile_pic}`;
                senderImage.className = `w3-image w3-circle`;
                msgDate.appendChild(dateVal);

                msgContainer.className = `${pfx}messages-wrapper`;
                msgBody.className = `w3-padding ${theme} w3-medium ${pfx}message-body-wrapper`;
                senderImage.classList.add(`${pfx}image-wrapper`);
                msgDate.className = `${pfx}message-date-wrapper`;
                (bl) ? msgContainer.appendChild(msgBody): null;

                if (nxt < messages.length && nxtMsg.sender === csender && noMin(nxtMsg.date_, msg.date_)) {
                    if (msg.id !== messages[0].id && prvMsg.sender === csender && noMin(msg.date_, prvMsg.date_)) {
                        msgBody.classList.add(`${pfx}middle-message-margin`);
                        (!bl) ? msgContainer.appendChild(msgBody): null;
                    } else {
                        msgBody.classList.add(`${pfx}top-message-margin`);
                        (!bl) ? msgContainer.appendChild(msgBody): null;
                    }
                } else {
                    if (msg.id !== messages[0].id && prvMsg.sender === csender && noMin(msg.date_, prvMsg.date_)) {
                        msgBody.classList.add(`${pfx}bottom-message-margin`);
                        msgContainer.appendChild(senderImage);
                        (!bl) ? msgContainer.appendChild(msgBody): null;
                        addDate = true;
                    } else {
                        msgContainer.appendChild(senderImage);
                        (!bl) ? msgContainer.appendChild(msgBody): null;
                        addDate = true;
                    }
                }
            }
            chatBox.appendChild(msgContainer);
            (addDate) ? chatBox.appendChild(msgDate): false;
        }

        chatBox.scrollTop = chatBox.scrollHeight;
        document.documentElement.scrollTop = document.documentElement.scrollHeight;
    }

    function noMin(date1, date2) {
        var d1 = Date.parse(date1);
        var d2 = Date.parse(date2);

        var diff = Math.abs((d1 - d2) / 1000);
        if (diff <= 60) {
            return true;
        } else {
            return false;
        }
    }

    function cvtDate(date) {
        const inputDate = Date.parse(date)
        const dateNow = Date.now()
        const diff = Math.abs((dateNow - inputDate) / 1000)
        const days = ["Sun", "Mon", "Tues", "Wed", "Thurs", "Fri", "Sat"]
        const months = ["Jan", "Feb", "Mar", "Aprl", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"]
        const today = new Date(dateNow)
        const myDate = new Date(inputDate)
        const hours = myDate.getHours() < 10 ? `0${myDate.getHours()}` : myDate.getHours()
        const minutes = myDate.getMinutes() < 10 ? `0${myDate.getMinutes()}` : myDate.getMinutes()

        if (today.getFullYear() !== myDate.getFullYear()) {
            const monthName = months[myDate.getMonth()]
            return `${monthName} ${myDate.getDate()}, ${myDate.getFullYear()}`
        }
        if (diff < 60) {
            return `Just now`
        } else if (diff >= 60 && diff < 3600) {
            const result = Math.round(diff / 60)
            return result > 1 ? `${result} mins` : `1 min`
        } else if (diff >= 3600 && diff < 86400) {
            if (today.getDay() === myDate.getDay()) {
                return `${hours}:${minutes}`
            } else {
                return `Yesterday ${hours}:${minutes}`
            }
        } else if (diff >= 86400 && diff < 86400 * 7) {
            const result = ``;
            const dayName = days[myDate.getDay()]
            const yester = days[today.getDay() - 1]

            if (dayName === yester) {
                return `Yesterday ${hours}:${minutes}`
            } else {
                return `${dayName} ${hours}:${minutes}`
            }
        } else {
            const monthName = months[myDate.getMonth()]
            return `${monthName} ${myDate.getDate()} ${hours}:${minutes}`
        }
    }

    function sliceLong(str) {
        if (str.length > 14) {
            newStr = str.slice(0, 14);
            return newStr + '...';
        } else {
            return str;
        }
    }

    function sliceStr(str) {
        if (str.length > 6) {
            newStr = str.slice(0, 5);
            return newStr + `<span class="w3-small">...</span> `;
        } else {
            return str;
        }
    }
</script>

</html>