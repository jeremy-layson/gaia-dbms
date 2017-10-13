<?php 

/**
* 4.3-19 Source of Lighting
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 02
*/
class Class_4_3_19
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
        $this->tbl_cols = $tbl_cols = array('owned', 'share', 'gaas', 'solar', 'batt', 'none', 'noans', 'Total');
        
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
                $result = $this->db->query($query = "SELECT uid,address,baranggay,he_source_light FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    $source = strtolower($row['he_source_light']);
                    $category = "";

                    if (strpos($source, "gaas") !== FALSE) $category = "gaas";
                    if (strpos($source, "gas") !== FALSE) $category = "gaas";
                    if (strpos($source, "owned") !== FALSE) $category = "owned";
                    if (strpos($source, "sharing") !== FALSE) $category = "share";
                    if (strpos($source, "Sharing") !== FALSE) $category = "share";
                    if (strpos($source, "none") !== FALSE) $category = "none";
                    if (strpos($source, "answer") !== FALSE) $category = "noans";
                    if ($source == 'n/a') $category = "noans";
                    if (strpos($source, "solar") !== FALSE) $category = "solar";
                    if (strpos($source, "battery") !== FALSE) $category = "batt";
                    
                    if (strlen(trim($source)) == 0) $category = "noans";

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
            // if (is_numeric(trim($card)) === TRUE) {
            //     $card = 'Barangay ' . trim($card);
            // }
            $ret = $ret . " baranggay = '" . trim($card) . "' OR";
        }

        return ' (' . substr($ret, 0, -3) . ')';
    }

}