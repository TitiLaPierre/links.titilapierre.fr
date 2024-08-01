const link = JSON.parse(document.querySelector("[linkData]").getAttribute("linkData"))
let { year, month } = JSON.parse(document.querySelector("[viewData]").getAttribute("viewData"))
const days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"]
const months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
let currentOption

const calendarElement = document.querySelector(".calendar")
const calendarContentElement = document.querySelector(".calendar--content")
const calendarDateElement = document.querySelector(".calendar--date")
const calendarDateIcon = document.querySelector("#calendar--date--icon")

const previousButton = document.querySelector(".calendar--back")
const warpButton = document.querySelector(".calendar--warp")
const nextButton = document.querySelector(".calendar--next")

class Case {
    constructor() {
        this.element = document.createElement("a")
        this.element.setAttribute("class", "calendar--case")
    }
    build() {
        return this.element
    }

}
class DisabledCase extends Case {
    constructor() {
        super()
        this.element.classList.add("case__disabled")
    }
}
class DayCase extends Case {
    constructor(year, month, day, views, ips, maximumViews, maximumIps) {
        super()

        let dayOfWeek = new Date(year, month-1, day).getDay()-1
        if (dayOfWeek < 0) dayOfWeek = 6

        const dayCountElement = document.createElement("span")
        dayCountElement.setAttribute("class", "calendar--day")
        dayCountElement.innerText = `${days[dayOfWeek].slice(0, 3)}. ${day.toString().padStart(2, "0")}`

        const isToday = new Date().getDate() == day && new Date().getMonth() == month-1 && new Date().getFullYear() == year
        if (isToday) {
            const todayElement = document.createElement("i")
            todayElement.setAttribute("class", "fa-regular fa-clock")
            dayCountElement.appendChild(todayElement)
        }

        this.element.appendChild(dayCountElement)

        const isFuture = new Date().getTime() < new Date(year, month-1, day).getTime()
        if (isFuture)
            this.element.classList.add("case__disabled")

        const isBeforeCreation = new Date(link.creation_date*1000).getTime() > new Date(year, month-1, day+1).getTime()
        if (isBeforeCreation)
            this.element.classList.add("case__disabled")

        if (!isFuture && !isBeforeCreation)
            this.element.href = `/stats/${link.uid}/${year.toString().padStart(4, "0")}/${month.toString().padStart(2, "0")}/${day.toString().padStart(2, "0")}/`

        if (views) {
            const scale = views*100/maximumViews
            const opacity = 0.25 + 0.75*scale/100;
            
            const valueElement = document.createElement("span")
            valueElement.classList.add("calendar--data")
            valueElement.setAttribute("filter", "total")

            valueElement.style.setProperty("--color", "var(--main)")
            valueElement.style.setProperty("--opacity", opacity)
            valueElement.style.display = "none"

            valueElement.innerHTML = `<h2 class="calendar--data--value">${views}</h2><span class="calendar--data--label">vue${views>1 ? "s" : ""}</span>`
            this.element.appendChild(valueElement)
        }

        if (ips) {
            const scale = ips.length*100/maximumIps
            const opacity = 0.25 + 0.75*scale/100;

            const ipsElement = document.createElement("span")
            ipsElement.classList.add("calendar--data")
            ipsElement.setAttribute("filter", "unique")
            
            ipsElement.style.setProperty("--color", "var(--mainDark)")
            ipsElement.style.setProperty("--opacity", opacity)
            ipsElement.style.display = "none"

            ipsElement.innerHTML = `<h2 class="calendar--data--value">${ips.length}</h2><span class="calendar--data--label">unique${ips.length>1 ? "s" : ""}</span>`
            this.element.appendChild(ipsElement)
        }
    }
}

function updateOptions(newOption) {
    if (currentOption) {
        for (const element of document.querySelectorAll(`[filter=${currentOption}]`))
            element.style.display = "none"
    }
    currentOption = newOption
    for (const element of document.querySelectorAll(`[filter=${currentOption}]`))
        element.style.display = null
}

async function updateCalendar(newYear, newMonth) {
    currentOption = document.querySelector("[name=calendar--option]:checked").value

    if (newMonth < 1) newYear--, newMonth += 12
    while (newMonth > 12) newYear++, newMonth -= 12

    year = newYear
    month = newMonth

    calendarDateIcon.setAttribute("class", "fas fa-spinner fa-spin")
    nextButton.disabled = true
    previousButton.disabled = true

    const start = new Date(newYear, newMonth-1, 1)
    const end = new Date(newYear, newMonth, 1, 0, 0, -1)
    const response = await api_call(url(`/api/stats/${link.uid}/`), { method: "POST", body: { start: Math.floor(start.getTime()/1000), end: Math.floor(end.getTime()/1000) } })

    if (response.success) {
        const viewsPerDay = {}
        const ipsPerDay = {}
        let maximumViews = 0
        let maximumIps = 0
        response.data.forEach(({ creation_date, ip }) => {
            const day = new Date(creation_date*1000).getDate()

            if (!viewsPerDay[day]) viewsPerDay[day] = 0
            viewsPerDay[day]++

            if (!ipsPerDay[day]) ipsPerDay[day] = []
            if (!ipsPerDay[day].includes(ip)) ipsPerDay[day].push(ip)

            if (viewsPerDay[day] > maximumViews) maximumViews = viewsPerDay[day]
            if (ipsPerDay[day].length > maximumIps) maximumIps = ipsPerDay[day].length
        })

        history.pushState({ newYear, newMonth }, "", `/stats/${link.uid}/${newYear.toString().padStart(4, "0")}/${newMonth.toString().padStart(2, "0")}/`)
        const daysCount = new Date(newYear, newMonth, 0).getDate()
        const blankDaysCount = new Date(newYear, newMonth-1, 0).getDay()
        calendarContentElement.innerHTML = ""
        for (let i = 0; i < blankDaysCount; i++)
            calendarContentElement.appendChild(new DisabledCase().build())
        for (let i = 1; i <= daysCount; i++) {
            calendarContentElement.appendChild(new DayCase(newYear, newMonth, i, viewsPerDay[i], ipsPerDay[i], maximumViews, maximumIps).build())
        }
        while (calendarContentElement.childElementCount % 7 != 0)
            calendarContentElement.appendChild(new DisabledCase().build())
        calendarDateElement.style.display = null
        calendarDateElement.innerHTML = `${months[newMonth-1]} ${newYear.toString().padStart(4, "0")}`
    }

    calendarDateIcon.setAttribute("class", "fas fa-calendar-days")
    nextButton.disabled = null
    previousButton.disabled = null

    updateOptions(currentOption)
}

nextButton.addEventListener("click", () => updateCalendar(year, month+1))
previousButton.addEventListener("click", () => updateCalendar(year, month-1))

warpButton.addEventListener("click", (e) => {
    e.stopPropagation()
    openPopup({
        title: "Se déplacer dans le temps",
        paragraphs: [ "Indiquez une année et un mois pour accéder aux statistiques de ce mois." ],
        actions: [
            {
                text: "Annuler",
                appearance: "secondary",
                icon: "fas fa-times",
                execute: "closePopup()"
            },
            {
                text: "Aujourd'hui",
                appearance: "main",
                icon: "fas fa-calendar-day",
                execute: "updateCalendar(new Date().getFullYear(), new Date().getMonth()+1); closePopup()"
            }
        ]
    })
})

for (const option of document.querySelectorAll("[name=calendar--option]")) {
    option.addEventListener("change", () => updateOptions(option.value))
}

window.addEventListener("pageshow", () => updateCalendar(year, month))