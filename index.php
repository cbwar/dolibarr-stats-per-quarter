<?php

if (false === (@include '../../main.inc.php')) {  // From htdocs directory
    require '../../../main.inc.php'; // From "custom" directory
}

require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once __DIR__ . '/lib/lib.inc.php';

global $db, $langs, $user;
global $conf;

if ($user->socid > 0) {
    // External user
    accessforbidden();
}
restrictedArea($user,'produit|service');

$langs->load("products");
$langs->load("services");
$langs->load("cbwarquarterstats@cbwarquarterstats");


/*
 * ACTIONS
 */

// No actions

/*
 * VIEW
 *
 * Put here all code to build page
 */

llxHeader('', $langs->trans('StatsPerQuarter'), '');

print load_fiche_titre($langs->trans('StatsPerQuarter'), "", 'title_products.png');
?>
    <div class="fichecenter">

        <div class="fichethirdleft">

            <?php if (!empty($conf->product->enabled)): ?>
                <h3><?=$langs->trans("Products")?></h3>
                <?php salesActivity(0); ?>
                <br>
                <?php chargesActivity(0); ?>
            <?php endif; ?>

        </div>
        <div class="fichetwothirdright">
            <div class="ficheaddleft">
                <?php if (!empty($conf->service->enabled)): ?>
                    <h3><?=$langs->trans("Services")?></h3>
                    <?php salesActivity(1); ?>
                    <br>
                    <?php chargesActivity(1); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
$db->close();
llxFooter();
