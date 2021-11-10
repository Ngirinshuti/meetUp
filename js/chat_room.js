// Wait until DOM content are loaded

window.addEventListener("DOMContentLoaded", (e) => {
  // select the messages container element
  const chatsListContainer = document.querySelector(".chatMessageList");

  // smoothly scroll the messages container to the bottom, so that we can see the new messages
  chatsListContainer.scrollTo({
    top: chatsListContainer.scrollHeight,
    behavior: "smooth",
  });

  // also scroll the body to the bottom to see everything
  document.body.scrollIntoView(false);

  // select the message input element
  const messageInput = document.querySelector("[data-chat-input]");

  // select the message editor element created by emoji library
  const messageEditor = messageInput.nextElementSibling;

  // select message send button
  const sendButton = document.querySelector("[data-chat-send]");

  // send pressing ENTER in the message input element
  messageInput.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      sendMessage(messageInput.value);
      clearInputs();
    }
  });

  // send pressing ENTER in the editor element created by emoji library
  messageEditor.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      // make message input's value similar to the text contet of the
      // editor element created by emoji library
      messageInput.value = messageEditor.textContent;
      sendMessage();
      clearInputs();
    }
  });

  // resets message inputs
  function clearInputs() {
    // clear message input value
    messageInput.value = "";
    // clear editor text content (value) by emoji library
    messageEditor.textContent = "";
  }

  // send by clicking send button
  sendButton.addEventListener("click", (e) => {
    sendMessage();
    clearInputs();
  });

  /**
   * Sends a message
   *
   * @param {function} callback callback function
   * @returns void
   */
  function sendMessage(callback = null) {
    // get a value from message input element
    const msg = messageInput.value;

    // check if the value isn't empty
    if (msg.trim() === "") {
      return showMsg("Empty message!");
    }

    // get a message reciever if any
    const reciever = messageInput.dataset.reciever;
    // get a current group id if any
    const groupId = messageInput.dataset.groupId;
    // get a csrf token key
    const csrfKey = messageInput.dataset.csrfKey;
    // get a csrf token value
    const csrfToken = messageInput.dataset.csrfToken;

    // initialze new formdata object
    const body = new FormData();

    // add all necessary request body to the formdata
    body.append(csrfKey, csrfToken);
    body.append("reciever", reciever);
    body.append("group_id", groupId);
    body.append("message", msg);

    // make send message request to the corresponding url
    request("../chat/send_message.php", body).then((msg) => {
      // You should recieve the newly created message and display it
      addMessage(msg);
      // call the callback function if any was provided
      typeof callback === "function" && callback();
    });
  }

  function showMsg(msg) {
    alert(msg);
  }

  // create message element
  function addMessage(msg) {
    // get message related data from data attributes
    const lastMessageDate = messageInput.dataset.lastMessageDate;
    const lastMessageSender = messageInput.dataset.lastMessageSender;
    const myUsername = messageInput.dataset.me;

    // remove empty messages  placeholder if any
    document.querySelector("[data-no-messages]")?.remove();

    // grab message template element from html document
    const messageTemp = document
      .querySelector("[data-message-template]")
      .content.cloneNode(true);

    // select essential elements from message template
    const senderImg = messageTemp.querySelector("img");
    const body = messageTemp.querySelector(".chatMessageBody");
    const messageInfo = messageTemp.querySelector(".chatMessageInfo");
    const messageTime = messageTemp.querySelector(".chatMessageTime");
    const messageStatus = messageTemp.querySelector(".chatStatus");

    // add content to the selected message template
    senderImg.src += msg.profile_pic;
    body.textContent = msg.body;
    messageTime.textContent = formatDate(msg.created_at);
    messageStatus.textContent = msg.status;

    // decide to add a message as a full message with user image
    // and message info or append to the existing message group element
    // if it was sent by the same user and less than a minute differance
    if (
      !lastMessageDate.trim() ||
      lastMessageSender !== msg.sender ||
      !lessThanMinute(msg.created_at, lastMessageDate)
    ) {
      // when we have to create a new message group

      // create a new message group element
      const messageGroup = document.createElement("div");
      messageGroup.classList.add("chatMessageGroup");
      messageGroup.classList.add(
        msg.sender === myUsername.trim() ? "sent" : "recieved"
      );

      // add our message element to the message group element
      messageGroup.append(
        messageTemp.querySelector(".chatMessage"),
        messageInfo
      );

      // add user related info
      messageGroup.prepend(messageTemp.querySelector(".chatMessageUser"));
      chatsListContainer.appendChild(messageGroup);
    } else {
      // when a message should be appended to existing message group

      // select last message group element
      const messageGroup = chatsListContainer.querySelector(
        ".chatMessageGroup:last-of-type"
      );

      // remove message info like status and time since they already exist
      // in the message group
      messageInfo.remove();

      // Add message to the message group right before message info
      messageGroup.insertBefore(
        messageTemp.querySelector(".chatMessage"),
        messageGroup.querySelector(".chatMessageInfo")
      );
    }

    // reset input field text content
    // this may not clear input content since we are using emoji editor div
    messageInput.textContent = "";
    // change when last message was created used to fetch new messages
    // so that we only fetch latest messages
    messageInput.setAttribute("data-last-message-date", msg.created_at);
    // also set the last user to send a message whitch we are about to display
    messageInput.setAttribute("data-last-message-sender", msg.sender);
    // reset input field value
    // // this may not clear input content since we are using emoji editor div
    messageInput.value = "";

    // smoothly scroll the messages container to the bottom, so that we can see the new message
    chatsListContainer.scrollTo({
      top: chatsListContainer.scrollHeight,
      behavior: "smooth",
    });
  }

  // checks for newest messages
  function check_new_messages() {
    // get the date latest message was sent or recieved
    // or use 1970-12-31 so that we can only select messages
    // that were sent after that date
    const lastMessageDate =
      messageInput.dataset.lastMessageDate.trim() ||
      new Date(new Date().setFullYear(1970, 12, 31)).toJSON();

    // get the id of the group we are currently in if any
    // so that we can check for messages only from that group
    const groupId = messageInput.dataset.groupId;

    // get the username of a friend we are chatting with
    // if we are not in a group, so that we can only fetch messages from
    // him/her
    const chatFriend = messageInput.dataset.chatFriend;

    // design the url to fetch newest messages
    const url = `
    ../chat/check_new_messages.php?group_id=${groupId}&sender=${chatFriend}&last_message_date=${lastMessageDate}
    `;

    // send the request to fetch new messages
    request(url).then((messages) => {
      // when messages are available loop through them
      messages.forEach((msg) => {
        // add an individual message to the screen
        addMessage(msg);
      });
    });
  }

  // run check chek_new_messages function every 3 seconds
  // to get new messages
  setInterval(check_new_messages, 3000);
});

