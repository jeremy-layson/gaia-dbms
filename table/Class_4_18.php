<?php 

/**
* 4.18 Occupancy Arrangement
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 24
*/
class Class_4_18
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
        $this->tbl_cols = $tbl_cols = array("owner", "tenant", "rentfree", "caretaker", "sharer", "dwellers", "land_owner", "isf", "noans", 'Total');

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
                $dp = strtoupper($row['structure_dp']);
                $dp2 = strtoupper($row['dp_type']);
                $col = "";

                if ($dp == "CARETAKER") $col = "caretaker";
                if ($dp == "LAND OWNER" || $dp2 == "LAND OWNER") $col = "land_owner";
                if ($dp == "COMMERCIAL TENANT") $col = "tenant";
                if ($dp == "INSTITUTIONAL OCCUPANT") $col = "rentfree";
                if ($dp == "SHARER") $col = "sharer";
                if ($dp == "STRUCTURE OWNER") $col = "owner";
                if ($dp == "STRUCTURE RENTER") $col = "tenant";
                if ($row['type'] == "ISF") $col = "isf";
                if ($dp2 == "LAND DWELLER") $col = "dwellers";

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