const more = document.querySelector("[more-options]")
const options = document.querySelector("[options-div]")
const edit = document.querySelector("[options]")
const deletenow = document.querySelector("[delete-option]")
options.addEventListener("click", e => {
    if (options.isSameNode(e.target)) {
        options.classList.toggle("hide");
    }
})

edit.addEventListener("click", (e) => {
    options.classList.remove("hide");
});
