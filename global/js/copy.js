function copyToClipboard(text) {
	const input = document.createElement("input")
	input.value = text
	document.body.appendChild(input)
	input.select()
	document.execCommand("copy")
	document.body.removeChild(input)
}

const copyAnimations = {}
for (const element of document.querySelectorAll("[copy]")) {
	element.addEventListener("click", function() {
		copyToClipboard(element.getAttribute("copy"))

		if (!element.classList.contains("copied"))
			element.classList.add("copied")

		for (const a of element.querySelectorAll("[copied]")) {
			let classes
			if (copyAnimations[a.id]) {
				classes = copyAnimations[a.id][0]
				clearTimeout(copyAnimations[a.id][1])
			} else { classes = a.getAttribute("class") }

			a.setAttribute("class", a.getAttribute("copied"))
			copyAnimations[a.id] = [classes, setTimeout(function() {
				a.setAttribute("class", copyAnimations[a.id][0])
                delete copyAnimations[a.id]
				element.classList.remove("copied")
			}, 1000)]
		}
	})
}

for (const element of document.querySelectorAll("[copied]")) {
    element.id = Math.random().toString(36).substring(7)
}