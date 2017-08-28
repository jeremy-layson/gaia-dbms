<?php 

/**
* 4.4-3 Vulnerable Groups
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 19
*/
class Class_4_4_3
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

        $col_total['Total']['Total']['sv_10k'] = array('COUNT' => 0);
        $col_total['Total']['Total']['sv_hh_woman'] = array('COUNT' => 0);
        $col_total['Total']['Total']['sv_60above'] = array('COUNT' => 0);
        $col_total['Total']['Total']['sv_special_assist'] = array('COUNT' => 0);
        $col_total['Total']['Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['sv_10k'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['sv_hh_woman'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['sv_60above'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['sv_special_assist'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['sv_10k'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['sv_hh_woman'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['sv_60above'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['sv_special_assist'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT uid,address,baranggay,sv_10k,sv_hh_woman,sv_60above,sv_special_assist FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    unset($this->unclaimed[$row['uid']]);

                    foreach (array('sv_10k','sv_hh_woman','sv_60above','sv_special_assist') as $field) {
                        $toAdd = trim($row[$field]);

                        if ($field == 'sv_10k' || $field == 'sv_hh_woman') {
                            if ($toAdd == 'Y') {
                                $toAdd = 1;
                            } else {
                                $toAdd = 0;
                            }
                        }

                        if ($field == 'sv_60above') {
                            $toAdd = intval($toAdd);
                        }

                        if ($field == 'sv_special_assist') {
                            if (in_array($toAdd, array('1', '2', '3', '4', '5'))) {
                                $toAdd = 1;
                            } else {
                                $toAdd = 0;
                            }
                        }

                        $data[$mun][$col[0]][$field][] = $row['uid'];
                        $data[$mun][$col[0]][$field]['COUNT'] += $toAdd;
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['Total']['COUNT'] += $toAdd;

                        $data[$mun]['Sub Total'][$field][] = $row['uid'];
                        $data[$mun]['Sub Total'][$field]['COUNT'] += $toAdd;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT'] += $toAdd;
                        
                        $col_total['Total']['Total'][$field][] = $row['uid'];
                        $col_total['Total']['Total'][$field]['COUNT'] += $toAdd;
                        $col_total['Total']['Total']['Total'][] = $row['uid'];
                        $col_total['Total']['Total']['Total']['COUNT'] += $toAdd;
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