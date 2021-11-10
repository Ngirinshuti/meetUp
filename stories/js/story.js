// make story items clickable
const storyItems = document.querySelectorAll(".storyItem");
storyItems.forEach((item) => {
    item.addEventListener("click", (e) => {
        item.querySelector(".storyLink").click();
    });
});

const storyPreviewContainer = document.querySelector(".storyPreviewContainer");
const storyPreviewProgressContainer = storyPreviewContainer.querySelector(
    ".storyPreviewProgressContainer.active"
);
const storyPreview = storyPreviewContainer.querySelector(".storyPreview");
const storyPreviewProgress = storyPreviewProgressContainer.querySelector(
    ".storyPreviewProgress"
);
const nextBtn = storyPreviewContainer.querySelector(".storyPreviewNextButton");
const prevBtn = storyPreviewContainer.querySelector(".storyPreviewPrevButton");
const goHomeBtn = document.querySelector("[data-redirect-home]");
const replyInput = storyPreviewContainer.querySelector(
    "form > input[name='reply']"
);
const video = storyPreview.querySelector("video");
const playIcon = storyPreview.querySelector("[data-play-icon");
const pauseIcon = storyPreview.querySelector("[data-pause-icon");

let paused = false;
let progressPercentage = 0;
let interval;
init();

function init() {

    storyPreviewProgress.style.width = "0";

    if (replyInput) {
        replyInput.addEventListener("focus", (e) => {
            paused = true;
        });
        replyInput.addEventListener("blur", (e) => {
            paused = false;
        });
    }

    if (!video) {
        storyPreview.addEventListener("click", e => {
            paused = !paused;
        })
        interval = setInterval(() => {
            if (!paused) {
                animateProgessBar();
            }
        }, 50);

        pauseIcon.addEventListener("click", (e) => {
            paused = true;
            playIcon.classList.remove("hide");
            pauseIcon.classList.add("hide");
        });

        playIcon.addEventListener("click", e => {
            paused = false;
            pauseIcon.classList.remove("hide");
            playIcon.classList.add("hide");
        });
        return;
    }

    handleVideo();
    video.addEventListener("timeupdate", (e) => {
        progressPercentage = (video.currentTime * 100) / video.duration;
        storyPreviewProgress.style.width = `${progressPercentage}%`;
    });
}

function animateProgessBar() {
    if (paused) return;
    if (progressPercentage < 100) {
        storyPreviewProgress.style.width = `${progressPercentage}%`;
        progressPercentage += 0.67;
    } else {
        clearInterval(interval);
        (goHomeBtn || nextBtn).click();
        // progressPercentage = 0
    }
}

function handleVideo() {
    const volumeIcon = storyPreview.querySelector("[data-volume-icon");

    const playVideo = (e) => video.play();
    const pauseVideo = (e) => video.pause();
    const toggleMuteVideo = (e) => {
        video.muted = !video.muted;
    };

    playIcon.addEventListener("click", playVideo);
    pauseIcon.addEventListener("click", pauseVideo);
    volumeIcon.addEventListener("click", toggleMuteVideo);

    const removeListeners = (e) => {
        playIcon.removeEventListener("click", playVideo);
        pauseIcon.removeEventListener("click", pauseVideo);
        volumeIcon.removeEventListener("click", toggleMuteVideo);
    };

    video.addEventListener("error", removeListeners);
    video.addEventListener("ended", removeListeners);

    video.addEventListener("click", (e) => {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    });

    const handleVolume = () => {
        video.play();
        let src = volumeIcon.querySelector("img").src;
        if (video.muted) {
            volumeIcon.querySelector("img").src = src.replace("up", "off");
        } else {
            volumeIcon.querySelector("img").src = src.replace("off", "up");
        }
    };

    handleVolume();

    video.addEventListener("volumechange", handleVolume);

    video.addEventListener("pause", (e) => {
        pauseIcon.classList.add("hide");
        playIcon.classList.remove("hide");
    });

    video.addEventListener("play", (e) => {
        pauseIcon.classList.remove("hide");
        playIcon.classList.add("hide");
    });
}
