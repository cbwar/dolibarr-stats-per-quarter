<?php

/**
 * Main lib file
 */

require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';


class QuarterStats
{

    private int $product_type;

    private array $data = [];

    private float $chargesPct;

    private float $abtmtPct;

    public function __construct(string $product_type)
    {
        global $conf;
        global $db;

        if ($product_type === 'service') {
            $this->product_type = 1;
        } else {
            $this->product_type = 0;
        }
        $this->fetchData();
        $this->chargesPct = (float)dolibarr_get_const($db, 'CBWARQUARTERSTATS_CHARGES_PCT', $conf->entity);
        $this->abtmtPct = (float)dolibarr_get_const($db, 'CBWARQUARTERSTATS_ABTMT_PCT', $conf->entity);
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get quarter for the given month
     * @param int $month
     * @return int
     */
    private function getMonthTrim($month)
    {
        return floor(((int)$month - 1) / 3) + 1;
    }

    /**
     * Get activity per quarter for product type
     * @param $product_type
     * @return array
     */
    private function fetchData()
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
        $sql .= " AND fd.product_type=" . $this->product_type;
        $sql .= " AND p.datep >= '" . $db->idate(dol_get_first_day($yearofbegindate)) . "'";
        $sql .= " GROUP BY annee, mois ";
        $sql .= " ORDER BY annee, mois ";

        $result = $db->query($sql);

        while ($objp = $db->fetch_object($result)) {
            if (!isset($this->data[$objp->annee])) {
                $this->data[$objp->annee] = array(
                    'trim1' => 0,
                    'trim2' => 0,
                    'trim3' => 0,
                    'trim4' => 0,
                );
            }
            $this->data[$objp->annee]['trim' . $this->getMonthTrim($objp->mois)] += $objp->Mnttot;
        }
    }


    public function renderTable(int $year)
    {
        global $langs;
    ?>
        <div class="fichecenter" style="min-width: 500px; max-width: 700px;">
            <table class="noborder">
                <thead>
                    <tr class="liste_titre">
                        <th align="left"></th>
                        <th align="right" width="15%"><?= $langs->trans("Quarter1") ?></th>
                        <th align="right" width="15%"><?= $langs->trans("Quarter2") ?></th>
                        <th align="right" width="15%"><?= $langs->trans("Quarter3") ?></th>
                        <th align="right" width="15%"><?= $langs->trans("Quarter4") ?></th>
                        <th align="right" width="15%"><?= $langs->trans("Total") ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?= $langs->trans("SellByQuarterHT") ?>
                        </td>
                        <td align="right" style="white-space: nowrap;"><?= price($this->data[$year]['trim1']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($this->data[$year]['trim2']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($this->data[$year]['trim3']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($this->data[$year]['trim4']) ?></td>
                        <td align="right" style="font-weight: bold;white-space: nowrap;"><?= price(array_sum($this->data[$year])) ?></td>
                    </tr>

                    <?php

                    $data = array_map(function ($v) {
                        return $v * $this->chargesPct / 100;
                    }, $this->data[$year]);

                    ?>
                    <tr>
                        <td>
                            <?= $langs->trans("ChargesByQuarterHT") ?> (<?= $this->chargesPct ?>%)
                        </td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim1']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim2']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim3']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim4']) ?></td>
                        <td align="right" style="font-weight: bold;white-space: nowrap;"><?= price(array_sum($data)) ?></td>
                    </tr>

                    <?php

                    $data = array_map(function ($v) {
                        return $v * ( 1 - $this->abtmtPct / 100 );
                    }, $this->data[$year]);

                    ?>

                    <tr>
                        <td>
                            <?= $langs->trans("AbtmtByQuarter") ?> (<?= $this->abtmtPct ?>%)
                        </td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim1']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim2']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim3']) ?></td>
                        <td align="right" style="white-space: nowrap;"><?= price($data['trim4']) ?></td>
                        <td align="right" style="font-weight: bold;white-space: nowrap;"><?= price(array_sum($data)) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

<?php
    }
}
