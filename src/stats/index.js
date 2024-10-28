const dateElement = document.querySelector(".statsHeader--date")

const dayElement = document.querySelector("#calendar--day")
const monthElement = document.querySelector("#calendar--month")
const yearElement = document.querySelector("#calendar--year")

let handlers = {}

const months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]

function updateDateDisplay(newYear, newMonth, newDay) {
    monthElement.value = months[newMonth-1]
    yearElement.value = newYear.toString().padStart(4, "0")
    resizeElement(monthElement)
    resizeElement(yearElement)
    if (newDay && dayElement) {
        dayElement.value = newDay.toString().padStart(2, "0")
        resizeElement(dayElement)
    }
}

function setHandlers(newHandlers) { handlers = newHandlers }

monthElement.addEventListener("input", function() {
    this.value = this.value.replace(/[^\p{L}\p{N}]/gu, "").slice(0, 10)
    resizeElement(this)
})
monthElement.addEventListener("blur", function() {
    if (this.value == "") handlers.reset()
    else if (/^\d{1,2}$/.test(this.value) && parseInt(this.value) > 0 && parseInt(this.value) < 13) handlers.month(parseInt(this.value))
    else {
        const newMonth = months.findIndex(month => month.toLowerCase().deaccentuate().startsWith(this.value.toLowerCase().deaccentuate()))
        if (newMonth === -1) handlers.reset()
        else handlers.month(newMonth+1)
    }
})

yearElement.addEventListener("input", function() {
    this.value = this.value.replace(/\D/g, "").slice(0, 4)
    resizeElement(this)
})
yearElement.addEventListener("blur", function() {
    if (this.value == "") handlers.reset()
    else handlers.year(parseInt(this.value))
})

if (dayElement) {
    dayElement.addEventListener("input", function() {
        this.value = this.value.replace(/\D/g, "").slice(0, 2)
        resizeElement(this)
    })
    dayElement.addEventListener("blur", function() {
        if (this.value == "") handlers.reset()
        else handlers.day(parseInt(this.value))
    })
}

window.addEventListener("keydown", function(e) {
    if (e.key == "Enter") {
        if (dayElement && dayElement === document.activeElement) monthElement.focus()
        else if (monthElement === document.activeElement) yearElement.focus()
        else if (yearElement === document.activeElement) yearElement.blur()
    }
})

window.addEventListener("resize", () => {
    resizeElement(monthElement)
    resizeElement(yearElement)
    if (dayElement) resizeElement(dayElement)
})

window.addEventListener("pageshow", () => {
    let dateElementWidth = 0
    dateElement.querySelectorAll("input").forEach(inputElement => dateElementWidth += maximumElementSize(inputElement))
    dateElement.style.width = `${dateElementWidth}px`
})