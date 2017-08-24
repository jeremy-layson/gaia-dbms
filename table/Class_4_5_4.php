<?php 

/**
* 4.5-4 Preference for Cash Compensation or Relocation Package
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 24
*/
class Class_4_5_4
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]' AND extent != '< than 20%'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('reloc', 'cash', 'undecided', 'Total');

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
                $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0  AND extent != '< than 20%' AND `hh_head` LIKE '%[322]' AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    $relocation = strtoupper($row['rpo_relocation_option']);

                    $col = [];

                    if ($relocation == '') $col['undecided'] = TRUE;
                    if (strpos($relocation, "BALIK PROBINSYA") !== FALSE) $col['reloc'] = TRUE;
                    if (strpos($relocation, "BALIK PROBINSIYA") !== FALSE) $col['reloc'] = TRUE;
                    if (strpos($relocation, "RELOCATION") !== FALSE) $col['reloc'] = TRUE;

                    if (strpos($relocation, "UNDECIDED") !== FALSE) $col['undecided'] = TRUE;
                    if (strpos($relocation, "NO ANSWER") !== FALSE) $col['undecided'] = TRUE;

                    if (strpos($relocation, "CASH COMPENSATION") !== FALSE) $col['cash'] = TRUE;
                    if (strpos($relocation, "CASH  COMPENSATION") !== FALSE) $col['cash'] = TRUE;
                    if (strpos($relocation, "BUSINESS CAPITAL") !== FALSE) $col['cash'] = TRUE;
                    if (strpos($relocation, "JUST COMPENSATION") !== FALSE) $col['cash'] = TRUE;
                    
                    
                    foreach ($col as $colKey => $field) {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$brg[0]][$colKey][] = $row['uid'];
                        $data[$mun][$brg[0]][$colKey]['COUNT']++;
                        $data[$mun][$brg[0]]['Total'][] = $row['uid'];
                        $data[$mun][$brg[0]]['Total']['COUNT']++;


                        $data[$mun]['Sub Total'][$colKey][] = $row['uid'];
                        $data[$mun]['Sub Total'][$colKey]['COUNT']++;
                        $data[$mun]['Sub Total']['Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['Total']['COUNT']++;
                        
                        
                        $col_total['Total']['Total'][$colKey][] = $row['uid'];
                        $col_total['Total']['Total'][$colKey]['COUNT']++;
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