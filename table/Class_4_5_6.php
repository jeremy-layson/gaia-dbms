<?php 

/**
* 4.5-6 Factors Considered in Choosing Relocation Sites
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 19
*/
class Class_4_5_6
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE hh_head LIKE '%[322]'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();

        $this->tbl_cols = $tbl_cols = array('rpo_reloc_factor_near_orig', 'rpo_reloc_factor_livelihood', 'rpo_reloc_factor_health_school', 'rpo_reloc_factor_market_access', 'rpo_reloc_factor_transport_access', 'rpo_reloc_factor_4ps_benefit', 'rpo_reloc_factor_others');

        foreach ($tbl_cols as $field) $col_total['Total']['Total'][$field] = array('COUNT' => 0);
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $field) $data[$mun]['Sub Total'][$field] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $field) $data[$mun][$col[0]][$field] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,rpo_reloc_factor_near_orig, rpo_reloc_factor_livelihood, rpo_reloc_factor_health_school, rpo_reloc_factor_market_access, rpo_reloc_factor_transport_access, rpo_reloc_factor_4ps_benefit, rpo_reloc_factor_others FROM survey WHERE `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    unset($this->unclaimed[$row['uid']]);

                    foreach ($tbl_cols as $field) {
                        $toAdd = trim($row[$field]);
                        if ($toAdd != '') {
                            $toAdd = 1;
                            $data[$mun][$col[0]][$field][] = $row['uid'];
                            $data[$mun][$col[0]][$field]['COUNT'] += $toAdd;
                            $data[$mun][$col[0]]['Total'][] = $row['uid'];
                            $data[$mun][$col[0]]['Total']['COUNT'] += $toAdd;

                            $data[$mun]['Sub Total'][$field][] = $row['uid'];
                            $data[$mun]['Sub Total'][$field]['COUNT'] += $toAdd;
                            $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                            $data[$mun]['Sub Total']['Total']['COUNT'] += $toAdd;
                            
                            $col_total['Total']['Total'][$field][] = $row['uid'];
                            $col_total['Total']['Total'][$field]['COUNT'] += $toAdd;
                            $col_total['Total']['Total']['Total'][] = $row['uid'];
                            $col_total['Total']['Total']['Total']['COUNT'] += $toAdd;
                        }

                    }
                }
            }
            $sub = $data[$mun]['Sub Total'];
            unset($data[$mun]['Sub Total']);
            $data[$mun]['Sub Total'] = $sub;
        }

        return array_merge($data, $col_total);
    }

    private function getMunicipality()
    {
        $query = "SELECT * FROM municipality";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[$row['municipality']][] = array($row['baranggay'], $row['wildcard']);
        }

        return $data;
    }

        //returns the wildcarded string
    private function getWildcard($wildcard) {
        $ret = '';
        $wc = explode(',', $wildcard);
        foreach ($wc as $card) {
            if (is_numeric(trim($card)) === TRUE) {
                $card = 'Barangay ' . trim($card);
            }
            $ret = $ret . " baranggay = '" . trim($card) . "' OR";
        }

        return ' (' . substr($ret, 0, -3) . ')';
    }

}