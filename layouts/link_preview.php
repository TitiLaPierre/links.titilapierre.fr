<?php function layout_link_preview($link) { ?>
    <a class="linkPreview" href="link/<?= $link["uid"] ?>/">
        <div class="linkPreview--content">
            <?php if (empty($link["icon_uid"])) { ?>
                <i class="linkPreview--icon fa-solid fa-earth-europe"></i>
            <?php } else { ?>
                <img class="linkPreview--icon" src="media/<?= $link["icon_uid"] ?>">
            <?php } ?>
            <div>
                <h2 class="linkPreview--title"><?= $link["name"] ?></h2>
                <span class="linkPreview--description">
                    <?php
                    if ($link["views"] > 0) {
                        $vues = $link["views"]." vue".($link["views"] > 1 ? "s" : "");
                        $lastActivity = "Dernière activité le ".date("d/m/Y", $link["last_activity"]);
                        $activity = $vues." — ".$lastActivity;
                    } else {
                        $activity = "Aucune activité — Créé le ".date("d/m/Y", $link["last_activity"]);
                    }
                    ?>
                    <?= $activity ?>
                </span>
            </div>
        </div>
        <div class="linkPreview--url--list">
            <div class="linkPreview--url">
                <h6 class="linkPreview--url--label">Source</h6>
                <span class="linkPreview--url--value"><?= WEBSITE_URL ?><u><?= $link["source_url"] ?></u>/</span>
            </div>
            <div class="linkPreview--url">
                <h6 class="linkPreview--url--label">Destination</h6>
                <span class="linkPreview--url--value"><u><?= $link["redirect_url"] ?></u></span>
            </div>
        </div>
    </a>
<?php } ?>