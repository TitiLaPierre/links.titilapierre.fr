const navbar = document.querySelector(".navbar")
const minimizeButton = document.querySelector("#minimize")
const page = document.querySelector(".page")

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

window.addEventListener("mousemove", (e) => updateMinimizeButton(e.pageX, e.pageY))
minimizeButton.addEventListener("click", () => changeNavbarState(navbar.classList.contains("minimized") ? "maximized" : "minimized"))