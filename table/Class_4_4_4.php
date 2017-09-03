<?php 

/**
* 4.4-4 Female Participation in Household Decision Making
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 04
*/
class Class_4_4_4
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('fpdm_finance', 'fpdm_education', 'fpdm_health', 'fpdm_purchase', 'fpdm_daytoday', 'fpdm_social', 'Total');
        
        foreach ($tbl_cols as $colm) {
            $col_total['Total']['Total'][$colm] = array('COUNT' => 0);
        }


        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $colm) {
                $data[$mun]['Sub Total'][$colm] = array('COUNT' => 0);
            }

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $colm) {
                    $data[$mun][$col[0]][$colm] = array('COUNT' => 0);
                }
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    $source = strtoupper($row['he_sanitation']);
                    
                    foreach ($tbl_cols as $category) {
                        if ($category != 'Total') {
                            unset($this->unclaimed[$row['uid']]);
                            $data[$mun][$col[0]][$category][] = $row['uid'];
                            $data[$mun][$col[0]][$category]['COUNT'] += ($row[$category] == "x" ? 1:0);
                            $data[$mun][$col[0]]['Total'][] = $row['uid'];
                            $data[$mun][$col[0]]['Total']['COUNT']  += ($row[$category] == "x" ? 1:0);

                            $data[$mun]['Sub Total'][$category][] = $row['uid'];
                            $data[$mun]['Sub Total'][$category]['COUNT']  += ($row[$category] == "x" ? 1:0);
                            $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                            $data[$mun]['Sub Total']['Total']['COUNT']  += ($row[$category] == "x" ? 1:0);
                            
                            $col_total['Total']['Total'][$category][] = $row['uid'];
                            $col_total['Total']['Total'][$category]['COUNT']  += ($row[$category] == "x" ? 1:0);
                            $col_total['Total']['Total']['Total'][] = $row['uid'];
                            $col_total['Total']['Total']['Total']['COUNT']  += ($row[$category] == "x" ? 1:0);
                            
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
        $query = "SELECT * FROM municipality WHERE is_deleted = 0";
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