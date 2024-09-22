const link = JSON.parse(document.querySelector("[linkData]").getAttribute("linkData"))
let { year, month, day } = JSON.parse(document.querySelector("[viewData]").getAttribute("viewData"))

const contentGrid = document.querySelector(".grid--content")
const warningElement = document.querySelector("#grid--warning")

const previousButton = document.querySelector("#calendar--back")
const nextButton = document.querySelector("#calendar--next")
const nowButton = document.querySelector("#calendar--now")
const backButton = document.querySelector("#calendar--backToMonth")

let currentSidebar = null
const sidebar = document.querySelector(".sidebar")
const sidebarClose = sidebar.querySelector(".sidebar--close")
const sidebarTitle = sidebar.querySelector(".sidebar--title")
const sidebarDescription = sidebar.querySelector(".sidebar--description")
const sidebarTag = sidebar.querySelector(".sidebar--tag")
const sidebarActivities = sidebar.querySelector(".sidebar--activities")

let viewsPerIps = {}

async function updateViews(newYear, newMonth, newDay) {
    if (newYear <= 0 && newMonth < 1) return

    const newDate = new Date(newYear, newMonth-1, newDay)
    year = newDate.getFullYear()
    month = newDate.getMonth()+1
    day = newDate.getDate()
    
    nextButton.disabled = true
    previousButton.disabled = true

    if (year == new Date().getFullYear() && month == new Date().getMonth()+1 && day == new Date().getDate()) nowButton.style.display = "none"
    else nowButton.style.display = null
    
    const start = new Date(year, month-1, day, 0, 0, 0)
    const end = new Date(year, month-1, day, 23, 59, 59)
    const response = await api_call(url(`/api/stats/${link.uid}/`), { method: "POST", body: { start: Math.floor(start.getTime()/1000), end: Math.floor(end.getTime()/1000) } })

    if (response.success) {
        history.pushState({ year, month }, "", `/stats/${link.uid}/${year.toString().padStart(4, "0")}/${month.toString().padStart(2, "0")}/${day.toString().padStart(2, "0")}/`)
        viewsPerIps = {}
        response.data.forEach(({ creation_date, ip }) => {
            if (!viewsPerIps[ip]) viewsPerIps[ip] = []
            viewsPerIps[ip].push(creation_date)
        })

        Object.values(viewsPerIps).forEach(views => views.sort())

        if (Object.keys(viewsPerIps).length > 0) {
            warningElement.style.display = "none"
            contentGrid.style.display = null
            contentGrid.innerHTML = ""
            for (const [ip, views] of Object.entries(viewsPerIps)) {
                const rowElement = document.createElement("button")
                rowElement.setAttribute("ip", ip)
                rowElement.classList.add("grid--row")
                rowElement.innerHTML = `<span class="grid--title">
                            <i class="fa-solid fa-globe"></i>
                            ${ip}
                        </span>
                        <span class="grid--info">${views.length}</span>
                        <span class="grid--info">${new Date(views[views.length-1]*1000).toLocaleTimeString()}</span>
                    </button>`
        
                contentGrid.appendChild(rowElement)
            }
        } else {
            warningElement.style.display = null
            contentGrid.style.display = "none"
        }
        backButton.href = `/stats/${link.uid}/${year.toString().padStart(4, "0")}/${month.toString().padStart(2, "0")}/`
        backButton.querySelector("span").textContent = `${months[month-1]} ${year}`
        updateDateDisplay(year, month, day)
    }

    nextButton.disabled = null
    previousButton.disabled = null
}

function showSidebar(ip) {
    if (currentSidebar == ip) return
    if (currentSidebar) closeSidebar()
    currentSidebar = ip
    const element = document.querySelector(`[ip="${ip}"]`)
    element.setAttribute("active", true)
    sidebar.style.display = null
    sidebarTitle.textContent = ip
    sidebarDescription.textContent = "Position inconnue"
    sidebarTag.href = `https://www.ip-tracker.org/lookup.php?ip=${ip}`
    sidebarActivities.innerHTML = ""
    for (const view of viewsPerIps[ip]) {
        const activityElement = document.createElement("div")
        activityElement.classList.add("sidebar--activity")
        activityElement.innerHTML = `<i class="sidebar--activity--icon fa-solid fa-check"></i>
            <p class="sidebar--activity--description">
                L'utilisateur a utilisé le lien.
            </p>
            <span class="sidebar--activity--date">${new Date(view*1000).toLocaleTimeString()}</span>`
        sidebarActivities.appendChild(activityElement)
    }
}

function closeSidebar() {
    const element = document.querySelector(`[ip="${currentSidebar}"]`)
    element.removeAttribute("active")
    currentSidebar = null
    sidebar.style.display = "none"
}

sidebarClose.addEventListener("click", closeSidebar)

window.addEventListener("click", function(e) {
    const target = e.target.closest("[ip]")
    if (target) showSidebar(target.getAttribute("ip"))
})


nextButton.addEventListener("click", () => updateViews(year, month, day+1))
previousButton.addEventListener("click", () => updateViews(year, month, day-1))
nowButton.addEventListener("click", () => updateViews(new Date().getFullYear(), new Date().getMonth()+1, new Date().getDate()))

for (const mode of document.querySelectorAll("[name=calendar--mode]")) {
    mode.addEventListener("change", () => updateModes(mode.value))
}

setHandlers({
    year: (newYear) => updateViews(newYear, month, day),
    month: (newMonth) => updateViews(year, newMonth, day),
    day: (newDay) => updateViews(year, month, newDay),
    reset: () => updateViews(year, month, day)
})

window.addEventListener("pageshow", () => updateViews(year, month, day))