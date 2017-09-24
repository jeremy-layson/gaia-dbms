<?php 

/**
* 4.32 Ethnicity of Household Heads
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 24
*/
class Class_4_32
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
        $this->tbl_cols = $tbl_cols = array("tagalog", "waray", "ilonggo", "bicolano", "bisaya", "ilocano", "aklanon", "davaoeno", "panggalatok", "kapampangan", "batangeno", "bulakeno", "noans", 'Total');

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
                $eth = trim(strtoupper(explode(",", $row['ethnicity'])[0]));
                $col = "";

                if ($eth == "AKLAN") $col = "aklanon";
                if ($eth == "AKLANON") $col = "aklanon";
                if ($eth == "BATANGGEÑO") $col = "batangeno";
                if ($eth == "BICOLANO") $col = "bicolano";
                if ($eth == "BISAYA") $col = "bisaya";
                if ($eth == "BULAKEÑO") $col = "bulakeno";
                if ($eth == "BULAKENYA") $col = "bulakeno";
                if ($eth == "CEBUANO") $col = "bisaya";
                if ($eth == "DAVAOENO") $col = "davaoeno";
                if ($eth == "ILLONGO") $col = "ilonggo";
                if ($eth == "ILOCANO") $col = "ilocano";
                if ($eth == "CEBUANO") $col = "bisaya";
                if ($eth == "ILONGGO") $col = "ilonggo";
                if ($eth == "KAPAMPANGAN") $col = "kapampangan";
                if ($eth == "PANGGALATOK") $col = "panggalatok";
                if ($eth == "TAGALO") $col = "tagalog";
                if ($eth == "TAGALOG") $col = "tagalog";
                if ($eth == "TAGAOG") $col = "tagalog";
                if ($eth == "WARAY") $col = "waray";
                if ($eth == "") $col = "noans";
                

                if ($col != "") {
                    unset($this->unclaimed[$row['uid']]);
                    $data[$mun][$col][] = $row['uid'];
                    $data[$mun][$col]['COUNT']++;

                    $data[$mun]['Total'][] = $row['uid'];
                    $data[$mun]['Total']['COUNT']++;
                    
                    $col_total[$col][] = $row['uid'];
                    $col_total[$col]['COUNT']++;
                    $col_total['Total'][] = $row['uid'];
                    $col_total['Total']['COUNT']++;
                       
                }
                   
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