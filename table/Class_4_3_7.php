<?php 

/**
* 4.3-7 Employment Status and Source of Income
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 24
*/
class Class_4_3_7
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

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
        $this->tbl_cols = $tbl_cols = array('em_perma', 'em_contract', 'em_temp', 'formal', 'informal', 'unemployed', 'total');
        
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
                $result = $this->db->query($query = "SELECT uid,address,baranggay,shi_source_employee,shi_source_fbusiness,shi_source_informal,shi_employ_permanent,shi_employ_contract,shi_employ_extra FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    unset($this->unclaimed[$row['uid']]);
                    
                    $vals = array('em_perma' => 0, 'em_contract' => 0, 'em_temp' => 0, 'formal' => 0, 'informal' => 0, 'unemployed' => 0);

                    $vals['em_perma'] = intval($row['shi_employ_permanent']);
                    $vals['em_contract'] = intval($row['shi_employ_contract']);
                    $vals['em_temp'] = intval($row['shi_employ_extra']);
                    $vals['formal'] = intval($row['shi_source_fbusiness']);
                    $vals['informal'] = intval($row['shi_source_informal']);

                    foreach ($vals as $vKey => $vValue) {
                        if ($vValue != 0) {
                            $data[$mun][$col[0]][$vKey][] = $row['uid'];
                            $data[$mun][$col[0]][$vKey]['COUNT'] += $vValue;
                            $data[$mun][$col[0]]['total'][] = $row['uid'];
                            $data[$mun][$col[0]]['total']['COUNT'] += $vValue;

                            $data[$mun]['Sub Total'][$vKey][] = $row['uid'];
                            $data[$mun]['Sub Total'][$vKey]['COUNT'] += $vValue;
                            $data[$mun]['Sub Total']['total'][] = $row['uid'];
                            $data[$mun]['Sub Total']['total']['COUNT'] += $vValue;
                            
                            $col_total['Total']['Total'][$vKey][] = $row['uid'];
                            $col_total['Total']['Total'][$vKey]['COUNT'] += $vValue;
                            $col_total['Total']['Total']['total'][] = $row['uid'];
                            $col_total['Total']['Total']['total']['COUNT'] += $vValue;
                        }
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