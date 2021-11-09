// globals
// DOM elements
window.addEventListener("DOMContentLoaded", (e) => {
    // container
    const commentContainers = document.querySelectorAll(".commentContainer");
    
    // comment template
    const commentTemp = document.querySelector("[data-comment-template]");

    commentContainers.forEach((container) => {
        let LOADED = false;
        
        const postId = container.dataset.postId;
        const currentUser = container.dataset.currentUser;
        
        // container toggle btn
        const containerToggleBtn = container
        .closest(".userpost")
        .querySelector("[data-comment-toggle]");
        // prev comments btn
        const prevCommentsBtn = container.querySelector("[data-prev-comments]");
        // next comments btn
        const nextCommentsBtn = container.querySelector("[data-next-comments]");
        // comment textarea
        const commentTextarea = container.querySelector("textarea");
        // comment btn
        const commentBtn = container.querySelector("[data-comment-btn]");

        // comment list container
        const commentList = container.querySelector(".commentList");
        // comment form
        const form = commentTextarea.closest("form");
        // editor
        const editor = commentTextarea.nextElementSibling;

        // toggle hide comment container
        containerToggleBtn.addEventListener("click", async (e) => {
            container.classList.toggle("hide");
            if (!LOADED) {
                const url = `../post/comment_list.php?post_id=${postId}`;
                const result = await fetchComments(url, postId)
                

                if (result.prev_url) {
                    prevCommentsBtn.classList.remove("hide");
                    prevCommentsBtn.setAttribute("data-url", result.prev_url);
                } else {
                    prevCommentsBtn.classList.add("hide");
                }
                
                if (result.next_url) {
                    nextCommentsBtn.classList.remove("hide");
                    nextCommentsBtn.setAttribute("data-url", result.next_url);
                } else {
                    nextCommentsBtn.classList.add("hide");
                }
                
                const comments = result.data.map((comment) => {
                    return makeComment(comment);
                });
                
                commentList.append(...comments);
                LOADED = true;
            }
        });

        const handleCreateComment = async (e = null) => {
            e.preventDefault();
            if (commentTextarea.value.trim().length === 0){
                return;
            }
            const body = new FormData(form);
            createComment(body).then((commentEl) => {
                commentList.prepend(commentEl);
                commentTextarea.value = "";
                editor.textContent = "";
            });
        };

        
        // pagination
        nextCommentsBtn.addEventListener("click", async e => {
            if (nextCommentsBtn.getAttribute("data-url").length) {
                const result = (await fetchComments(nextCommentsBtn.getAttribute("data-url"), postId))
                const comments = result.data.map((comment) => {
                    return makeComment(comment);
                });
                commentList.innerHTML = "";
                commentList.append(...comments);

                if (result.prev_url) {
                    prevCommentsBtn.classList.remove("hide");
                    prevCommentsBtn.setAttribute("data-url", result.prev_url);
                } else {
                    prevCommentsBtn.classList.add("hide");
                }

                if (result.next_url) {
                    nextCommentsBtn.classList.remove("hide");
                    nextCommentsBtn.setAttribute("data-url", result.next_url);
                } else {
                    nextCommentsBtn.classList.add("hide");
                }
            }
        })

        // pagination
        prevCommentsBtn.addEventListener("click", async e => {
            if (prevCommentsBtn.getAttribute("data-url").length) {
                const result = (await fetchComments(prevCommentsBtn.getAttribute("data-url"), postId))
                const comments = result.data.map((comment) => {
                    return makeComment(comment);
                });
                commentList.innerHTML = "";
                commentList.prepend(...comments);

                if (result.prev_url) {
                    prevCommentsBtn.classList.remove("hide");
                    prevCommentsBtn.setAttribute("data-url", result.prev_url);
                } else {
                    prevCommentsBtn.classList.add("hide");
                }

                if (result.next_url) {
                    nextCommentsBtn.classList.remove("hide");
                    nextCommentsBtn.setAttribute("data-url", result.next_url);
                } else {
                    nextCommentsBtn.classList.add("hide");
                }
            }
        })


        // create comment
        editor.addEventListener("keyup", (e) => {
            e.preventDefault();
            if (e.key === "Enter") {
                commentTextarea.value = editor.textContent;
                handleCreateComment(e);
            }
        });
        commentBtn.addEventListener("click", handleCreateComment);
    });

    async function fetchComments(url) {
        const response = await fetch(url);
        const result = await response.json();

        if (result.errors) {
            handleErrors(result.errors);
            return {};
        }

        if (result.length === 0) {
            return {};
        } else {
            return result;
        }
    }

    function handleErrors(errors) {
        console.log(errors);
    }

    async function createComment(body) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const url = `../post/comment_create.php/`;
            xhr.open("POST", url);
            xhr.addEventListener("readystatechange", (e) => {
                if (xhr.readyState === 4) {
                    const result = JSON.parse(xhr.responseText);
                    if (result.errors) {
                        handleErrors(result.errors);
                        resolve([]);
                    }
                    // console.log(result);
                    resolve(makeComment(result));
                }
            });
            xhr.send(body);
        });
    }

    function makeComment(comment) {
        const commentClone = commentTemp.content.cloneNode(true);
        commentClone.querySelector(".commentUserName").textContent =
            comment.username;
        commentClone.querySelector(".commentBody").textContent =
            comment.comment;
        const img = document.createElement("img");
        readImage(img, comment.profile_pic);
        img.className = commentClone.querySelector("img").className;
        commentClone.querySelector("img").replaceWith(img);
        const likeBtn = commentClone.querySelector("[data-comment-like]");
        likeBtn.textContent = `Like ${comment.likes}`;
        likeBtn.addEventListener("click", (e) => {
            const data = new FormData();
            data.append("comment_id", comment.id);
            likeComment(likeBtn, data);
        });
        // console.log(comment.id);
        commentClone.id = "comment" + comment.id;

        return commentClone;
    }

    function readImage(img, url) {
        fetch("../images/" + url).then(async (res) => {
            res.blob().then((blob) => {
                const new_url = URL.createObjectURL(blob);
                img.setAttribute("src", new_url);
            });
        });
    }

    function likeComment(el, data) {
        fetch("../post/comment_like.php", { method: "POST", body: data }).then(
            async (res) => {
                const comment = await res.json();
                // console.log({el, comment})
                el.closest(".comment").replaceWith(makeComment(comment));
            }
        );
    }
});
