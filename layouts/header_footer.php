<?php function layout_header($title, $css) { ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<base href="<?= WEBSITE_URL ?>">
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <script type="text/javascript">
        const url = (a) => new URL(a, "<?= WEBSITE_URL ?>").toString()
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
        function getCookie(name) {
            var value = "; " + document.cookie
            var parts = value.split("; " + name + "=")
            if (parts.length == 2) return parts.pop().split(";").shift()
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
</body>
</html>
<?php } ?>