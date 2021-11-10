if (localStorage.getItem("theme") === "light") {
    document.querySelectorAll("body").forEach((body) => {
        body.classList.add("light");
    });
} else {
    document.querySelectorAll("body").forEach((body) => {
        body.classList.add("dark");
    });
}

const themeContainer = document.querySelector(".themeContainer");
if (themeContainer) {
    const themeCheck = themeContainer.querySelector("input[type='checkbox']");

    themeCheck.addEventListener("change", (e) => {
        document.querySelectorAll("body").forEach((body) => {
            if (body.classList.contains("light")) {
                localStorage.setItem("theme", "dark");
            } else {
                localStorage.setItem("theme", "light");
            }
            body.classList.toggle("light");
        });
    });
}
