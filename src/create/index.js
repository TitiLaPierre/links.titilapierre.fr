const stepNameElement = document.querySelector(".page--step--name")
const stepDotElements = Array.from(document.querySelectorAll(".page--step"))

const backButton = document.querySelector(".form--back")
const nextButton = document.querySelector(".form--next")

const nameField = document.querySelector("#form--name")
const nameInput = document.querySelector("[field=name]")

const targetField = document.querySelector("#form--target")
const targetInput = document.querySelector("[field=target]")

const sourceField = document.querySelector("#form--source")
const sourceInput = document.querySelector("[field=source]")

const stepElements = Array.from(document.querySelectorAll(".form--step"))
let currentStep = parseInt(document.querySelector("#form--step").value)

let waitingResponse = false

function showStep(step) {
    stepElements[currentStep].style.display = "none"
    stepElements[step].style.display = null

    currentStep = step
    document.querySelector("#form--step").value = step

    stepNameElement.innerText = stepElements[step].getAttribute("title")
    for (let i = 0; i < stepDotElements.length; i++) {
        const state = i == step ? "current" : i < step ? "completed" : "locked"
        stepDotElements[i].setAttribute("state", state)
    }

    if (step == 0) backButton.style.display = "none"
    else backButton.style.display = null
    
    nextButton.style.display = null
    if (step < stepElements.length-1) {
        nextButton.classList.remove("action__main")
        nextButton.querySelector("span").innerText = "Suivant"
        nextButton.querySelector("i").className = "fas fa-arrow-right"
    } else {
        nextButton.classList.add("action__main")
        nextButton.querySelector("span").innerText = "Publier"
        nextButton.querySelector("i").className = "fas fa-check"
    }

    validate(step)
}

function validate(step) {
    let validation = true
    if (step == 0) {
        if (nameInput.value.trim() == "") {
            displayError(nameField, null)
            validation = false
        } else if (!/^[\p{L}\p{N} ]+$/u.test(nameInput.value.trim())) {
            displayError(nameField, "Utilisez uniquement des lettres, chiffres et tirets.")
            validation = false
        } else {
            displayError(nameField, null)
        }
    } else if (step == 1) {
        if (targetInput.value.trim() == "") {
            displayError(targetField, null)
            validation = false
        } else if (!/^(?:(http(?:s)?):\/\/.)?[-a-z0-9@%._\+~#=]{1,256}\.[a-z]{2,6}\b(?:[-a-z0-9@:%_\+.~#?&\/\/=]*)$/i.test(targetInput.value.trim()) && targetInput.value.trim().length < 2083) {
            displayError(targetField, "L'entée n'est pas une URL.")
            validation = false
        } else {
            displayError(targetField, null)
        }
    } else if (step == 2) {
        if (sourceInput.value.trim() == "") {
            displayError(sourceField, null)
        } else if (!/^[a-zA-Z0-9\.\-_]+$/.test(sourceInput.value.trim())) {
            displayError(sourceField, "Utilisez uniquement des lettres, chiffres, tirets et points.")
            validation = false
        } else if (sourceInput.value.trim().length < 3 || sourceInput.value.trim().length > 64) {
            displayError(sourceField, "Entre 3 et 64 caractères.")
            validation = false
        } else {
            displayError(sourceField, null)
        }
    }
    if (waitingResponse) validation = false
    nextButton.disabled = !validation
}

function displayError(fieldElement, message) {
    const errorElement = fieldElement.querySelector(".form--field--error")
    if (message) {
        fieldElement.classList.add("field__error")
        errorElement.innerText = message
        errorElement.style.display = null
    } else {
        fieldElement.classList.remove("field__error")
        errorElement.innerText = ""
        errorElement.style.display = "none"
    }
}

backButton.addEventListener("click", function() {
    if (currentStep > 0) {
        showStep(currentStep - 1)
    }
})

nextButton.addEventListener("click", async function() {
    if (currentStep < stepElements.length - 1) {
        showStep(currentStep + 1)
    } else {
        waitingResponse = true
        validate(currentStep)
        const response = await api_call(url("/api/link/"), { method: "POST",
            body: { name: nameInput.value.trim(), redirect_url: targetInput.value.trim(), source_url: sourceInput.value.trim() },
        })
        console.log(response)
        waitingResponse = false
        if (!response.success) {
            if (response.code == "LINK_ALREADY_EXISTS") {
                showStep(2)
                displayError(sourceField, "L'URL spécifiée existe déjà.")
            }
        } else {
            window.location.href = url(`link/${response.data.uid}/`)
        }
    }
})

nameInput.addEventListener("input", () => validate(currentStep))
targetInput.addEventListener("input", () => validate(currentStep))
sourceInput.addEventListener("input", () => validate(currentStep))

nameInput.addEventListener("keydown", function(e) {
    if (e.key == "Enter") {
        nextButton.click()
        targetInput.focus()
    }
})
targetInput.addEventListener("keydown", function(e) {
    if (e.key == "Enter") {
        nextButton.click()
        sourceInput.focus()
    }
})
sourceInput.addEventListener("keydown", function(e) {
    if (e.key == "Enter") {
        nextButton.click()
    }
})

for (const element of document.querySelectorAll("[syncInput]")) {
    const target = document.getElementById(element.getAttribute("syncInput"))
    target.addEventListener("input", function() {
        const output = element.getAttribute("syncPattern").replace("%input%", target.value)
        element.innerText = output
    })
}

window.addEventListener("pageshow", () => showStep(currentStep))

for (const element of document.querySelectorAll(".page--step")) {
    element.addEventListener("click", () => element.getAttribute("state") === "completed" && showStep(parseInt(element.getAttribute("step"))))
}