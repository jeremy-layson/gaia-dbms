<?php 

/**
* Table ##. ISF Rental Fees
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 10. 03
*/
class Class_New_3
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND `type` = 'ISF'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('less', '1K', '2K', '3K', '4K', '5K', 'more', 'total');
        
        foreach ($tbl_cols as $colm) {
            $col_total['Total']['Total'][$colm] = array('COUNT' => 0);
        }
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);


        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $colm) {
                $data[$mun]['Sub Total'][$colm] = array('COUNT' => 0);
            }
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $colm) {
                    $data[$mun][$col[0]][$colm] = array('COUNT' => 0);
                }
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0  AND `type` = 'ISF' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    
                    
                    $val = intval(trim($row['he_rental_amort']));
                    
                    if ($val === 0) {
                        $val = intval(trim($row['dsa_rent_cost']));
                    }

                    $category = "";
                    
                    if ($val < 1000) $category = "less";
                    if ($val >= 1000 AND $val < 2000) $category = "1K";
                    if ($val >= 2000 AND $val < 3000) $category = "2K";
                    if ($val >= 3000 AND $val < 4000) $category = "3K";
                    if ($val >= 4000 AND $val < 5000) $category = "4K";
                    if ($val >= 5000 AND $val < 6000) $category = "5K";
                    if ($val >= 6000) $category = "more";
                    

                    if ($category != '') {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][$category][] = $row['uid'];
                        $data[$mun][$col[0]][$category]['COUNT']++;
                        $data[$mun][$col[0]]['total'][] = $row['uid'];
                        $data[$mun][$col[0]]['total']['COUNT']++;

                        $data[$mun]['Sub Total'][$category][] = $row['uid'];
                        $data[$mun]['Sub Total'][$category]['COUNT']++;
                        $data[$mun]['Sub Total']['total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['total']['COUNT']++;
                        
                        $col_total['Total']['Total'][$category][] = $row['uid'];
                        $col_total['Total']['Total'][$category]['COUNT']++;
                        $col_total['Total']['Total']['total'][] = $row['uid'];
                        $col_total['Total']['Total']['total']['COUNT']++;
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