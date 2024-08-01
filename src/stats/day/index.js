const link = JSON.parse(document.querySelector("[linkData]").getAttribute("linkData"))
let { year, month, day } = JSON.parse(document.querySelector("[viewData]").getAttribute("viewData"))

const contentGrid = document.querySelector(".grid--content")
const footerGrid = document.querySelector(".grid--footer")

async function updateViews(year, month, day) {
    const start = new Date(year, month-1, day, 0, 0, 0)
    const end = new Date(year, month-1, day, 23, 59, 59)
    const response = await api_call(url(`/api/stats/${link.uid}/`), { method: "POST", body: { start: Math.floor(start.getTime()/1000), end: Math.floor(end.getTime()/1000) } })

    if (response.success) {
        const viewsPerIps = {}
        response.data.forEach(({ creation_date, ip }) => {
            if (!viewsPerIps[ip]) viewsPerIps[ip] = []
            viewsPerIps[ip].push(creation_date)
        })

        Object.values(viewsPerIps).forEach(views => views.sort())

        if (Object.keys(viewsPerIps).length > 0) {
            footerGrid.style.display = "none"
            contentGrid.style.display = null
            contentGrid.innerHTML = ""
            for (const [ip, views] of Object.entries(viewsPerIps)) {
                const rowElement = document.createElement("button")
                rowElement.classList.add("grid--row")
                rowElement.innerHTML = `<span class="grid--title">
                            <i class="fa-solid fa-globe"></i>
                            ${ip}
                        </span>
                        <span class="grid--info">${views.length}</span>
                        <span class="grid--info">${new Date(views[views.length-1]*1000).toLocaleTimeString()}</span>
                        <span class="grid--action"><i class="fa-solid fa-chevron-right"></i></span>
                    </button>`
    
                contentGrid.appendChild(rowElement)
            }
        } else {
            footerGrid.style.display = null
            contentGrid.style.display = "none"
        }
    }
}

window.addEventListener("pageshow", () => updateViews(year, month, day))