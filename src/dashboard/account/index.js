const updatePasswordButton = document.querySelector("#updatePassword--button")

async function isVerifiedUser(username, password) {
    const response = await api_call(url("api/login/"), { method: "POST",
        body: { username: username, password: password }
    })
    return response.success
}

async function updatePassword(uid) {
    const username = document.getElementById("username").textContent;
    const oldPassword = document.getElementById("oldPasswordInput").value;
    const isVerified = await isVerifiedUser(username, oldPassword);
    if (isVerified) {
        const response = await api_call(url(`/api/password/${uid}/`), { method: "UPDATE",
            body: { password: document.getElementById("newPasswordInput").value },
        })
        if (response.success) {
            closePopup()
            openSnackbar({"text": "Votre mot de passe a été mis à jour avec succès !", "icon": "fa-solid fa-check", "bgColor": "success"})
        } else {
            closePopup()
            openSnackbar({"text": "Erreur : "+response.code, "icon": "fa-solid fa-circle-exclamation", "bgColor": "error"})
        }
    } else {
        closePopup()
        openSnackbar({"text": "Erreur : votre mot de passe actuel est incorrect.", "icon": "fa-solid fa-circle-exclamation", "bgColor": "error"})
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
            { text: "Modifier", icon: "fa-solid fa-edit", appearance: "main", execute: `updatePassword()` },
        ],
        type: ["password"]
    })
})