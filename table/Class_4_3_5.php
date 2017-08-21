<?php 

/**
* 4.3-5 Length of Stay in Present Place
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_3_5
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

        $col_total['Total']['Total']['less'] = array('COUNT' => 0);
        $col_total['Total']['Total']['110yrs'] = array('COUNT' => 0);
        $col_total['Total']['Total']['else'] = array('COUNT' => 0);
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);


        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['less'] = array('COUNT' => 0); //4-6
            $data[$mun]['Sub Total']['110yrs'] = array('COUNT' => 0); //7-9
            $data[$mun]['Sub Total']['else'] = array('COUNT' => 0); // >9
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['less'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['110yrs'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['else'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,hdi_length_stay FROM survey WHERE `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    
                    $hh = strtoupper($row['hdi_length_stay']);

                    if ($hh == '1-3 YEARS' || $hh == '3-6YEARS' || $hh == '3-6 YEARS' || $hh == '6-10 YEARS') {
                        $hh = '110yrs';
                    }

                    if ($hh == '10-15 YEARS' || $hh == 'MORE THAN 15 YEARS') {
                        $hh = 'else';
                    }

                    if ($hh == 'LESS THAN ONE YEAR') {
                        $hh = 'less';
                    }

                    if ($hh == '110yrs' || $hh == 'else' || $hh =='less') {
                        unset($this->unclaimed[$row['uid']]);
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