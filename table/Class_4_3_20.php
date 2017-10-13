<?php 

/**
* 4.3-20 Source of Fuel for Cooking
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 02
*/
class Class_4_3_20
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
        $this->tbl_cols = $tbl_cols = array('elec', 'wood', 'kerosene', 'lpg', 'coal', 'others', 'noans', 'Total');
        
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
                $result = $this->db->query($query = "SELECT uid,address,baranggay,he_fuel_cooking FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    $source = strtoupper($row['he_fuel_cooking']);
                    $category = "";

                    if ($source == "CHARCOAL") $category = "coal";
                    if ($source == "ELECTRICITY") $category = "elec";
                    if ($source == "KEROSENE") $category = "kerosene";
                    if ($source == "KEROSENE (GAAS)") $category = "kerosene";
                    if ($source == "KEROSENE, CHARCOAL") $category = "kerosene";
                    if ($source == "KEROSENE, LPG") $category = "kerosene";
                    if ($source == "LPG") $category = "lpg";
                    if ($source == "LPG AND CHARCOAL") $category = "lpg";
                    if ($source == "LPG, CHARCOAL") $category = "lpg";
                    if ($source == "LPG, KEROSENE") $category = "lpg";
                    if ($source == "MGAS") $category = "kerosene";
                    if ($source == "WOOD") $category = "wood";
                    if ($source == "WOOD AND CHARCOAL") $category = "wood";
                    if ($source == "WOOD, CHARCOAL") $category = "wood";
                    if ($source == "WOOD AND LPG") $category = "wood";
                    if ($source == "WOOD, KEROSENE") $category = "wood";
                    if ($source == "WOOD, LPG") $category = "wood";
                    if ($source == "WOOD, LPG, CHARCOAL") $category = "wood";
                    if ($source == "NONE") $category = "others";
                    if ($source == "") $category = "noans";
                    if ($source == "NO ANSWER") $category = "noans";
                    if ($source == "N/A") $category = "noans";
                    
                    if ($category != "") {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][$category][] = $row['uid'];
                        $data[$mun][$col[0]][$category]['COUNT']++;
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['Total']['COUNT']++;

                        $data[$mun]['Sub Total'][$category][] = $row['uid'];
                        $data[$mun]['Sub Total'][$category]['COUNT']++;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT']++;
                        
                        $col_total['Total']['Total'][$category][] = $row['uid'];
                        $col_total['Total']['Total'][$category]['COUNT']++;
                        $col_total['Total']['Total']['Total'][] = $row['uid'];
                        $col_total['Total']['Total']['Total']['COUNT']++;
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