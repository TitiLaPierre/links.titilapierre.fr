const navbar = document.querySelector(".navbar")
const minimizeButton = document.querySelector("#minimize")
const page = document.querySelector(".page")

const html = document.querySelector("html")

const mobileMenuButton = document.querySelector("#mobileNavbar--button")
const mobileMenu = document.querySelector("#mobileNavbar--menu")

let isMobileMenuOpen = false

function updateMinimizeButton(x, y) {
    const xPos = navbar.clientWidth
    const yPos = y-minimizeButton.clientHeight/2

    const minYPos = window.scrollY
    const maxYPos = window.scrollY+window.innerHeight-minimizeButton.clientHeight

    minimizeButton.style.left = xPos+"px"
    minimizeButton.style.top = Math.min(maxYPos, Math.max(minYPos, yPos))+"px"

    if (Math.abs(navbar.clientWidth-x) < 35)
        minimizeButton.style.display = null
    else
        minimizeButton.style.display = "none"
}

function changeNavbarState(state) {
    if (state == "minimized") {
        navbar.classList.add("minimized")
        minimizeButton.classList.add("minimized")
    } else {
        navbar.classList.remove("minimized")
        minimizeButton.classList.remove("minimized")
    }
    updateMinimizeButton(0, 0)
    document.cookie = "navbar_state="+state+"; path=/"
}

function toggleMobileMenu() {
    isMobileMenuOpen = !isMobileMenuOpen
    mobileMenu.style.display = isMobileMenuOpen ? "flex" : "none"
}

window.addEventListener("mousemove", (e) => updateMinimizeButton(e.pageX, e.pageY))
window.addEventListener("resize", () => updateMinimizeButton(0, 0))
minimizeButton.addEventListener("click", () => changeNavbarState(navbar.classList.contains("minimized") ? "maximized" : "minimized"))

mobileMenuButton.addEventListener("click", function(e) {
    e.stopPropagation()
    toggleMobileMenu()
})
window.addEventListener("click", function(e) {
    if (isMobileMenuOpen && e.target != mobileMenuButton && e.target != mobileMenu)
        toggleMobileMenu()
})