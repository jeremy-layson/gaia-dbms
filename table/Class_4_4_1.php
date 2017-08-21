<?php 

/**
* 4.4-1 Gender of Household Head
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 19
*/
class Class_4_4_1
{
    private $db;
    public $unclaimed;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE hh_head LIKE '%[322]'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();

        $col_total['Total']['Total']['male'] = array('COUNT' => 0);
        $col_total['Total']['Total']['female'] = array('COUNT' => 0);
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['male'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['female'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['male'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['female'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,family_head_gender FROM survey WHERE `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {

                    $gender = strtolower($row['family_head_gender']);

                    if ($gender == 'male' OR $gender == 'female') {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][$gender][] = $row['uid'];
                        $data[$mun][$col[0]][$gender]['COUNT']++;
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['Total']['COUNT']++;

                        $data[$mun]['Sub Total'][$gender][] = $row['uid'];
                        $data[$mun]['Sub Total'][$gender]['COUNT']++;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT']++;
                        
                        $col_total['Total']['Total'][$gender][] = $row['uid'];
                        $col_total['Total']['Total'][$gender]['COUNT']++;
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