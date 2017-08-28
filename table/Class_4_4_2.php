<?php 

/**
* 4.4-2 Persons Who Need Special Assistance
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 19
*/
class Class_4_4_2
{
    private $db;
    public $unclaimed;

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

        $col_total['Total']['Total']['1'] = array('COUNT' => 0);
        $col_total['Total']['Total']['2'] = array('COUNT' => 0);
        $col_total['Total']['Total']['3'] = array('COUNT' => 0);
        $col_total['Total']['Total']['4'] = array('COUNT' => 0);
        $col_total['Total']['Total']['5'] = array('COUNT' => 0);
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['1'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['2'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['3'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['4'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['5'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['1'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['2'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['3'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['4'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['5'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,sv_special_assist FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {

                    $assist = strtolower($row['sv_special_assist']);

                    if ($assist == '1' OR $assist == '2' OR $assist == '3' OR $assist == '4' OR $assist == '5') {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][$assist][] = $row['uid'];
                        $data[$mun][$col[0]][$assist]['COUNT']++;
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['Total']['COUNT']++;

                        $data[$mun]['Sub Total'][$assist][] = $row['uid'];
                        $data[$mun]['Sub Total'][$assist]['COUNT']++;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT']++;
                        
                        $col_total['Total']['Total'][$assist][] = $row['uid'];
                        $col_total['Total']['Total'][$assist]['COUNT']++;
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