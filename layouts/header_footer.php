<?php function layout_header($title, $css) { ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<base href="<?= VERSION["base_url"] ?>">
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <script type="text/javascript">
        const url = (a) => new URL(a, "<?= VERSION["base_url"] ?>").toString()
        async function api_call(url, { method="GET", headers, body }) {
            headers = { "Content-Type": "application/json", ...(headers ?? {}) }
            const response = await fetch(url, {
                body: body ? JSON.stringify(body) : null,
                method, headers
            })
            const text = await response.text()
            try { return JSON.parse(text) } catch (e) { return text }
        }
        String.prototype.zeros = function(n) {
            return this.length >= n ? this : "0".repeat(n-this.length)+this
        }
        String.prototype.deaccentuate = function() {
            return this.normalize('NFD').replace(/\p{Diacritic}/gu, "")
        }
        function getCookie(name) {
            var value = "; " + document.cookie
            var parts = value.split("; " + name + "=")
            if (parts.length == 2) return parts.pop().split(";").shift()
        }
        function textSize(text, fontFamily, fontSize) {
            const canvas = document.createElement("canvas")
            const context = canvas.getContext("2d")
            context.font = `${fontSize} ${fontFamily}`
            return context.measureText(text).width
        }
        function resizeElement(element) {
            const text = element.value
            const [fontFamily, fontSize] = [window.getComputedStyle(element).fontFamily, window.getComputedStyle(element).fontSize]
            element.style.width = `${textSize(text, fontFamily, fontSize)}px`
        }
        function maximumElementSize(element) {
            const maxlength = element.getAttribute("maxlength")
            return textSize("0".repeat(maxlength), window.getComputedStyle(element).fontFamily, window.getComputedStyle(element).fontSize)   
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/favicon.png">
	<script src="https://kit.fontawesome.com/c0c9f3092d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/fonts.css">
    <?php foreach ($css as $file) { ?>
        <link rel="stylesheet" type="text/css" href="<?= $file ?>">
    <?php } ?>
</head>
<body>
<?php } function layout_footer($js) { ?>
    <?php foreach ($js as $file) { ?>
        <script type="text/javascript" src="<?= $file ?>"></script>
    <?php } ?>
<span class="version"><?= VERSION["id"] ?>-<?= VERSION["environment"] ?></span>
</body>
</html>
<?php } ?>