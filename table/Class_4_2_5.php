<?php 

/**
* 4.2-5 Affected Trees
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_2_5
{
    private $db;
    public $unclaimed;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey`";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();

        $col_total['Grand Total']['Grand Total']['trees_fb'] = array('COUNT' => 0);
        $col_total['Grand Total']['Grand Total']['trees_nonfb'] = array('COUNT' => 0);
        $col_total['Grand Total']['Grand Total']['trees_cash'] = array('COUNT' => 0);
        $col_total['Grand Total']['Grand Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['trees_fb'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['trees_nonfb'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['trees_cash'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);
            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['trees_fb'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['trees_nonfb'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['trees_cash'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,trees_fb,trees_nonfb,trees_cash FROM survey WHERE `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    unset($this->unclaimed[$row['uid']]);
                    
                    foreach (array('trees_fb', 'trees_nonfb', 'trees_cash') as $trees) {
                        $data[$mun][$col[0]][$trees][] = $row['uid'];
                        $data[$mun][$col[0]][$trees]['COUNT'] += intval($row[$trees]);
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['Total']['COUNT'] += intval($row[$trees]);

                        $data[$mun]['Sub Total'][$trees][] = $row['uid'];
                        $data[$mun]['Sub Total'][$trees]['COUNT'] += intval($row[$trees]);
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT'] += intval($row[$trees]);
                        
                        $col_total['Grand Total']['Grand Total'][$trees][] = $row['uid'];
                        $col_total['Grand Total']['Grand Total'][$trees]['COUNT'] += intval($row[$trees]);
                        $col_total['Grand Total']['Grand Total']['Total'][] = $row['uid'];
                        $col_total['Grand Total']['Grand Total']['Total']['COUNT'] += intval($row[$trees]);
                           
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
        $query = "SELECT * FROM municipality";
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