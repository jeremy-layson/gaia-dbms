<?php 

/**
* 12.1-6 Estimated Cost of ISF Structures based on RCS
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 25
*/
class Class_12_1_6_old
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

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
        $this->tbl_cols = $tbl_cols = array("Margin_no", "Margin_area", "Margin_cost", "Severe_no", "Severe_area", "Severe_cost", "Total");

        foreach ($tbl_cols as $col) {
            $col_total[$col] = 0;
        }


        foreach ($columns as $mun => $brgys) {


            foreach ($tbl_cols as $col) {
                $data[$mun][$col] = 0;
            }
            $query = "SELECT * FROM survey WHERE is_deleted = 0 AND `type` = 'ISF' AND `address` LIKE '%" . $mun . "%'";
            if ($mun == "Valenzuela") $query =  $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);
            while ($row = $result->fetch_assoc()) {
                $area = floatval($row['dms_affected']);
                $extent = $row['extent'];
                $affect = "";
                if ($extent == "< than 20%") {
                    $affect = "Margin";
                } else {
                    $affect = "Severe";
                }

                unset($this->unclaimed[$row['uid']]);
                
                $data[$mun][$affect . "_no"]++;
                $data[$mun][$affect . "_area"]+= $area;
                
                
                $col_total[$affect . "_no"]++;
                $col_total[$affect . "_area"]++;
                   
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