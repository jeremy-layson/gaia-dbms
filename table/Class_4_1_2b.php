<?php 

/**
* 4.1-2b Number of Affected PAFs and PAPs by LGUs
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 11
*/
class Class_4_1_2b
{
    private $db;
    public $unclaimed;
    public $total;
    public $tbl_cols;
    public $definition;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();

        $this->tbl_cols = $tbl_cols = array('PAF_LEGAL', 'PAF_ISF', 'PAF_Total', 'PAP_LEGAL', 'PAP_ISF', 'PAP_Total');

        $append = [];


        foreach ($tbl_cols as $field) $this->total[$field] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $field) $data[$mun]['Sub Total'][$field] = array('COUNT' => 0);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $field) $data[$mun][$col[0]][$field] = array('COUNT' => 0);
                
                $wildcard = $this->getWildcard($col[1]);

                $query = "SELECT * FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $mun . "%' AND ($wildcard)";
                if ($mun == "Valenzuela") $query = $query . " AND NOT `address` LIKE '%(Depot)%'";
                $result = $this->db->query($query);


                while ($row = $result->fetch_assoc()) {
                    $category = '_' . $row['type'];

                    if ($category != '') {
                        unset($this->unclaimed[$row['uid']]);

                        $data[$mun][$col[0]]['PAF' . $category][] = $row['uid'];
                        $data[$mun][$col[0]]['PAF' . $category]['COUNT']++;
                        $data[$mun][$col[0]]['PAF_Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['PAF_Total']['COUNT']++;


                        $data[$mun][$col[0]]['PAP' . $category][] = $row['uid'];
                        $data[$mun][$col[0]]['PAP' . $category]['COUNT'] += intval($row['hh_members']);
                        $data[$mun][$col[0]]['PAP_Total'][] = $row['uid'];
                        $data[$mun][$col[0]]['PAP_Total']['COUNT'] += intval($row['hh_members']);
                        
                        
                        $data[$mun]['Sub Total']['PAF' . $category][] = $row['uid'];
                        $data[$mun]['Sub Total']['PAF' . $category]['COUNT']++;
                        $data[$mun]['Sub Total']['PAF_Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['PAF_Total']['COUNT']++;


                        $data[$mun]['Sub Total']['PAP' . $category][] = $row['uid'];
                        $data[$mun]['Sub Total']['PAP' . $category]['COUNT'] += intval($row['hh_members']);
                        $data[$mun]['Sub Total']['PAP_Total'][] = $row['uid'];
                        $data[$mun]['Sub Total']['PAP_Total']['COUNT'] += intval($row['hh_members']);
                        
                        $this->total['PAF' . $category][] = $row['uid'];
                        $this->total['PAF' . $category]['COUNT']++;
                        $this->total['PAF_Total'][] = $row['uid'];
                        $this->total['PAF_Total']['COUNT']++;


                        $this->total['PAP' . $category][] = $row['uid'];
                        $this->total['PAP' . $category]['COUNT'] += intval($row['hh_members']);
                        $this->total['PAP_Total'][] = $row['uid'];
                        $this->total['PAP_Total']['COUNT'] += intval($row['hh_members']);
                    }
                }
            }
            $sub = $data[$mun]['Sub Total'];
            unset($data[$mun]['Sub Total']);
            $data[$mun]['Sub Total'] = $sub;
        }

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