<?php 

/**
* 4.3-12 Place of Employment of Female Household Head
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 21
*/
class Class_4_3_12
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]' AND family_head_gender = 'Female'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('brgy', 'mun', 'prov', 'out', 'Total');

        foreach ($tbl_cols as $col) {
            $col_total['Total']['Total'][$col] = array('COUNT' => 0);
        }


        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $col) {
                $data[$mun]['Sub Total'][$col] = array('COUNT' => 0);
            }

            foreach ($brgys as $brgy => $brg) {

                foreach ($tbl_cols as $col) {
                    $data[$mun][$brg[0]][$col] = array('COUNT' => 0);
                }
                
                $wildcard = $this->getWildcard($brg[1]);
                $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]' AND family_head_gender = 'Female' AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {

                    $col = '';
                    if ($row['shi_place_employment'] == 'within the BRGY') $col = 'brgy';
                    if ($row['shi_place_employment'] == 'within the MUNICIPALITY') $col = 'mun';
                    if ($row['shi_place_employment'] == 'within the PROVINCE') $col = 'prov';
                    if ($row['shi_place_employment'] == 'OTHER Province') $col = 'out';

                    if ($col != '') {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$brg[0]][$col][] = $row['uid'];
                        $data[$mun][$brg[0]][$col]['COUNT']++;
                        $data[$mun][$brg[0]]['Total'][] = $row['uid'];
                        $data[$mun][$brg[0]]['Total']['COUNT']++;


                        $data[$mun]['Sub Total'][$col][] = $row['uid'];
                        $data[$mun]['Sub Total'][$col]['COUNT']++;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT']++;
                        
                        
                        $col_total['Total']['Total'][$col][] = $row['uid'];
                        $col_total['Total']['Total'][$col]['COUNT']++;
                        $col_total['Total']['Total']['Total'][] = $row['uid'];
                        $col_total['Total']['Total']['Total']['COUNT']++;
                    }
                       
                }
            }
            $sub = $data[$mun]['Sub Total'];
            unset($data[$mun]['Sub Total']);
            $data[$mun]['Sub Total'] = $sub;
        }

        $this->total = $col_total['Total']['Total'];
        return $data;
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