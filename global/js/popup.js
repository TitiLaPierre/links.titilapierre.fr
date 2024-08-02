function handleClick(event) {
    const popup = document.getElementById("popup")
    if (!popup.contains(event.target)) closePopup()
}

function openPopup(data) {
    window.addEventListener("click", handleClick)
    let popup = document.querySelector("#popup")
    if (!popup) {
        popup = document.createElement("div")
        popup.id = "popup"
        document.body.appendChild(popup)
    }

    popup.innerHTML = `
        <div class="popup--header">
            <h2>${data.title}</h2>
            <button class="popup--close" onclick="closePopup()"><i class="fas fa-times"></i></button>
        </div>
        <div class="popup--content">
            ${data.paragraphs.map(paragraph => `<p class="popup--paragraph">${paragraph}</p>`).join("")}
        </div>
        <div class="popup--actions">
            ${data.actions.map(action => {
                return `<button class="popup--button button__${action.appearance}" onclick="${action.execute}">
                    <i class="${action.icon}"></i>
                    ${action.text}
                </button>`
            }).join("")}
        </div>
    `
    popup.style.display = "flex"
}

function closePopup() {
    window.removeEventListener("click", handleClick)
    const popup = document.getElementById("popup")
    popup.style.display = "none"
}