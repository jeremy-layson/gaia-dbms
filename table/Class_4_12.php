<?php 

/**
* 4.12 Proof of Ownership Presented by Legal Landowners per LGU
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 24
*/
class Class_4_12
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
        $this->tbl_cols = $tbl_cols = array('title', 'ret', 'dm', 'lp', 'brc', 'lr', 'lc', 'cola', 'Total');

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
                
                $temps = [];

                foreach ($tbl_cols as $col) $temps[$col] = 0;

                $temps['title'] = strtoupper($row['kd_title']) == "X" ? 1:0;
                $temps['ret'] = strtoupper($row['kd_real']) == "X" ? 1:0;
                $temps['dm'] = strtoupper($row['kd_deed']) == "X" ? 1:0;
                $temps['lp'] = strtoupper($row['kd_landplan']) == "X" ? 1:0;
                $temps['brc'] = strtoupper($row['kd_brgycert']) == "X" ? 1:0;
                $temps['lr'] = strtoupper($row['kd_landrights']) == "X" ? 1:0;
                $temps['lc'] = strtoupper($row['kd_lease']) == "X" ? 1:0;
                $temps['cola'] = strtoupper($row['kd_certaward']) == "X" ? 1:0;
                
                $added = FALSE;
                foreach ($tbl_cols as $col) {
                    if ($temps[$col] == 1) {
                        $added = TRUE;
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col][] = $row['uid'];
                        $data[$mun][$col]['COUNT']++;
                        $data[$mun]['Total'][] = $row['uid'];
                        $data[$mun]['Total']['COUNT']++;
                        
                        if ($added == TRUE) {
                            $col_total[$col][] = $row['uid'];
                            $col_total[$col]['COUNT']++;
                            $col_total['Total'][] = $row['uid'];
                            $col_total['Total']['COUNT']++;
                        }
                        $added = FALSE;
                    }   
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