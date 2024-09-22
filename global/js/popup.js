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
        scrim = document.createElement("div")
        scrim.id = "scrim"
        document.body.appendChild(scrim)
    }

    popup.innerHTML = `
        <div class="popup--header">
            <h2>${data.title}</h2>
            <button class="popup--close" onclick="closePopup()"><i class="fa-solid fa-times"></i></button>
        </div>
        <div class="popup--content">
            ${data.content.map(element => {
                if (element.type == "paragraph") {
                    return `<div class="popup--section">
                        ${element.title ? `<h3 class="popup--title">${element.title}</h3>` : ""}
                        <p class="popup--paragraph">${element.text}</p>
                    </div>`
                } else if (element.type == "list") {
                    return `<div class="popup--section">
                        ${element.title ? `<p class="popup--item">${element.title}</p>` : ""}
                        <ul class="popup--list">
                            ${element.items.map(item => `<li class="popup--item">${item}</li>`).join("")}
                        </ul>
                    </div>`
                } else if (element.type == "input") {
                    return `<div class="popup--inputContainer">
                        <input
                            id="${element.id}"
                            class="popup--input"
                            placeholder="${element.placeholder}"
                            type="${element.inputType}"
                            ${element.input ? `oninput="${element.input}"` : ""}
                        >
                        ${element.inputType == "password" ? `<button class="popup--inputButton" onclick="showHidePassword(this, '${element.id}')"><i class="fa-solid fa-eye"></i></button>` : ""}
                    </div>`
                }
            }).join("")}
        </div>
        <div class="popup--actions">
            ${data.actions.map(action => {
                let buttonIcon = ""
                if (action.icon != "") {
                    buttonIcon = `<i class="${action.icon}"></i>`
                }
                return `<button class="popup--button button__${action.appearance}" onclick="${action.execute}" ${action.disabled ? "disabled" : ""} ${action.id ? `id="${action.id}"` : ""}>
                    ${buttonIcon}
                    ${action.text}
                </button>`
            }).join("")}
        </div>
    `
    popup.style.display = "flex"
    scrim.style.display = "block"
}

function closePopup() {
    window.removeEventListener("click", handleClick)
    const popup = document.getElementById("popup")
    popup.style.display = "none"
    const scrim = document.getElementById("scrim")
    scrim.style.display = "none"
}

function openSnackbar(data) {
    let snackbar = document.createElement("div")
    snackbar.className = "snackbar"
    snackbar.style.backgroundColor = `var(--${data.bgColor})`
    document.body.appendChild(snackbar)
    let snackbarIcon = ""
    if (data.icon != "") {
        snackbarIcon = `<i style="color: var(--on${data.bgColor.charAt(0).toUpperCase() + data.bgColor.slice(1)})" class="${data.icon}"></i>`
    }
    snackbar.innerHTML = `
    ${snackbarIcon}
    <span style="color: var(--on${data.bgColor.charAt(0).toUpperCase() + data.bgColor.slice(1)})">${data.text}</span>
    `
    snackbar.style.transform = "translate(0px, 0px)"
    setTimeout(() => {
        snackbar.style.transform = "translate(0px, 200px)"
        setTimeout(() => {
            snackbar.remove()
        }, "2000");
    }, "2000");
}