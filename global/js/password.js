function showHidePassword(button, targetId) {
    const passwordInput = document.getElementById(targetId)
    const iconElement = button.querySelector("i")
    const spanElement = button.querySelector("span")
    if (passwordInput.getAttribute("type") == "password") {
        passwordInput.setAttribute("type", "text")
        iconElement.className = "fa-solid fa-eye-slash"
        spanElement.textContent = "Cacher"
    } else {
        passwordInput.setAttribute("type", "password")
        iconElement.className = "fa-solid fa-eye"
        spanElement.textContent = "Afficher"
    }
}