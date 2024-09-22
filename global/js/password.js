function showHidePassword(button, targetId) {
    const passwordInput = document.getElementById(targetId)
    const iconElement = button.querySelector("i")
    if (passwordInput.getAttribute("type") == "password") {
        passwordInput.setAttribute("type", "text")
        iconElement.className = "fa-solid fa-eye-slash"
    } else {
        passwordInput.setAttribute("type", "password")
        iconElement.className = "fa-solid fa-eye"
    }
}