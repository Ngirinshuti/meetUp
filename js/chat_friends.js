
// chat links click
const friendLink = document.querySelectorAll(".chatFriendLink");

friendLink.forEach((link) => {
  link.closest(".chatFriend").addEventListener("click", (e) => {
    if (e.target.matches(".chatFriend")) {
      link.click();
    }
  });
});


// selecting users for groups
