<?php 

/**
* 4.5 Number of Legal PAFs at Valenzuela Depot by Type of Loss 
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 17
*/
class Class_4_5
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

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND type='LEGAL' AND `address` LIKE '%(Depot)%'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        // $columns = $this->getMunicipality();
        $columns = array('Valenzuela (Depot)' => 0);

        $this->tbl_cols = $tbl_cols = array('land_owner', 'owner_res', 'owner_mixed', 'owner_cibe', 'renter', 'absentee', 'workers', 'total');

        $append = [];


        foreach ($tbl_cols as $field) $this->total[$field] = array('stay' => [], 'move' => [], 'total' => []);

        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $field) $data[$mun][$field] = array('stay' => [], 'move' => [], 'total' => []);
                

            $query = "SELECT * FROM survey WHERE type='LEGAL' AND is_deleted = 0 AND `address` LIKE '%" . $mun . "%' ";
            if ($mun == "Valenzuela") $query = $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);


            while ($row = $result->fetch_assoc()) {
                $extent = $row['extent'];
                $type = $row['type'];
                $dp = $row['structure_dp'];
                $use = $row['structure_use'];

                $category = '';
                if (strpos($row['structure_owner'], '(Absentee)') !== FALSE) {
                    $category = 'absentee';
                }

                $displacement = 'none';
                if ($extent == '< than 20%') {
                    $displacement = 'stay';

                } elseif ($extent != 'Land Lessee' && $extent != 'Auxiliary' && $extent != 'Land owner' && $extent != 'Land Owner') {
                    $displacement = 'move';
                }

                if ($displacement != 'none') {
                    //structure owners
                    if ($category == '') {
                        if ($dp == 'Structure Owner' || $dp == 'Structure owner') {
                            $category = 'owner_';
                        } elseif ($dp == 'Structure Renter') {
                            $category = 'renter';
                        } elseif ($dp == 'Land Owner') {
                            $category = 'land_owner';
                        } elseif ($dp == 'Commercial Tenant') {
                            $category = 'tenant';
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

                if ($category != '') {
                    unset($this->unclaimed[$row['uid']]);
                    $data[$mun][$category][$displacement][] = $row['uid'];
                    $data[$mun][$category]['total'][] = $row['uid'];
                    
                    $data[$mun]['total'][$displacement][] = $row['uid'];
                    $data[$mun]['total']['total'][] = $row['uid'];
                    
                    $this->total[$category][$displacement][] = $row['uid'];
                    $this->total[$category]['total'][] = $row['uid'];

                    $this->total['total'][$displacement][] = $row['uid'];
                    $this->total['total']['total'][] = $row['uid'];
                       
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