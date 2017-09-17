<?php 

/**
* 4.20 - Number of Affected Improvements by Type of Use
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09 . 17
*/
class Class_4_20
{
    private $db;
    public $unclaimed;
    public $total;
    public $tbl_cols;
    public $improvements;

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

        $this->tbl_cols = $tbl_cols = array('RESIDENTIAL', 'COMMERCIAL', 'INSTITUTIONAL', 'INDUSTRIAL', 'MIXED USE', 'Total');
        $this->improvements = $improvements = array('Fence', 'Gate', 'Others', 'Sub Total');

        foreach ($tbl_cols as $field) {
            $col_total[$field] = array('COUNT' => 0);
        }
        


        foreach ($columns as $mun => $brgys) {

            foreach ($improvements as $imp) {
                foreach ($tbl_cols as $field) {
                    $data[$mun][$imp][$field] = array('COUNT' => 0);
                }
            }

            $query = "SELECT * FROM survey WHERE is_deleted = 0 AND `address` LIKE '%$mun%'";
            if ($mun == "Valenzuela") $query = $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);
            while ($row = $result->fetch_assoc()) {
                
                
                $use = strtoupper($row['structure_use']);

                $imp = [];
                $imp['Fence'] = $row['improve_fence'] != '' ? 1:0;
                $imp['Gate'] = $row['improve_gate'] != '' ? 1:0;

                $imp['Others'] = 0; //wells, pigpens, basketball court, irrigation canal, toilets
                $imp['Others'] += $row['improve_well'] != '' ? 1:0;
                $imp['Others'] += $row['improve_pigpen'] != '' ? 1:0;
                $imp['Others'] += $row['improve_bcourt'] != '' ? 1:0;
                $imp['Others'] += $row['improve_toilet'] != '' ? 1:0;

                $imp['Sub Total'] = 0;
                $imp['Sub Total'] += $imp['Fence'];
                $imp['Sub Total'] += $imp['Gate'];
                $imp['Sub Total'] += $imp['Others'];


                if (in_array($use, $tbl_cols) === TRUE) {
                    unset($this->unclaimed[$row['uid']]);
                    foreach ($imp as $key => $val) {
                        $data[$mun][$key][$use][] = $row['uid'];
                        $data[$mun][$key][$use]['COUNT'] += $val;
                        $data[$mun][$key]['Total'][] = $row['uid'];
                        $data[$mun][$key]['Total']['COUNT'] += $val;

                        if ($key == "Sub Total") {
                            $col_total[$use][] = $row['uid'];
                            $col_total[$use]['COUNT'] += $val;
                            $col_total['Total'][] = $row['uid'];
                            $col_total['Total']['COUNT'] += $val;
                        }
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