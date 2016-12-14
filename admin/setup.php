<?php
/**
 * Setup page
 */

// Load Dolibarr environment
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';

global $langs;
global $db;

$langs->load("admin");
$langs->load("cbwarquarterstats@cbwarquarterstats");
$langs->load("users");

if (!$user->admin) accessforbidden();


/*
 * Actions
 */

if ($opts = GETPOST('opts')) {
	// Save charges value 
	if (($opt_charges = filter_var($opts['QUARTERSTATS_CHARGES_NUM'], FILTER_VALIDATE_INT)) !== false) {
		dolibarr_set_const($db, 'QUARTERSTATS_CHARGES_NUM', $opt_charges, 'chaine', '0', '', $conf->entity);
	} else {
		setEventMessages($langs->trans("IntNeeded"), null, 'errors');
	}
}


/*
 * View
 */

llxHeader('', $langs->trans("QuarterStatsSetup"));

$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';
print load_fiche_titre($langs->trans("QuarterStatsSetup"), $linkback, 'title_setup');

// Default value
$opt_charges = $conf->global->QUARTERSTATS_CHARGES_NUM;
if ($opt_charges === null) {
	$opt_charges = 24;
}

// Opts form
?>
    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
        <table class="noborder" width="100%">
            <tr class="liste_titre">
                <th><?= $langs->trans("Description") ?></th>
                <th align="center" width="20">&nbsp;</th>
                <th align="center" width="100"><?= $langs->trans("Value") ?></th>
            </tr>
            <tr>
                <td>
                    <label for="chargesNum"><?= $langs->trans("ChargesNumOption") ?></label>
                </td>
                <td></td>
                <td align="right"><input id="chargesNum" min="0" max="100" type="number"
                                         class="flat"
                                         name="opts[QUARTERSTATS_CHARGES_NUM]"
                                         value="<?= $opt_charges ?>"> %
                </td>
            </tr>
        </table>
        <div class="tabsAction">
            <input type="submit" class="butAction" value="<?= $langs->trans("Save") ?>"/>
        </div>
    </form>
<?php


llxFooter();
$db->close();

