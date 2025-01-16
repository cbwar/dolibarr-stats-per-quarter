<?php

if (false === (@include '../../main.inc.php')) {  // From htdocs directory
    require '../../../main.inc.php'; // From "custom" directory
}

require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once __DIR__ . '/lib/lib.inc.php';

global $conf, $db, $langs, $user;

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
ini_set('display_errors', 'On');


llxHeader('', $langs->trans('StatsPerQuarter'), '');

print load_fiche_titre($langs->trans('StatsPerQuarter'), "", 'title_products.png');

$select_product_type = GETPOST('product_type');
if ($select_product_type === '') $select_product_type = 'service';

$h = 0;
$head = array();
if (isset($conf->product->enabled)) {
    $head[$h][0] = DOL_URL_ROOT . '/custom/cbwarquarterstats/index.php?product_type=product';
    $head[$h][1] = $langs->trans('Product');
    $head[$h][2] = 'product';
    $h++;
}
if (isset($conf->service->enabled)) {
    $head[$h][0] = DOL_URL_ROOT . '/custom/cbwarquarterstats/index.php?product_type=service';
    $head[$h][1] = $langs->trans('Service');
    $head[$h][2] = 'service';
    $h++;
}
print dol_get_fiche_head(
    $head,
    $select_product_type,
    $select_product_type === 'product' ? $langs->trans('Product') : $langs->trans('Service'),
    -1
);


$stats = new QuarterStats($select_product_type);
$data = $stats->getData();

if (empty($data)) {

    print 'No data';

} else {

    $selected_year = GETPOST('year', 'int');
    if ($selected_year === '') $selected_year = date('Y');

    $h = 0;
    $head = array();
    foreach ($data as $year => $ydata) {
        $head[$h][0] = DOL_URL_ROOT . '/custom/cbwarquarterstats/index.php?product_type=' . $select_product_type . '&year=' . $year;
        $head[$h][1] = $year;
        $head[$h][2] = 'year_' . $year;
        $h++;
    }
    print dol_get_fiche_head($head, 'year_' . $selected_year, $selected_year, -1);

    $stats->renderTable($selected_year);

}

$db->close();
llxFooter();
