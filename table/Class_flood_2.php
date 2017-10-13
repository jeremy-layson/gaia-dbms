<?php 

/**
* Table 2 Highest Flooding Experienced for the last 5 Years
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 31
*/
class Class_flood_2
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
        $this->tbl_cols = $tbl_cols = array('yearly', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', 'noans', 'total');
        
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
                $result = $this->db->query($query = "SELECT uid,address,baranggay,flood_deepest FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard) AND (flood_5years = 'Y' OR flood_5years = 'y')");
                while ($row = $result->fetch_assoc()) {
                    
                    
                    $val = strtoupper($row['flood_deepest']);
                    $val = explode("/", $val);
                    $val = trim($val[0]); //get first answer only
                    $val = explode(",", $val);
                    $val = trim($val[0]); //get first answer only


                    if ($val == '20114') $val = '2014';

                    $category = '';
                    if ($val == '' || $val == 'NO IDEA') {
                        $category = 'noans';
                    } elseif ($val == 'EVERY YEAR' || $val == 'YEARLY') {
                        $category = 'yearly';
                    } else {
                        if (in_array($val, $tbl_cols) === TRUE) {
                            $category = $val;
                        }
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