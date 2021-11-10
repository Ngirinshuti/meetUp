window.addEventListener("DOMContentLoaded", (e) => {
  // icons
  const imageSelectIcon = document.querySelector("[data-image-select-icon]");
  const videoSelectIcon = document.querySelector("[data-video-select-icon]");

  // submit btn
  const submitButton = document.querySelector("[data-submit-btn]");

  // file inputs
  const imageInput = document.querySelector("[data-image-input]");
  const videoInput = document.querySelector("[data-video-input]");

  // preview element
  const filePreview = document.querySelector("[data-file-preview]");

  // events
  imageSelectIcon.addEventListener("click", (e) => {
    imageInput.click();
  });

  videoSelectIcon.addEventListener("click", (e) => {
    videoInput.click();
  });

  // file preview

  function previewFile(file) {
    const isVideo = file.type.startsWith("video");
    const isImage = file.type.startsWith("image");

    if (!isVideo && !isImage) {
      alert("Can't preview! File type not expected.");
      return;
    }

    const previewEl = document.createElement(isVideo ? "video" : "img");

    previewEl.src = URL.createObjectURL(file);
    previewEl.setAttribute("controls", "true");

    filePreview.innerHTML = "";

    filePreview.appendChild(previewEl);
    filePreview.classList.remove("hide");
  }

  // legacy js

  if (!videoInput || !imageInput) {
    return alert("Something is wrong!");
  }

  videoInput.addEventListener("change", (e) => {
    const vidElement = document.createElement("video");
    const file = videoInput.files[0];

    if (
      videoInput.files[0] &&
      !vidElement.canPlayType(videoInput.files[0].type)
    ) {
      if (confirm("Image file type not supported!")) {
        videoInput.click();
      } else {
        videoInput.form.reset();
        return;
      }
    }

    if (videoInput.files[0] && videoInput.files[0].size > 100000000) {
      alert(
        "Choosen file is bigger than " + Math.round(100000000 / 1000000) + "MB"
      );
      if (confirm("Image file type not supported!")) {
        videoInput.click();
      } else {
        videoInput.form.reset();
        return;
      }
    }

    if (file) {
      previewFile(file);
    } else {
      filePreview.classList.add("hide");
    }
  });

  imageInput.addEventListener("change", (e) => {
    if (imageInput.files[0] && !imageInput.files[0].type.startsWith("image/")) {
      if (confirm("Image file type not supported!")) {
        imageInput.click();
      } else {
        imageInput.form.reset();
        return;
      }
    }

    const file = imageInput.files[0];

    if (file) {
      previewFile(file);
    } else {
      filePreview.classList.add("hide");
    }
  });
});
