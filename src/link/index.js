const link = JSON.parse(document.querySelector("[linkData]").getAttribute("linkData"))

const deleteButton = document.querySelector("#delete")
const updateIcon = document.querySelector(".page--icon--wrapper")
const iconDefaultElement = updateIcon.querySelector("span.page--icon")
const iconImageElement = updateIcon.querySelector("img.page--icon")

async function deleteLink(uid) {
    const response = await api_call(url(`/api/link/${uid}/`), { method: "DELETE" })
    if (response.success) {
        window.location.href = url(`dashboard/links/`)
    }
}

deleteButton.addEventListener("click", async function(e) {
    e.stopPropagation()
    openPopup({
        title: "Supprimer le lien",
        content: [
            { type: "paragraph", text: `En continuant, vous allez supprimer le lien "${link.name}" comprenant les statistiques et la liaison de redirection entre les deux sites.` },
            { type: "paragraph", text: "Cette opération est irréversible." }
        ],
        actions: [
            { text: "Annuler", icon: "", appearance: "secondary", execute: "closePopup()" },
            { text: "Supprimer", icon: "fa-solid fa-trash", appearance: "danger", execute: `deleteLink('${link.uid}')` },
        ]
    })
})

updateIcon.addEventListener("click", async function() {
    updateIcon.disabled = "true"
    const promises = []
    promises.push(new Promise(resolve => setTimeout(resolve, 500)))
    promises.push(api_call(url(`/api/retreive_icon/${link.uid}/`), { method: "POST" }))
    const [_, response] = await Promise.all(promises)
    if (response.success) {
        iconDefaultElement.style.display = "none"
        iconImageElement.style.display = null
        iconImageElement.src = `media/${response.data.icon_uid}`
    } else {
        iconDefaultElement.style.display = null
        iconImageElement.style.display
        iconImageElement.src = null
    }
    updateIcon.disabled = null
})
