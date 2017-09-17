<?php 

/**
* 4.3-9b - Monthly Income of ISFs
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 13
*/
class Class_4_3_9c
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]' AND `type` = 'ISF'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('5K', '10K', '15K', '20K', '30K', '50K', 'else');
        
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
                $result = $this->db->query($query = "SELECT uid,address,baranggay,shi_total_hh_income FROM survey WHERE is_deleted = 0 AND `type` = 'ISF' AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    if ($row['shi_total_hh_income'] != '') {
                        unset($this->unclaimed[$row['uid']]);
                        
                        $hh = floatval($row['shi_total_hh_income']);

                        if ($hh < 5000) {
                            $key = '5K';
                        } elseif ($hh <= 10000) {
                            $key = '10K';
                        } elseif ($hh <= 15000) {
                            $key = '15K';
                        } elseif ($hh <= 20000) {
                            $key = '20K';
                        } elseif ($hh <= 30000) {
                            $key = '30K';
                        } elseif ($hh <= 50000) {
                            $key = '50K';
                        } else {
                            $key = 'else';
                        }

                        $data[$mun][$col[0]][$key][] = $row['uid'];
                        $data[$mun][$col[0]][$key]['COUNT']++;
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['Total']['COUNT']++;

                        $data[$mun]['Sub Total'][$key][] = $row['uid'];
                        $data[$mun]['Sub Total'][$key]['COUNT']++;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT']++;
                        
                        $col_total['Total']['Total'][$key][] = $row['uid'];
                        $col_total['Total']['Total'][$key]['COUNT']++;
                        $col_total['Total']['Total']['Total'][] = $row['uid'];
                        $col_total['Total']['Total']['Total']['COUNT']++;
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