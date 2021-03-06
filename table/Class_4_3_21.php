<?php 

/**
* 4.3-21 Water Supply
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 02
*/
class Class_4_3_21
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
        $this->tbl_cols = $tbl_cols = array('maynilad', 'sharing', 'deepwell', 'shallow', 'refill', 'local', 'nawasa', 'noans', 'Total');
        
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
                $result = $this->db->query($query = "SELECT uid,address,baranggay,he_water_supply FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    $source = strtoupper($row['he_water_supply']);
                    $category = "";

                    if ($source == "(NAKIKIIGIB)") $category = "sharing";
                    if ($source == "BOUGHT NAWASA, DEEP WELL") $category = "nawasa";
                    if ($source == "BULACAN WATER DISTRCIT") $category = "maynilad";
                    if ($source == "DEEP WELL") $category = "deepwell";
                    if ($source == "DEEP WELL/NAWASA/WATER REFILL") $category = "deepwell";
                    if ($source == "LOCAL WATER DISTRICT") $category = "local";
                    if ($source == "MAYNIAD/MANILA WATER") $category = "maynilad";
                    if ($source == "MAYNILAD/ MANILA WATER") $category = "maynilad";
                    if ($source == "MAYNILAD/ MANILA WATER, SHARING (NEIGHBOR)") $category = "maynilad";
                    if ($source == "MAYNILAD/MANILA WATER") $category = "maynilad";
                    if ($source == "NAWASA") $category = "nawasa";
                    if ($source == "SHALLOW WELL") $category = "shallow";
                    if ($source == "SHALLOW WELL/ WATER REFILL") $category = "shallow";
                    if ($source == "SHARING") $category = "sharing";
                    if ($source == "SHARING (NEGHBOR)") $category = "sharing";
                    if ($source == "SHARING (NEIGHBOR)") $category = "sharing";
                    if ($source == "SHARING (NEIGHBOR), WATER REFILL") $category = "sharing";
                    if ($source == "WATER REFILL") $category = "refill";
                    if ($source == "NO ANSWER") $category = "noans";
                    if ($source == "N/A") $category = "noans";
                    
                    if ($source == "") $category = "noans";
                    

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