const updatePasswordButton = document.querySelector("#updatePassword--button")

function validateInputs() {
    const oldPasswordInput = document.querySelector("#oldPasswordInput")
    const newPasswordInput = document.querySelector("#newPasswordInput")
    const confirmPasswordInput = document.querySelector("#confirmPasswordInput")
    const mainButton = document.querySelector("#mainButton")
    if (oldPasswordInput.value.length > 0 && newPasswordInput.value.length > 0 && confirmPasswordInput.value.length > 0) {
        mainButton.disabled = null
    } else {
        mainButton.disabled = "true"
    }
}

async function updatePassword() {
    const oldPasswordInput = document.querySelector("#oldPasswordInput")
    const newPasswordInput = document.querySelector("#newPasswordInput")
    const confirmPasswordInput = document.querySelector("#confirmPasswordInput")
    const passes = [
        { regex: /^.{8,}$/, error: "Le mot de passe doit contenir au moins 8 caractères." },
        { regex: /^.{0,100}$/, error: "Le mot de passe ne peut pas contenir plus de 100 caractères." },
        { regex: /[A-Z]/, error: "Le mot de passe doit contenir au moins une lettre majuscule." },
        { regex: /[a-z]/, error: "Le mot de passe doit contenir au moins une lettre minuscule." },
        { regex: /\d/, error: "Le mot de passe doit contenir au moins un chiffre." },
        { regex: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/, error: "Le mot de passe doit contenir au moins un caractère spécial." },
        { regex: /^[a-z\d!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+$/i, error: "Le mot de passe contient un caractère non autorisé." }
    ]
    for (let pass of passes) {
        if (!pass.regex.test(newPasswordInput.value)) {
            openSnackbar({ "text": "Erreur : " + pass.error, "icon": "fa-solid fa-circle-exclamation", "bgColor": "error" })
            return
        }
    }
    if (newPasswordInput.value != confirmPasswordInput.value) {
        openSnackbar({ "text": "Erreur : Le champ de confirmation ne contient pas le bon mot de passe.", "icon": "fa-solid fa-circle-exclamation", "bgColor": "error" })
        return
    }
    const response = await api_call(url("/api/password/"), { method: "UPDATE",
        body: { oldPassword: oldPasswordInput.value, newPassword: newPasswordInput.value },
    })
    if (response.success) {
        openSnackbar({"text": "Votre mot de passe a été mis à jour avec succès !", "icon": "fa-solid fa-check", "bgColor": "success"})
        closePopup()
    } else if (response.code == "INVALID_CREDENTIALS") {
        openSnackbar({"text": "Erreur : Votre mot de passe actuel est incorrect.", "icon": "fa-solid fa-circle-exclamation", "bgColor": "error"})
    } else {
        openSnackbar({"text": "Erreur : "+response.code, "icon": "fa-solid fa-circle-exclamation", "bgColor": "error"})
    }
}

updatePasswordButton.addEventListener("click", async function (e) {
    e.stopPropagation()
    openPopup({
        title: "Modifier votre mot de passe",
        content: [
            { type: "paragraph", title: "Ancien mot de passe", text: "Saissisez votre mot de passe actuel pour confirmer votre identité." },
            { type: "input", inputType: "password", placeholder: "Ancien mot de passe", id: "oldPasswordInput", input: "validateInputs()" },
            { type: "paragraph", title: "Nouveau mot de passe", text: "Saisissez un nouveau mot de passe pour sécuriser votre compte." },
            { type: "list", title: "Le nouveau mot de passe requiert", items: [
                "Entre 8 et 100 caractères",
                "Au moins une lettre minuscule, majuscule",
                "Au moins un chiffre",
                "Au moins un caractère spécial"
            ]},
            { type: "input", inputType: "password", placeholder: "Nouveau mot de passe", id: "newPasswordInput", input: "validateInputs()" },
            { type: "paragraph", title: "Confirmer le mot de passe", text: "Saisissez à nouveau le nouveau mot de passe pour éviter les erreurs de frappe." },
            { type: "input", inputType: "password", placeholder: "Confirmer le mot de passe", id: "confirmPasswordInput", input: "validateInputs()" }
        ],
        actions: [
            { text: "Annuler", icon: "", appearance: "secondary", execute: "closePopup()" },
            { text: "Modifier", icon: "fa-solid fa-edit", appearance: "main", execute: "updatePassword()", id: "mainButton", disabled: true },
        ]
    })
})