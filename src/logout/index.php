<?php

if (!is_logged()) redirect("/login/");

return [
    "title" => "Tableau de bord"
];

function render() { ?>
    <script type="text/javascript">
        const logout = async () => {
            const response = await api_call(url("api/logout/"), { method: "POST" })
            document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
            window.location.href = url(`login/?disconnected`)
        }
        window.addEventListener("pageshow", logout)
    </script>
<?php }

?>