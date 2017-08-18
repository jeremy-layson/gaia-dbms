<?php 

/**
* 4.3-2 Size of Household
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_3_2
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

        $col_total['Total']['Total']['3'] = array('COUNT' => 0);
        $col_total['Total']['Total']['6'] = array('COUNT' => 0);
        $col_total['Total']['Total']['9'] = array('COUNT' => 0);
        $col_total['Total']['Total']['else'] = array('COUNT' => 0);
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);


        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['3'] = array('COUNT' => 0); //0-3
            $data[$mun]['Sub Total']['6'] = array('COUNT' => 0); //4-6
            $data[$mun]['Sub Total']['9'] = array('COUNT' => 0); //7-9
            $data[$mun]['Sub Total']['else'] = array('COUNT' => 0); // >9
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['3'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['6'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['9'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['else'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,hh_members FROM survey WHERE `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    unset($this->unclaimed[$row['uid']]);
                    
                    $hh = intval($row['hh_members']);

                    if ($hh <= 3) {
                        $hh = '3';
                    } elseif ($hh <= 6) {
                        $hh = '6';
                    } elseif ($hh <= 9) {
                        $hh = '9';
                    } else {
                        $hh = 'else';
                    }

                    $data[$mun][$col[0]][$hh][] = $row['uid'];
                    $data[$mun][$col[0]][$hh]['COUNT']++;
                    $data[$mun][$col[0]]['Total'][] = $row['uid'];
                    $data[$mun][$col[0]]['Total']['COUNT']++;

                    $data[$mun]['Sub Total'][$hh][] = $row['uid'];
                    $data[$mun]['Sub Total'][$hh]['COUNT']++;
                    $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                    $data[$mun]['Sub Total']['Total']['COUNT']++;
                    
                    $col_total['Total']['Total'][$hh][] = $row['uid'];
                    $col_total['Total']['Total'][$hh]['COUNT']++;
                    $col_total['Total']['Total']['Total'][] = $row['uid'];
                    $col_total['Total']['Total']['Total']['COUNT']++;
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