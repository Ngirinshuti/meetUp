const recentLinks = document.querySelectorAll(".recentChatLink");

recentLinks.forEach((link) => {
  link.closest(".recentChat").addEventListener("click", (e) => {
    if (
      e.target.matches(".recentChat") ||
      !e.target.matches(".recentChatUserName") && e.target.closest(".recentChatBody")
    ) {
      link.click();
    }
  });
});
