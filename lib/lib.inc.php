<?php
/**
 * Main lib file
 */


/*
 *  Print html activity for product type
 *
 *  @param      int $product_type   Type of product
 *  @return     void
 */
function salesActivity($product_type)
{
    global $conf, $langs, $db;
    global $bc;

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
    if ($result) {
        $tmpyear = 0;
        $trim1 = 0;
        $trim2 = 0;
        $trim3 = 0;
        $trim4 = 0;
        $lgn = 0;
        $num = $db->num_rows($result);

        if ($num > 0) {
            print '<table class="noborder" width="75%">';

            if ($product_type == 0)
                print '<tr class="liste_titre"><td  align=left>' . $langs->trans("SellByQuarterHT") . '</td>';
            else
                print '<tr class="liste_titre"><td  align=left>' . $langs->trans("SellByQuarterHT") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter1") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter2") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter3") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter4") . '</td>';
            print '<td align=right>' . $langs->trans("Total") . '</td>';
            print '</tr>';
        }
        $i = 0;

        $var = true;

        while ($i < $num) {
            $objp = $db->fetch_object($result);
            if ($tmpyear != $objp->annee) {
                if ($trim1 + $trim2 + $trim3 + $trim4 > 0) {
                    $var = !$var;
                    print '<tr ' . $bc[$var] . '><td align=left>' . $tmpyear . '</td>';
                    print '<td align=right>' . price($trim1) . '</td>';
                    print '<td align=right>' . price($trim2) . '</td>';
                    print '<td align=right>' . price($trim3) . '</td>';
                    print '<td align=right>' . price($trim4) . '</td>';
                    print '<td align=right>' . price($trim1 + $trim2 + $trim3 + $trim4) . '</td>';
                    print '</tr>';
                    $lgn++;
                }
                // We go to the following year
                $tmpyear = $objp->annee;
                $trim1 = 0;
                $trim2 = 0;
                $trim3 = 0;
                $trim4 = 0;
            }

            if ($objp->mois == "01" || $objp->mois == "02" || $objp->mois == "03")
                $trim1 += $objp->Mnttot;

            if ($objp->mois == "04" || $objp->mois == "05" || $objp->mois == "06")
                $trim2 += $objp->Mnttot;

            if ($objp->mois == "07" || $objp->mois == "08" || $objp->mois == "09")
                $trim3 += $objp->Mnttot;

            if ($objp->mois == "10" || $objp->mois == "11" || $objp->mois == "12")
                $trim4 += $objp->Mnttot;

            $i++;
        }
        if ($trim1 + $trim2 + $trim3 + $trim4 > 0) {
            $var = !$var;
            print '<tr ' . $bc[$var] . '><td align=left>' . $tmpyear . '</td>';
            print '<td align=right>' . price($trim1) . '</td>';
            print '<td align=right>' . price($trim2) . '</td>';
            print '<td align=right>' . price($trim3) . '</td>';
            print '<td align=right>' . price($trim4) . '</td>';
            print '<td align=right>' . price($trim1 + $trim2 + $trim3 + $trim4) . '</td>';
            print '</tr>';
        }
        if ($num > 0)
            print '</table>';
    }
}

function chargesActivity($product_type)
{
    global $conf, $langs, $db;
    global $bc;

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
    if ($result) {
        $tmpyear = 0;
        $trim1 = 0;
        $trim2 = 0;
        $trim3 = 0;
        $trim4 = 0;
        $lgn = 0;
        $num = $db->num_rows($result);

        if ($num > 0) {
            print '<table class="noborder" width="75%">';

            if ($product_type == 0)
                print '<tr class="liste_titre"><td  align=left>' . $langs->trans("ChargesByQuarterHT") . '</td>';
            else
                print '<tr class="liste_titre"><td  align=left>' . $langs->trans("ChargesByQuarterHT") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter1") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter2") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter3") . '</td>';
            print '<td align=right>' . $langs->trans("Quarter4") . '</td>';
            print '<td align=right>' . $langs->trans("Total") . '</td>';
            print '</tr>';
        }
        $i = 0;

        $var = true;

        while ($i < $num) {
            $objp = $db->fetch_object($result);
            if ($tmpyear != $objp->annee) {
                if ($trim1 + $trim2 + $trim3 + $trim4 > 0) {
                    $var = !$var;
                    $charges = 24;
                    $chargesTrim1 = ($trim1 / 100) * $charges;
                    $chargesTrim2 = ($trim2 / 100) * $charges;
                    $chargesTrim3 = ($trim3 / 100) * $charges;
                    $chargesTrim4 = ($trim4 / 100) * $charges;

                    print '<tr ' . $bc[$var] . '><td align=left>' . $tmpyear . '</td>';
                    print '<td align=right>' . price($chargesTrim1) . '</td>';
                    print '<td align=right>' . price($chargesTrim2) . '</td>';
                    print '<td align=right>' . price($chargesTrim3) . '</td>';
                    print '<td align=right>' . price($chargesTrim4) . '</td>';
                    print '<td align=right><b>' . price($chargesTrim1 + $chargesTrim2 + $chargesTrim3 + $chargesTrim4) . '</b></td>';
                    print '</tr>';
                    $lgn++;
                }
                // We go to the following year
                $tmpyear = $objp->annee;
                $trim1 = 0;
                $trim2 = 0;
                $trim3 = 0;
                $trim4 = 0;
            }

            if ($objp->mois == "01" || $objp->mois == "02" || $objp->mois == "03")
                $trim1 += $objp->Mnttot;

            if ($objp->mois == "04" || $objp->mois == "05" || $objp->mois == "06")
                $trim2 += $objp->Mnttot;

            if ($objp->mois == "07" || $objp->mois == "08" || $objp->mois == "09")
                $trim3 += $objp->Mnttot;

            if ($objp->mois == "10" || $objp->mois == "11" || $objp->mois == "12")
                $trim4 += $objp->Mnttot;

            $i++;
        }
        if ($trim1 + $trim2 + $trim3 + $trim4 > 0) {
            $var = !$var;
            $charges = 24;
            $chargesTrim1 = ($trim1 / 100) * $charges;
            $chargesTrim2 = ($trim2 / 100) * $charges;
            $chargesTrim3 = ($trim3 / 100) * $charges;
            $chargesTrim4 = ($trim4 / 100) * $charges;

            print '<tr ' . $bc[$var] . '><td align=left>' . $tmpyear . '</td>';
            print '<td align=right><span style="color: black; ">' . price($chargesTrim1) . '</span></td>';
            print '<td align=right><span style="color: black; ">' . price($chargesTrim2) . '</span></td>';
            print '<td align=right><span style="color: black; ">' . price($chargesTrim3) . '</span></td>';
            print '<td align=right><span style="color: black; ">' . price($chargesTrim4) . '</span></td>';
            print '<td align=right><span style="color: red; "><b>' . price($chargesTrim1 + $chargesTrim2 + $chargesTrim3 + $chargesTrim4) . '</b></span></td>';
            print '</tr>';
        }
        if ($num > 0)
            print '</table>';
    }
}