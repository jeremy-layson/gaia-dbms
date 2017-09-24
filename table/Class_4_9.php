<?php 

/**
* 4.9 Area of Affected Private Land by Use
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 24
*/
class Class_4_9
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

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND ownership='Private'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        unset($columns['Valenzuela (Depot)']);

        $this->tbl_cols = $tbl_cols = array('RESIDENTIAL', 'INSTITUTIONAL', 'INDUSTRIAL', 'COMMERCIAL', 'AGRICULTURAL', 'MIXED USE', 'Total');


        foreach ($tbl_cols as $field) $this->total[$field] = array('COUNT' => 0);

        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $field) $data[$mun][$field] = array('COUNT' => 0);
                

            $query = "SELECT * FROM survey WHERE type='LEGAL' AND is_deleted = 0 AND ownership = 'Private' AND `address` LIKE '%" . $mun . "%' ";
            if ($mun == "Valenzuela") $query = $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);


            while ($row = $result->fetch_assoc()) {
                $use = strtoupper($row['use_structure']);
                $sqm = floatval($row['alo_affectedarea']);

                if ($use != '') {
                    unset($this->unclaimed[$row['uid']]);
                    $data[$mun][$use][] = $row['uid'];
                    $data[$mun][$use]['COUNT'] += $sqm;
                       
                    $data[$mun]['Total'][] = $row['uid'];
                    $data[$mun]['Total']['COUNT'] += $sqm;
                    
                    $this->total[$use][] = $row['uid'];
                    $this->total[$use]['COUNT'] += $sqm;
                       
                    $this->total['Total'][] = $row['uid'];
                    $this->total['Total']['COUNT'] += $sqm;
                }
            }
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