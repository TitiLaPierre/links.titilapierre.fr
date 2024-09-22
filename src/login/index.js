const inputUsername = document.querySelector("#username")
const inputPassword = document.querySelector("#password")
const buttonLogin = document.querySelector("#login")
const spanInformation = document.querySelector("#information")

let waitingResponse = false

const params = new URLSearchParams(window.location.search)
if (params.has("required")) {
    sendInformation(true, "error", "Vous devez être connecté pour accéder à cette page.")
    history.replaceState({}, document.title, window.location.pathname)
} else if (params.has("disconnected")) {
    sendInformation(true, "success", "Vous avez été déconnecté de votre compte.")
    history.replaceState({}, document.title, window.location.pathname)
}

function sendInformation(displayed, style, text) {
    if (!displayed) {
        spanInformation.style.opacity = "0"
        return
    } else spanInformation.style.opacity = "1"
    spanInformation.className = spanInformation.className.split(" ").filter(c => !c.startsWith("information__")).join(" ")
    spanInformation.classList.add(`information__${style}`)
    spanInformation.textContent = text
    spanInformation.animate([{ translate: "0" }, { translate: "20px" }, { translate: "0" }], 250)
}

function validateInputs() {
    let enabled = true
    if (waitingResponse) enabled = false
    if (!/^.+$/.test(inputUsername.value)) enabled = false
    if (!/^.+$/.test(inputPassword.value)) enabled = false
    buttonLogin.disabled = enabled ? null : true
}

function showHidePassword() {
    let pwdInput = document.querySelector("#login--passwordInputContainer>input")
    let showHideBtn = document.querySelector("#login--passwordInputContainer>button>i")
    if (pwdInput.getAttribute("type") == "password") {
        pwdInput.setAttribute("type", "text")
        showHideBtn.className = "fa-solid fa-eye-slash"
    } else {
        pwdInput.setAttribute("type", "password")
        showHideBtn.className = "fa-solid fa-eye"
    }
}

inputUsername.addEventListener("input", validateInputs)
inputPassword.addEventListener("input", validateInputs)

inputUsername.addEventListener("keypress", function(e) {
    if (e.key == "Enter") inputPassword.focus()
})
inputPassword.addEventListener("keypress", function(e) {
    if (e.key == "Enter") buttonLogin.click()
})

buttonLogin.addEventListener("click", async function() {
    waitingResponse = true
    validateInputs()
    sendInformation(false)
    const response = await api_call(url("api/login/"), { method: "POST",
        body: { username: inputUsername.value, password: inputPassword.value }
    })
    if (response.success == false) {
        sendInformation(true, "error", "Identifiant ou mot de passe invalide.")
    } else {
        document.cookie = `token=${response.token}; max-age=${60*60*24*7}; path=/; samesite=strict`
        location.reload()
    }
    waitingResponse = false
    validateInputs()
})

validateInputs()