<?php 

/**
* 4.33 Religious Affiliation
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 24
*/
class Class_4_33
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

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
        $this->tbl_cols = $tbl_cols = array("rc", "inc", "bagain", "camacop", "miracle", "protestant", "christian", "add", "islam", "noans", 'Total');

        foreach ($tbl_cols as $col) {
            $col_total[$col] = array('COUNT' => 0);
        }


        foreach ($columns as $mun => $brgys) {


            foreach ($tbl_cols as $col) {
                $data[$mun][$col] = array('COUNT' => 0);
            }
            $query = "SELECT * FROM survey WHERE is_deleted = 0 AND hh_head LIKE '%[322]' AND `address` LIKE '%" . $mun . "%'";
            if ($mun == "Valenzuela") $query =  $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);
            while ($row = $result->fetch_assoc()) {
                $rel = trim(strtoupper($row['religion']));
                $col = "";

                if ($rel =="BORN AGAIN") $col = "bagain";
                if ($rel =="BORN AGAIN CHRISTIAN") $col = "bagain";
                if ($rel =="CAMACOP") $col = "camacop";
                if ($rel =="CHRISTIAN") $col = "christian";
                if ($rel =="MALINTA V.C.") $col = "christian";
                if ($rel =="DATING DAAN") $col = "add";
                if ($rel =="INC") $col = "inc";
                if ($rel =="ISLAM") $col = "islam";
                if ($rel =="MIRACLE CRUSADE") $col = "miracle";
                if ($rel =="PROTESTANT") $col = "protestant";
                if ($rel =="ROMAN CAHOLIC") $col = "rc";
                if ($rel =="ROMAN CATHOIC") $col = "rc";
                if ($rel =="ROMAN CATHOLIC") $col = "rc";
                if ($rel =="") $col = "noans";
                
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