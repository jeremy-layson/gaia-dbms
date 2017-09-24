<?php 

/**
* 4.3 Number of PAFs and PAPs by Type of Loss
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 18
*/
class Class_4_3
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

        $this->tbl_cols = $tbl_cols = array('LEGAL_RELOC', 'LEGAL_STAY', 'LEGAL_TOTAL', 'ISF_RELOC', 'ISF_STAY', 'ISF_TOTAL', 'TOTAL_RELOC', 'TOTAL_STAY', 'TOTAL_TOTAL');

        $tbl_rows = array('owner_res', 'owner_cibe', 'owner_mixed', 'land_owner', 'tenant', 'renter', 'wage_earner', 'absentee');
        
        $this->definition = array(
            'owner_res' => 'Structure Owners (Residential)', 
            'owner_cibe' => 'Structure Owners (CIBEs)', 
            'owner_mixed' => 'Structure Owners (Mixed Use)', 
            'land_owner' => 'Land Owners', 
            'tenant' => 'Tenant Farmers', 
            'renter' => 'Renters of Residential Structure', 
            'wage_earner' => 'Wage Earners (Employees of CIBEs)', 
            'absentee' => 'Absentee Structure Owners'
        );
        $append = [];


        foreach ($tbl_cols as $field) $this->total['PAP'][$field] = array('COUNT' => 0);
        foreach ($tbl_cols as $field) $this->total['PAF'][$field] = array('COUNT' => 0);
        
        foreach ($tbl_rows as $row) {
            foreach ($tbl_cols as $field) $data[$row]['PAP'][$field] = array('COUNT' => 0);
            foreach ($tbl_cols as $field) $data[$row]['PAF'][$field] = array('COUNT' => 0);
        }
        
        
        $query = "SELECT * FROM survey WHERE is_deleted = 0";
        $result = $this->db->query($query);


        while ($row = $result->fetch_assoc()) {
            $row_category = '';

            $type = $row['type'];
            $reloc = 'RELOC';
            if (trim($row['extent']) == '< than 20%') $reloc = 'STAY';

            $extent = $row['extent'];
            $type = $row['type'];
            $dp = trim($row['structure_dp']);
            $use = trim($row['structure_use']);

            $category = '';
            if (strpos($row['structure_owner'], '(Absentee)') !== FALSE) {
                $category = 'absentee';
            }           

            $reloc = strtoupper(trim($row['displacement']));

            if (strpos($reloc, "STAY") != FALSE) {
                $reloc = 'STAY';
            }
            if (strpos($reloc, "DISPLACEMENT") != FALSE) {
                $reloc = 'RELOC';
            }

            if ($reloc == 'RELOC' || $reloc == 'STAY') {
                //structure owners
                if ($category == '') {
                    if ($dp == 'Structure Owner' || $dp == 'Structure owner') {
                        $category = 'owner_';
                    } elseif ($dp == 'Structure Renter') {
                        $category = 'renter';
                    } elseif ($dp == 'Land Owner') {
                        $category = 'land_owner';
                    } elseif ($dp == 'Commercial Tenant') {
                        // $category = 'tenant';
                    }
                }

                if ($category == 'owner_') {
                    if ($use == 'Residential') {
                        $category = $category . 'res';
                    } elseif ($use == 'Mixed Use' || $use == 'Mixed use') {
                        $category = $category . 'mixed';
                    } else {
                        $category = $category . 'cibe';
                    }
                }
            }

            //FOR LAND OWNERS
            if (trim(strtoupper($row['dp_type'])) == 'LAND OWNER') {
                $category = 'land_owner';

                if (trim(strtoupper($row['alo_extent'])) == '< THAN 20%') {
                    $reloc = 'STAY';
                } else {
                    $reloc = 'RELOC';
                }
            }

            $row_category = $category;

            if ($row_category != '' && ($reloc == 'STAY' || $reloc == 'RELOC')) {
                unset($this->unclaimed[$row['uid']]);
                $data[$row_category]['PAF'][$type . "_" . $reloc][] = $row['uid'];
                $data[$row_category]['PAP'][$type . "_" . $reloc][] = $row['uid'];
                
                $data[$row_category]['PAF'][$type . "_" . $reloc]['COUNT']++;
                $data[$row_category]['PAP'][$type . "_" . $reloc]['COUNT'] += intval($row['hh_members']);
                
                $data[$row_category]['PAF'][$type . "_TOTAL"][] = $row['uid'];
                $data[$row_category]['PAP'][$type . "_TOTAL"][] = $row['uid'];
                
                $data[$row_category]['PAF'][$type . "_TOTAL"]['COUNT']++;
                $data[$row_category]['PAP'][$type . "_TOTAL"]['COUNT'] += intval($row['hh_members']);
                
                $data[$row_category]['PAF']["TOTAL_" . $reloc][] = $row['uid'];
                $data[$row_category]['PAP']["TOTAL_" . $reloc][] = $row['uid'];
                
                $data[$row_category]['PAP']["TOTAL_TOTAL"][] = $row['uid'];
                $data[$row_category]['PAF']["TOTAL_TOTAL"][] = $row['uid'];
                
                $data[$row_category]['PAF']["TOTAL_" . $reloc]['COUNT']++;
                $data[$row_category]['PAP']["TOTAL_" . $reloc]['COUNT'] += intval($row['hh_members']);
                
                $data[$row_category]['PAF']["TOTAL_TOTAL"]['COUNT']++;
                $data[$row_category]['PAP']["TOTAL_TOTAL"]['COUNT'] += intval($row['hh_members']);
                
                $this->total['PAF'][$type . "_" . $reloc][] = $row['uid'];
                $this->total['PAP'][$type . "_" . $reloc][] = $row['uid'];
                
                $this->total['PAF'][$type . "_" . $reloc]['COUNT']++;
                $this->total['PAP'][$type . "_" . $reloc]['COUNT'] += intval($row['hh_members']);
                
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