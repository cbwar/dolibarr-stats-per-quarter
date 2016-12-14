<?php

if (false === (@include '../../main.inc.php')) {  // From htdocs directory
    require '../../../main.inc.php'; // From "custom" directory
}

require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once __DIR__ . '/lib/lib.inc.php';

global $db, $langs, $user;
global $conf;

// External user
if ($user->socid > 0) {
    accessforbidden();
}
// User rights testing
restrictedArea($user, 'produit|service');

// Load lang
$langs->load("products");
$langs->load("services");
$langs->load("cbwarquarterstats@cbwarquarterstats");

/*
 * ACTIONS
 */

// No actions

/*
 * VIEW
 */

llxHeader('', $langs->trans('StatsPerQuarter'), '');

print load_fiche_titre($langs->trans('StatsPerQuarter'), "", 'title_products.png');

$blocks = array();
if ($conf->product->enabled) {
    $blocks[] = array(0, $langs->trans("Products"));
}
if ($conf->service->enabled) {
    $blocks[] = array(1, $langs->trans("Services"));
}

?>
    <div class="fichecenter">
        <?php foreach ($blocks as $i => $block): ?>
            <div class="fichethirdleft">
                <?php if ($i !== 0): ?>
                <div class="ficheaddleft"><?php endif; ?>
                    <h3><?= $block[1] ?></h3>
                    <?php showSalesActivity($block[0]); ?>
                    <br>
                    <?php showChargesActivity($block[0]); ?>
                    <?php if ($i !== 0): ?>
                </div>
            <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php
$db->close();
llxFooter();
