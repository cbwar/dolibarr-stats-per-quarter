<?php
/**
 * Main lib file
 */

require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';


/**
 * Print html table
 * @param string $title
 * @param array $datas
 */
function showQuarterTbl($title, $datas)
{
    global $langs;
    ?>
    <table class="noborder">
        <tr class="liste_titre">
            <th align="left"><?= $title ?></th>
            <th align="right" width="15%"><?= $langs->trans("Quarter1") ?></th>
            <th align="right" width="15%"><?= $langs->trans("Quarter2") ?></th>
            <th align="right" width="15%"><?= $langs->trans("Quarter3") ?></th>
            <th align="right" width="15%"><?= $langs->trans("Quarter4") ?></th>
            <th align="right" width="15%"><?= $langs->trans("Total") ?></th>
        </tr>
        <?php
        if (count($datas) === 0) { ?>
            <tr>
                <td colspan="6"><?= $langs->trans("NoResults") ?></td>
            </tr>
            <?php
        } else {
            $var = true;
            global $bc;

            foreach ($datas as $year => $trims) {
                $sum = array_sum($trims);
                $var = !$var;
                ?>
                <tr <?= $bc[$var] ?>>
                    <td align="left"><?= $year ?></td>
                    <td align="right" style="white-space: nowrap;"><?= price($trims['trim1']) ?></td>
                    <td align="right" style="white-space: nowrap;"><?= price($trims['trim2']) ?></td>
                    <td align="right" style="white-space: nowrap;"><?= price($trims['trim3']) ?></td>
                    <td align="right" style="white-space: nowrap;"><?= price($trims['trim4']) ?></td>
                    <td align="right" style="font-weight: bold;white-space: nowrap;"><?= price($sum) ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
}

/**
 * Get quarter for the given month
 * @param int $month
 * @return int
 */
function getMonthTrim($month)
{
    return floor(((int)$month - 1) / 3) + 1;
}

/**
 * Get activity per quarter for product type
 * @param $product_type
 * @return array
 */
function getQuartersActivity($product_type)
{
    global $conf, $db;

    // We display the last 3 years
    $yearofbegindate = date('Y', dol_time_plus_duree(time(), -3, "y"));

    // breakdown by quarter
    $sql = "SELECT DATE_FORMAT(p.datep,'%Y') as annee, DATE_FORMAT(p.datep,'%m') as mois, SUM(fd.total_ht) as Mnttot";
    $sql .= " FROM " . MAIN_DB_PREFIX . "facture as f, " . MAIN_DB_PREFIX . "facturedet as fd";
    $sql .= " , " . MAIN_DB_PREFIX . "paiement as p," . MAIN_DB_PREFIX . "paiement_facture as pf";
    $sql .= " WHERE f.entity = " . $conf->entity;
    $sql .= " AND f.rowid = fd.fk_facture";
    $sql .= " AND pf.fk_facture = f.rowid";
    $sql .= " AND pf.fk_paiement= p.rowid";
    $sql .= " AND fd.product_type=" . $product_type;
    $sql .= " AND p.datep >= '" . $db->idate(dol_get_first_day($yearofbegindate)) . "'";
    $sql .= " GROUP BY annee, mois ";
    $sql .= " ORDER BY annee, mois ";

    $result = $db->query($sql);
    $datas = array();

    while ($objp = $db->fetch_object($result)) {
        if (!isset($datas[$objp->annee])) {
            $datas[$objp->annee] = array(
                'trim1' => 0,
                'trim2' => 0,
                'trim3' => 0,
                'trim4' => 0,
            );
        }
        $datas[$objp->annee]['trim' . getMonthTrim($objp->mois)] += $objp->Mnttot;
    }
    return $datas;
}

/**
 *  Print html activity for product type
 *
 * @param      int $product_type Type of product
 */
function showSalesActivity($product_type)
{
    global $langs;
    $datas = getQuartersActivity($product_type);
    showQuarterTbl($langs->trans("SellByQuarterHT"), $datas);
}

/**
 * Print charges for product_type
 *
 * @param      int $product_type Type of product
 */
function showChargesActivity($product_type)
{
    global $conf;
    global $db;
    global $langs;

    // Charges
    $chargesOpt = (float)dolibarr_get_const($db, 'CBWARQUARTERSTATS_CHARGES_PCT', $conf->entity);
    $chargesPct = $chargesOpt / 100.0;
    $chargesData = getQuartersActivity($product_type);
    foreach ($chargesData as $year => $trims) {
        $chargesData[$year] = array_map(function ($v) use ($chargesPct) {
            return $v * $chargesPct;
        }, $trims);
    }
    showQuarterTbl($langs->trans("ChargesByQuarterHT") . ' (' . $chargesOpt . '%)', $chargesData);
}

/**
 * Print abattment for product_type
 *
 * @param      int $product_type Type of product
 */
function showAbtmtActivity($product_type)
{
    global $conf;
    global $db;
    global $langs;

    $abtmtOpt = (float)dolibarr_get_const($db, 'CBWARQUARTERSTATS_ABTMT_PCT', $conf->entity);
    $abtmtPct = $abtmtOpt / 100.0;
    $abtmtData = getQuartersActivity($product_type);
    foreach ($abtmtData as $year => $trims) {
        $abtmtData[$year] = array_map(function ($v) use ($abtmtPct) {
            return $v * (1 - $abtmtPct);
        }, $trims);
    }

    showQuarterTbl($langs->trans("AbtmtByQuarter") . ' (' . $abtmtOpt . '%)', $abtmtData);
}
