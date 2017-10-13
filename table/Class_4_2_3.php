<?php 

/**
* 4.2-3 Affected Improvements
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 24
*/
class Class_4_2_3
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

        $this->tbl_cols = $tbl_cols = array('RESIDENTIAL', 'INSTITUTIONAL', 'INDUSTRIAL', 'MIXED USE', 'COMMERCIAL', 'AGRICULTURAL', 'Total');
        $this->improvements = $improvements = array('Fence', 'Gate', 'Others');

        foreach ($tbl_cols as $field) {
            $col_total[$field] = array('COUNT' => 0);
        }
        


        foreach ($columns as $mun => $brgys) {
            
            foreach ($tbl_cols as $field) {
                $data[$mun]['Sub Total']['Sub Total'][$field] = array('COUNT' => 0);
            }

            foreach ($brgys as $brgy => $col) {

                foreach ($improvements as $imp) {
                    foreach ($tbl_cols as $field) {
                        $data[$mun][$col[0]][$imp][$field] = array('COUNT' => 0);
                    }
                }

                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
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
                    $imp['Others'] += $row['improve_bridge'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_terminal'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_shed'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_storage'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_watertank'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_extension'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_fishpond'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_garage'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_sarisari'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_playground'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_table'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_extension'] != '' ? 1:0;
                    $imp['Others'] += $row['improve_parking'] != '' ? 1:0;

                    if (in_array($use, $tbl_cols) === TRUE) {
                        unset($this->unclaimed[$row['uid']]);
                        foreach ($imp as $key => $val) {
                            $data[$mun][$col[0]][$key][$use][] = $row['uid'];
                            $data[$mun][$col[0]][$key][$use]['COUNT'] += $val;
                            $data[$mun][$col[0]][$key]['Total'][] = $row['uid'];
                            $data[$mun][$col[0]][$key]['Total']['COUNT'] += $val;

                            $data[$mun]['Sub Total']['Sub Total'][$use][] = $row['uid'];
                            $data[$mun]['Sub Total']['Sub Total'][$use]['COUNT'] += $val;
                            $data[$mun]['Sub Total']['Sub Total']['Total'][] = $row['uid'];
                            $data[$mun]['Sub Total']['Sub Total']['Total']['COUNT'] += $val;
                            
                            $col_total[$use][] = $row['uid'];
                            $col_total[$use]['COUNT'] += $val;
                            $col_total['Total'][] = $row['uid'];
                            $col_total['Total']['COUNT'] += $val;
                        }
                    }
                }
            }
            $sub = $data[$mun]['Sub Total'];
            unset($data[$mun]['Sub Total']);
            $data[$mun]['Sub Total'] = $sub;
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