<?php 

/**
* 4.3-4 Educational Attainment of Household Members
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_3_4
{
    private $db;
    public $unclaimed;
    public $tbl_cols;

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
        $this->tbl_cols = $tbl_cols = array(
            'ses_ed_none',
            'ses_ed_pre',
            'ses_ed_elem',
            'ses_ed_elemgrad',
            'ses_ed_hs',
            'ses_ed_hsgrad',
            'ses_ed_college',
            'ses_ed_collegegrad',
            'ses_ed_voc',
            'ses_ed_vocgrad', 
            'ses_ed_notage',
            'ses_ed_other',
            'ses_ed_total');

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
                $result = $this->db->query($query = "SELECT * FROM survey WHERE `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    unset($this->unclaimed[$row['uid']]);

                    foreach ($tbl_cols as $col) {
                        if ($col != 'ses_ed_total') {

                            $data[$mun][$brg[0]][$col][] = $row['uid'];
                            $data[$mun][$brg[0]][$col]['COUNT'] += (intval($row[$col . '_male']) + intval($row[$col . '_female']));
                            $data[$mun][$brg[0]]['ses_ed_total'][] = $row['uid'];
                            $data[$mun][$brg[0]]['ses_ed_total']['COUNT'] += (intval($row[$col . '_male']) + intval($row[$col . '_female']));


                            $data[$mun]['Sub Total'][$col][] = $row['uid'];
                            $data[$mun]['Sub Total'][$col]['COUNT'] += (intval($row[$col . '_male']) + intval($row[$col . '_female']));
                            $data[$mun]['Sub Total']['ses_ed_total'][] = $row['uid'];
                            $data[$mun]['Sub Total']['ses_ed_total']['COUNT'] += (intval($row[$col . '_male']) + intval($row[$col . '_female']));

                            $col_total['Total']['Total'][$col][] = $row['uid'];
                            $col_total['Total']['Total'][$col]['COUNT'] += (intval($row[$col . '_male']) + intval($row[$col . '_female']));
                            $col_total['Total']['Total']['ses_ed_total'][] = $row['uid'];
                            $col_total['Total']['Total']['ses_ed_total']['COUNT'] += (intval($row[$col . '_male']) + intval($row[$col . '_female']));

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