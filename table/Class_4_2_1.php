<?php 

/**
* 4.2-1 Affected Lands: Area (m2)
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_2_1
{
    private $db;
    public $unclaimed;
    public $total;

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

        $col_total['RESIDENTIAL'] = array('COUNT' => 0);
        $col_total['COMMERCIAL'] = array('COUNT' => 0);
        $col_total['AGRICULTURAL'] = array('COUNT' => 0);
        $col_total['INDUSTRIAL'] = array('COUNT' => 0);
        $col_total['INSTITUTIONAL'] = array('COUNT' => 0);
        $col_total['MIXED USE'] = array('COUNT' => 0);
        $col_total['Total'] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            $data[$mun]['Sub Total']['RESIDENTIAL'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['COMMERCIAL'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['AGRICULTURAL'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['INDUSTRIAL'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['INSTITUTIONAL'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['MIXED USE'] = array('COUNT' => 0);
            $data[$mun]['Sub Total']['Total'] = array('COUNT' => 0);
            foreach ($brgys as $brgy => $col) {
                $data[$mun][$col[0]]['RESIDENTIAL'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['COMMERCIAL'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['AGRICULTURAL'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['INDUSTRIAL'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['INSTITUTIONAL'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['MIXED USE'] = array('COUNT' => 0);
                $data[$mun][$col[0]]['Total'] = array('COUNT' => 0);
                $wildcard = $this->getWildcard($col[1]);
                if ($mun == 'Valenzuela') {
                    $result = $this->db->query("SELECT uid,`use`,alo_affectedarea FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $mun . "%' AND NOT `address` LIKE '%(Depot)%' AND ($wildcard)");
                } else {
                    $result = $this->db->query("SELECT uid,`use`,alo_affectedarea FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                }
                
                while ($row = $result->fetch_assoc()) {
                    if (isset($data[$mun][$col[0]][strtoupper($row['use'])]) === TRUE) {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][strtoupper($row['use'])]['COUNT'] += floatval($row['alo_affectedarea']);
                        $data[$mun]['Sub Total'][strtoupper($row['use'])]['COUNT'] += floatval($row['alo_affectedarea']);
                        
                        $col_total[strtoupper($row['use'])]['COUNT'] += floatval($row['alo_affectedarea']);
                        $col_total['Total']['COUNT'] += floatval($row['alo_affectedarea']);
                        
                        $data[$mun][$col[0]]['Total']['COUNT'] += floatval($row['alo_affectedarea']);

                        $data[$mun][$col[0]][strtoupper($row['use'])][] = $row['uid'];
                        $data[$mun]['Sub Total'][strtoupper($row['use'])][] = $row['uid'];
                        
                        $col_total[strtoupper($row['use'])][] = $row['uid'];
                        $col_total['Total'][] = $row['uid'];
                        
                        $data[$mun][$col[0]]['Total'][] = $row['uid'];
                        
                    }
                    
                }
            }
            $sub = $data[$mun]['Sub Total'];
            unset($data[$mun]['Sub Total']);
            $data[$mun]['Sub Total'] = $sub;
        }

        $this->total = $col_total;
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