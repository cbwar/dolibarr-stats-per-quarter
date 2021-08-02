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

if ($conf->product->enabled) {
    ?>
    <h3><?= $langs->trans("Products") ?></h3>
    <div class="fichecenter" style="min-width: 500px; max-width: 700px;">
        <?php showSalesActivity(0); ?>
        <br/>
        <?php showChargesActivity(0); ?>
        <br/>
        <?php showAbtmtActivity(0); ?>
    </div>
    <?php
}
if ($conf->service->enabled) {
    ?>
    <h3><?= $langs->trans("Services") ?></h3>
    <div class="fichecenter" style="min-width: 500px;max-width: 700px;">
        <?php showSalesActivity(1); ?>
        <br/>
        <?php showChargesActivity(1); ?>
        <br/>
        <?php showAbtmtActivity(1); ?>
    </div>
    <?php
}

$db->close();
llxFooter();
