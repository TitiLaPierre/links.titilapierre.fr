const updatePasswordButton = document.querySelector("#updatePassword--button")

async function updatePassword() {
    // const username = document.getElementById("username").textContent
    const oldPassword = document.getElementById("oldPasswordInput").value
    const newPassword = document.getElementById("newPasswordInput").value
    const response = await api_call(url("/api/password/"), { method: "UPDATE",
        body: { username: username, oldPassword: oldPassword, newPassword: newPassword },
    })
    closePopup()
    if (response.success) {
        openSnackbar({"text": "Votre mot de passe a été mis à jour avec succès !", "icon": "fa-solid fa-check", "bgColor": "success"})
    } else if (response.code == "INVALID_CREDENTIALS") {
        openSnackbar({"text": "Erreur : votre mot de passe actuel est incorrect.", "icon": "fa-solid fa-circle-exclamation", "bgColor": "error"})
    } else {
        openSnackbar({"text": "Erreur : "+response.code, "icon": "fa-solid fa-circle-exclamation", "bgColor": "error"})
    }
}

updatePasswordButton.addEventListener("click", async function (e) {
    e.stopPropagation()
    openPopup({
        title: "Modifier votre mot de passe",
        paragraphs: [
            "Entrez vos informations de connexion ci-dessous."
        ],
        actions: [
            { text: "Annuler", icon: "", appearance: "secondary", execute: "closePopup()" },
            { text: "Modifier", icon: "fa-solid fa-edit", appearance: "main", execute: "updatePassword()" },
        ],
        type: ["password"]
    })
})