const editButtonPhoto = document.querySelector("[data-story-edit-icon_photo]");
const editButtonVideo = document.querySelector("[data-story-edit-icon_video]");
const editButtonAudio = document.querySelector("[data-story-edit-icon_audio]");
const editModalPhoto = document.querySelector("[data-photo-edit-modal]");
const editModalVideo = document.querySelector("[data-video-edit-modal]");
const editModalAudio = document.querySelector("[data-audio-edit-modal]");
const closeModalButtonPhoto = document.querySelector("[data-hide-edit-photo-modal]");
const closeModalButtonVideo = document.querySelector("[data-hide-edit-video-modal]");
const closeModalButtonAudio = document.querySelector("[data-hide-edit-audio-modal]");
const pickPhotoButton = document.querySelector("[data-pick-photo]");
const pickVideoButton = document.querySelector("[data-pick-video]");
const pickAudioButton = document.querySelector("[data-pick-audio]");
const pickPhotoInput = document.querySelector("[data-edit-photo-input]");
const pickVideoInput = document.querySelector("[data-edit-video-input]");
const pickAudioInput = document.querySelector("[data-edit-audio-input]");
const photoPlaceholder = document.querySelector("[data-photo-placeholder ]");
const videoPlaceholder = document.querySelector("[data-video-placeholder ]");
const audioPlaceholder = document.querySelector("[data-audio-placeholder ]");
const submitButtonPhoto = document.querySelector("[data-edit-photo-form-submit]");
const submitButtonVideo = document.querySelector("[data-edit-video-form-submit]");
const submitButtonAudio = document.querySelector("[data-edit-audio-form-submit]");

console.log({ closeModalButtonPhoto });
console.log({ closeModalButtonVideo });
console.log({ closeModalButtonAudio });
editModalPhoto.addEventListener("click", e => {
    if (editModalPhoto.isSameNode(e.target)) {
        editModalPhoto.classList.toggle("hide");
    }
})
editModalVideo.addEventListener("click", e => {
    if (editModalVideo.isSameNode(e.target)) {
        editModalVideo.classList.toggle("hide");
    }
})
editModalAudio.addEventListener("click", e => {
    if (editModalAudio.isSameNode(e.target)) {
        editModalAudio.classList.toggle("hide");
    }
})

submitButtonPhoto.addEventListener("click", (e) => {
    if (pickPhotoInput.files.length) {
        if (!pickPhotoInput.files[0].type.startsWith("image/")) {
            alert("Unsupported image file type!");
            return;
        }
        submitButtonPhoto.closest("form").submit();
    } else {
        alert("No file choosen!");
    }
});
submitButtonVideo.addEventListener("click", (e) => {
    if (pickVideoInput.files.length) {
        if (!pickVideoInput.files[0].type.startsWith("video/")) {
            alert("Unsupported video file type!");
            return;
        }
        submitButtonVideo.closest("form").submit();
    } else {
        alert("No file choosen!");
    }
});
submitButtonAudio.addEventListener("click", (e) => {
    if (pickAudioInput.files.length) {
        if (!pickAudioInput.files[0].type.startsWith("audio/")) {
            alert("Unsupported audio file type!");
            return;
        }
        submitButtonAudio.closest("form").submit();
    } else {
        alert("No file choosen!");
    }
});

pickPhotoButton.addEventListener("click", (e) => {
    pickPhotoInput.click();
});
pickVideoButton.addEventListener("click", (e) => {
    pickVideoInput.click();
});
pickVideoInput.addEventListener("change", (e) => {
    let files = pickVideoInput.files;
    videoPlaceholder.innerHTML = ""
    if (!files.length || !files[0].type.startsWith("video/")) {
        return;
    }
    const video= document.createElement("video");
    video.src = URL.createObjectURL(files[0]);
    const dimensions = videoPlaceholder.getBoundingClientRect();
    video.setAttribute(
        "style",
        `width: ${dimensions.width}px;height:${dimensions.height}px;`
    );
    videoPlaceholder.appendChild(video);
});
pickAudioInput.addEventListener("change", (e) => {
    let files = pickAudioInput.files;
    audioPlaceholder.innerHTML = ""
    if (!files.length || !files[0].type.startsWith("audio/")) {
        return;
    }
    const audio = document.createElement("audio");
    audio.src = URL.createObjectURL(files[0]);
    const dimensions = audioPlaceholder.getBoundingClientRect();
    audio.setAttribute(
        "style",
        `width: ${dimensions.width}px;height:${dimensions.height}px;`
    );
    audioPlaceholder.appendChild(audio);
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

editButtonPhoto.addEventListener("click", (e) => {
    editModalPhoto.classList.remove("hide");
});
editButtonVideo.addEventListener("click", (e) => {
    editModalVideo.classList.remove("hide");
});
editButtonAudio.addEventListener("click", (e) => {
    editModalAudio.classList.remove("hide");
});

closeModalButtonPhoto.addEventListener("click", (e) => {
    editModalPhoto.classList.add("hide");
});
closeModalButtonVideo.addEventListener("click", (e) => {
    editModalVideo.classList.add("hide");
});
closeModalButtonAudio.addEventListener("click", (e) => {
    editModalAudio.classList.add("hide");
});