/**
 * Checks whether two dates has less than a minute differance
 *
 * @param {string} date1 the first date
 * @param {string} date2 another date
 * @returns boolean
 */
function lessThanMinute(date1, date2) {
  const diff = (new Date(date1) - new Date(date2)) / (1000 * 60);
  // console.log({ diff });
  if (1 > diff && diff > -1) {
    return true;
  }

  return false;
}

/**
 * Legacy function that formats a date into a more human readable form
 *
 * @param {string} date date to format
 * @returns string formated date
 */
function formatDate(date) {
  const inputDate = Date.parse(date);
  const dateNow = Date.now();
  const diff = Math.abs((dateNow - inputDate) / 1000);
  const days = ["Sun", "Mon", "Tues", "Wed", "Thurs", "Fri", "Sat"];
  const months = [
    "Jan",
    "Feb",
    "Mar",
    "Aprl",
    "May",
    "June",
    "July",
    "Aug",
    "Sept",
    "Oct",
    "Nov",
    "Dec",
  ];
  const today = new Date(dateNow);
  const myDate = new Date(inputDate);
  const hours =
    myDate.getHours() < 10 ? `0${myDate.getHours()}` : myDate.getHours();
  const minutes =
    myDate.getMinutes() < 10 ? `0${myDate.getMinutes()}` : myDate.getMinutes();

  if (today.getFullYear() !== myDate.getFullYear()) {
    const monthName = months[myDate.getMonth()];
    return `${monthName} ${myDate.getDate()}, ${myDate.getFullYear()}`;
  }
  if (diff < 60) {
    return `now`;
  } else if (diff >= 60 && diff < 3600) {
    const result = Math.round(diff / 60);
    return result > 1 ? `${result} mins` : `1 min`;
  } else if (diff >= 3600 && diff < 86400) {
    if (today.getDay() === myDate.getDay()) {
      return `${hours}:${minutes}`;
    } else {
      return `Yesterday ${hours}:${minutes}`;
    }
  } else if (diff >= 86400 && diff < 86400 * 7) {
    const result = ``;
    const dayName = days[myDate.getDay()];
    const yester = days[today.getDay() - 1];

    if (dayName === yester) {
      return `Yesterday ${hours}:${minutes}`;
    } else {
      return `${dayName} ${hours}:${minutes}`;
    }
  } else {
    const monthName = months[myDate.getMonth()];
    return `${monthName} ${myDate.getDate()} ${hours}:${minutes}`;
  }
}

async function request(url, body = null) {
  const method = body ? "POST" : "GET";

  const res = await fetch(url, { method, ...(body ? { body } : {}) });
  return await res.json();
}
