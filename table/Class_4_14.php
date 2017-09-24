<?php 

/**
* 4.14 Affected Structures and Improvements 
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 24
*/
class Class_4_14
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array("LEGAL_Severe", "LEGAL_Margin", "ISF_Severe", "ISF_Margin", "Total_Severe", "Total_Margin");

        foreach ($tbl_cols as $col) {
            $col_total[$col] = array('COUNT' => 0);
        }


        foreach ($columns as $mun => $brgys) {


            foreach ($tbl_cols as $col) {
                $data[$mun][$col] = array('COUNT' => 0);
            }
            $query = "SELECT * FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $mun . "%'";
            if ($mun == "Valenzuela") $query =  $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);
            while ($row = $result->fetch_assoc()) {
                $type = $row['type'];
                $extent = $row['extent'];

                $affect = "";
                if ($extent == "< than 20%") {
                    $affect = "Margin";
                } else {
                    $affect = "Severe";
                }

                unset($this->unclaimed[$row['uid']]);
                
                $data[$mun][$type . "_" . $affect][] = $row['uid'];
                $data[$mun][$type . "_" . $affect]['COUNT']++;
                $data[$mun]["Total_" . $affect][] = $row['uid'];
                $data[$mun]["Total_" . $affect]['COUNT']++;
                
                $col_total[$type . "_" . $affect][] = $row['uid'];
                $col_total[$type . "_" . $affect]['COUNT']++;
                $col_total["Total_" . $affect][] = $row['uid'];
                $col_total["Total_" . $affect]['COUNT']++;
            }

        }

        $this->total = $col_total;
        return $data;
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