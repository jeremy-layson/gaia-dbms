<?php 

/**
* 4.2 Number of PAFs and PAPs by LGUs
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 17
*/
class Class_4_2
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

        $this->tbl_cols = $tbl_cols = array('LEGAL_RELOC', 'LEGAL_STAY', 'LEGAL_TOTAL', 'ISF_RELOC', 'ISF_STAY', 'ISF_TOTAL', 'TOTAL_RELOC', 'TOTAL_STAY', 'TOTAL_TOTAL');
        
        $append = [];


        foreach ($tbl_cols as $field) $this->total['PAP'][$field] = array('COUNT' => 0);
        foreach ($tbl_cols as $field) $this->total['PAF'][$field] = array('COUNT' => 0);
        
        foreach ($columns as $mun => $brgys) {

            foreach ($tbl_cols as $field) $data[$mun]['PAP'][$field] = array('COUNT' => 0);
            foreach ($tbl_cols as $field) $data[$mun]['PAF'][$field] = array('COUNT' => 0);
            
            
            $query = "SELECT * FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $mun . "%' ";
            if ($mun == "Valenzuela") $query = $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);


            while ($row = $result->fetch_assoc()) {
                $reloc = 'RELOC';
                $type = $row['type'];
                if (trim($row['extent']) == '< than 20%') $reloc = 'STAY';

                unset($this->unclaimed[$row['uid']]);
                $data[$mun]['PAF'][$type . "_" . $reloc][] = $row['uid'];
                $data[$mun]['PAP'][$type . "_" . $reloc][] = $row['uid'];
                
                $data[$mun]['PAF'][$type . "_" . $reloc]['COUNT']++;
                $data[$mun]['PAP'][$type . "_" . $reloc]['COUNT'] += intval($row['hh_members']);
                
                $data[$mun]['PAF'][$type . "_TOTAL"][] = $row['uid'];
                $data[$mun]['PAP'][$type . "_TOTAL"][] = $row['uid'];
                
                $data[$mun]['PAF'][$type . "_TOTAL"]['COUNT']++;
                $data[$mun]['PAP'][$type . "_TOTAL"]['COUNT'] += intval($row['hh_members']);
                
                $data[$mun]['PAF']["TOTAL_" . $reloc][] = $row['uid'];
                $data[$mun]['PAP']["TOTAL_" . $reloc][] = $row['uid'];
                
                $data[$mun]['PAP']["TOTAL_TOTAL"][] = $row['uid'];
                $data[$mun]['PAF']["TOTAL_TOTAL"][] = $row['uid'];
                
                $data[$mun]['PAF']["TOTAL_" . $reloc]['COUNT']++;
                $data[$mun]['PAP']["TOTAL_" . $reloc]['COUNT'] += intval($row['hh_members']);
                
                $data[$mun]['PAF']["TOTAL_TOTAL"]['COUNT']++;
                $data[$mun]['PAP']["TOTAL_TOTAL"]['COUNT'] += intval($row['hh_members']);
                
                $this->total['PAF'][$type . "_" . $reloc][] = $row['uid'];
                $this->total['PAP'][$type . "_" . $reloc][] = $row['uid'];
                
                $this->total['PAF'][$type . "_" . $reloc]['COUNT']++;
                $this->total['PAP'][$type . "_" . $reloc]['COUNT'] += intval($row['hh_members']);

                $this->total['PAF'][$type . "_TOTAL"][] = $row['uid'];
                $this->total['PAP'][$type . "_TOTAL"][] = $row['uid'];
                
                $this->total['PAF'][$type . "_TOTAL"]['COUNT']++;
                $this->total['PAP'][$type . "_TOTAL"]['COUNT'] += intval($row['hh_members']);
                
                $this->total['PAF']["TOTAL_" . $reloc][] = $row['uid'];
                $this->total['PAP']["TOTAL_" . $reloc][] = $row['uid'];
                
                $this->total['PAP']["TOTAL_TOTAL"][] = $row['uid'];
                $this->total['PAF']["TOTAL_TOTAL"][] = $row['uid'];
                
                $this->total['PAF']["TOTAL_" . $reloc]['COUNT']++;
                $this->total['PAP']["TOTAL_" . $reloc]['COUNT'] += intval($row['hh_members']);
                
                $this->total['PAF']["TOTAL_TOTAL"]['COUNT']++;
                $this->total['PAP']["TOTAL_TOTAL"]['COUNT'] += intval($row['hh_members']);
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