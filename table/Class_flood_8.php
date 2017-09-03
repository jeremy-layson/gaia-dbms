<?php 

/**
* Table 8. Duration of Flood
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 31
*/
class Class_flood_8
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]' AND (flood_5years = 'Y' OR flood_5years = 'y')";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('6h', '12h', '24h', '96h', '168h', 'week', 'noans', 'total');
        
        foreach ($tbl_cols as $colm) {
            $col_total['Total']['Total'][$colm] = array('COUNT' => 0);
        }
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);


        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $colm) {
                $data[$mun]['Sub Total'][$colm] = array('COUNT' => 0);
            }
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $colm) {
                    $data[$mun][$col[0]][$colm] = array('COUNT' => 0);
                }
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,flood_subside FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard) AND (flood_5years = 'Y' OR flood_5years = 'y')");
                while ($row = $result->fetch_assoc()) {
                    
                    
                    $val = strtoupper($row['flood_subside']);

                    // $this->tbl_cols = $tbl_cols = array('6h', '12h', '24h', '96h', '168h', 'week', 'noans', 'total');
                    $h_6 = array('3 HRS', '30 MINUTES',);
                    $h_12 = array('0.5', '0.75');
                    $h_24 = array('24 HRS');
                    $h_96 = array('48 HRS'); //3 days
                    $h_168 = array('1 WEEK', '1-WEEK', '4-DAYS', '5-DAYS'); //7 days
                    $h_week = array('1 WEEK OR MORE', '1-2 WEEKS', '1- 2 WEEKS', '1-3 WEEKS', '10-DAYS', 'DAYS AND MONTHS', 'WEEKS');

                    $category = '';
                    if ($val == '' || $val == '-') {
                        $category = 'noans';
                    } elseif (in_array($val, $h_6) === TRUE) {
                        $category = '6h';
                    } elseif (in_array($val, $h_12) === TRUE) {
                        $category = '12h';
                    } elseif (in_array($val, $h_24) === TRUE) {
                        $category = '24h';
                    } elseif (in_array($val, $h_96) === TRUE) {
                        $category = '96h';
                    } elseif (in_array($val, $h_168) === TRUE) {
                        $category = '168h';
                    } elseif (in_array($val, $h_week) === TRUE) {
                        $category = 'week';
                    } 

                    if ($category != '') {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][$category][] = $row['uid'];
                        $data[$mun][$col[0]][$category]['COUNT']++;
                        $data[$mun][$col[0]]['total'][] = $row['uid'];
                        $data[$mun][$col[0]]['total']['COUNT']++;

                        $data[$mun]['Sub Total'][$category][] = $row['uid'];
                        $data[$mun]['Sub Total'][$category]['COUNT']++;
                        $data[$mun]['Sub Total']['total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['total']['COUNT']++;
                        
                        $col_total['Total']['Total'][$category][] = $row['uid'];
                        $col_total['Total']['Total'][$category]['COUNT']++;
                        $col_total['Total']['Total']['total'][] = $row['uid'];
                        $col_total['Total']['Total']['total']['COUNT']++;
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