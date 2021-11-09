let shouldSearch = false;
let timeout = null;
let foundUsers = [];

const searchResultContainer = document.querySelector(
    "[data-nav-search-result-container]"
);
const searchResultList = document.querySelector(
    "[data-nav-search-result-list]"
);
const searchInput = document.querySelector("[data-nav-search-input]");
const searchResultTemp = document.querySelector(
    "[data-nav-search-result-template]"
);

searchInput.addEventListener("input", (e) => {
    searchInput.classList.add("focused");
    if (searchInput.value.trim() === "") {
        clearTimeout(timeout);
        resetResults("Search a user");
        return;
    }
    if (searchInput.value.length > 1) {
        timeout = setTimeout(() => {
            getSearchResults(searchInput.value);
        }, 300);
    } else {
        getSearchResults(searchInput.value);
    }
});

searchInput.addEventListener("blur", (e) => {
    if (searchInput.value === ""){
        resetResults();
        searchInput.classList.remove("focused")
    }
});

function getSearchResults(search) {
    const url = `../search/search.php?search=${search}`;
    fetch(url).then(async (response) => {
        response
            .json()
            .then((users) => {
                return Promise.resolve(
                    users.map((user) => {
                        const resultEl = searchResultTemp.content
                            .cloneNode(true)
                            .querySelector("[data-nav-search-result]");
                        const nameEl = resultEl.querySelector(
                            "[data-nav-search-result-username]"
                        );
                        resultEl.href += "?user=" + user.username;
                        nameEl.textContent = `${user.username} - ${user.fname} ${user.lname}`;
                        return resultEl;
                    })
                );
            })
            .then((elements) => {
                if (elements.length === 0) {
                    resetResults("No results found");
                } else {
                    resetResults("");
                    searchResultList.append(...elements);
                }
            });
    });
}

function resetResults(text = "No search results") {
    searchResultList.innerHTML = ""; //text && "<p>" + text + "</p>";
}
