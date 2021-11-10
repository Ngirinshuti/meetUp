const editButton = document.querySelector("[data-profile-edit-icon]");
const editModal = document.querySelector("[data-profile-edit-modal]");
const closeModalButton = document.querySelector("[data-hide-edit-modal]");
const pickPhotoButton = document.querySelector("[data-pick-photo]");
const pickPhotoInput = document.querySelector("[data-edit-file-input]");
const photoPlaceholder = document.querySelector("[data-photo-placeholder ]");
const submitButton = document.querySelector("[data-edit-form-submit]");

console.log({ closeModalButton });

editModal.addEventListener("click", e => {
    if (editModal.isSameNode(e.target)) {
        editModal.classList.toggle("hide");
    }
})

submitButton.addEventListener("click", (e) => {
    if (pickPhotoInput.files.length) {
        if (!pickPhotoInput.files[0].type.startsWith("image/")) {
            alert("Unsupported image file type!");
            return;
        }
        submitButton.closest("form").submit();
    } else {
        alert("No file choosen!");
    }
});

pickPhotoButton.addEventListener("click", (e) => {
    pickPhotoInput.click();
});

pickPhotoInput.addEventListener("change", (e) => {
    let files = pickPhotoInput.files;
    photoPlaceholder.innerHTML = ""
    if (!files.length || !files[0].type.startsWith("image/")) {
        return;
    }
    const img = document.createElement("img");
    img.src = URL.createObjectURL(files[0]);
    const dimensions = photoPlaceholder.getBoundingClientRect();
    img.setAttribute(
        "style",
        `width: ${dimensions.width}px;height:${dimensions.height}px;`
    );
    photoPlaceholder.appendChild(img);
});

editButton.addEventListener("click", (e) => {
    editModal.classList.remove("hide");
});

closeModalButton.addEventListener("click", (e) => {
    editModal.classList.add("hide");
});
