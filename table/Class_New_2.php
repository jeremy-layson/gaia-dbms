<?php 

/**
* Table ##. Relocation Experience
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 10. 03
*/
class Class_New_2
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND reloc_exp = 'Y'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('bagumbong', 'langkiwa', 'malis', 'northville2b', 'northville8', 'pnr', 'refuse', 'total');
        
        foreach ($tbl_cols as $colm) {
            $col_total['Total']['Total'][$colm] = array('COUNT' => 0);
        }
        // $col_total['Total']['Total']['Total'] = array('COUNT' => 0);


        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $colm) {
                $data[$mun]['Sub Total'][$colm] = array('COUNT' => 0);
            }
            // $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $colm) {
                    $data[$mun][$col[0]][$colm] = array('COUNT' => 0);
                }
                // $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0 AND reloc_exp = 'Y' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    
                    
                    $val = trim(strtoupper($row['reloc_site']));

                    $category = "";

                    if (strpos($val, "BAGUMBONG") !== FALSE) { $category = "bagumbong"; }
                    if (strpos($val, "LANGKIWA") !== FALSE) { $category = "langkiwa"; }
                    if (strpos($val, "MALIS") !== FALSE) { $category = "malis"; }
                    if (strpos($val, "NORTHVILLE 2B") !== FALSE) { $category = "northville2b"; }
                    if (strpos($val, "NORTHVILLE VIII") !== FALSE) { $category = "northville8"; }
                    if (strpos($val, "PNR") !== FALSE) { $category = "pnr"; }
                    if (strpos($val, "REFUSE") !== FALSE) { $category = "refuse"; }
                    
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