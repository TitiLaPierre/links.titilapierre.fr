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
            ${data.paragraphs.map(paragraph => `<p class="popup--paragraph">${paragraph}</p>`).join("")}
            ${data.type.map(type => {
                if (type == "password") {
                    return `<div class="popup--inputContainer">
                        <input id="oldPasswordInput" class="popup--input" placeholder="Ancien mot de passe" type="password">
                        <button id="oldPasswordButton" class="popup--inputButton" onclick="showHidePassword('old')"><i class="fa-solid fa-eye"></i></button>
                    </div>
                    <div class="popup--inputContainer">
                        <input id="newPasswordInput" class="popup--input" placeholder="Nouveau mot de passe" type="password">
                        <button id="newPasswordButton" class="popup--inputButton" onclick="showHidePassword('new')"><i class="fa-solid fa-eye"></i></button>
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
                return `<button class="popup--button button__${action.appearance}" onclick="${action.execute}">
                    ${buttonIcon}
                    ${action.text}
                </button>`
            }).join("")}
        </div>
    `
    const mainButton = document.querySelector(".popup--button.button__main")
    const oldPasswordInput = document.querySelector("#oldPasswordInput")
    const newPasswordInput = document.querySelector("#newPasswordInput")
    if (data.type[0] == "password") {
        mainButton.setAttribute("disabled", "")
        oldPasswordInput.addEventListener("input", () => {
            if (oldPasswordInput.value.length > 0 && newPasswordInput.value.length > 0) {
                mainButton.removeAttribute("disabled")
            } else {
                mainButton.setAttribute("disabled", "")
            }
        })
        newPasswordInput.addEventListener("input", () => {
            if (oldPasswordInput.value.length > 0 && newPasswordInput.value.length > 0) {
                mainButton.removeAttribute("disabled")
            } else {
                mainButton.setAttribute("disabled", "")
            }
        })
    }
    oldPasswordInput.addEventListener("keypress", function(e) {
        if (e.key == "Enter") mainButton.click()
    })
    newPasswordInput.addEventListener("keypress", function(e) {
        if (e.key == "Enter") mainButton.click()
    })
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