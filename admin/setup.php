<?php
/**
 * Setup page
 */

require '../../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';

global $langs;
global $db;
global $conf;
global $user;

$langs->load("admin");
$langs->load("cbwarquarterstats@cbwarquarterstats");
$langs->load("users");

if (!$user->admin) {
	accessforbidden();
}

/*
 * Actions
 */

if ($opts = GETPOST('opts')) {
	// Save charges value
	if (($opt_charges = filter_var($opts['CBWARQUARTERSTATS_CHARGES_PCT'], FILTER_VALIDATE_FLOAT)) !== false) {
		dolibarr_set_const($db, 'CBWARQUARTERSTATS_CHARGES_PCT', $opt_charges, 'chaine', '0', '', $conf->entity);
	} else {
		setEventMessages($langs->trans("IntNeeded"), null, 'errors');
	}

	if (($opt_abtmt = filter_var($opts['CBWARQUARTERSTATS_ABTMT_PCT'], FILTER_VALIDATE_FLOAT)) !== false) {
		dolibarr_set_const($db, 'CBWARQUARTERSTATS_ABTMT_PCT', $opt_abtmt, 'chaine', '0', '', $conf->entity);
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

// Default values
$opt_charges = $conf->global->CBWARQUARTERSTATS_CHARGES_PCT ?? 22.1;
$opt_abtmt = $conf->global->CBWARQUARTERSTATS_ABTMT_PCT ?? 34.7;

?>
	<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="token" value="<?= newToken() ?>"/>
		<table class="noborder allwidth">
			<tr class="liste_titre">
				<th><?= $langs->trans("Description") ?></th>
				<th class="center"></th>
				<th class="right"></th>
			</tr>
			<tr class="oddeven">
				<td>
					<label for="chargesNum"><?= $langs->trans("ChargesNumOption") ?></label>
				</td>
				<td class="center"></td>
				<td class="right">
                    <span style="white-space: nowrap">
                    <input id="chargesNum" min="0" max="100" type="number"
						   class="flat" step=".01"
						   name="opts[CBWARQUARTERSTATS_CHARGES_PCT]"
						   value="<?= $opt_charges ?>"> %</span>
				</td>
			</tr>
			<tr>
				<td>
					<label for="chargesNum"><?= $langs->trans("AbtmtNumOption") ?></label>
				</td>
				<td class="center"></td>
				<td class="right">
                    <span style="white-space: nowrap">
                    <input id="chargesNum" min="0" max="100" type="number"
						   class="flat" step=".01"
						   name="opts[CBWARQUARTERSTATS_ABTMT_PCT]"
						   value="<?= $opt_abtmt ?>"> %</span>
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

