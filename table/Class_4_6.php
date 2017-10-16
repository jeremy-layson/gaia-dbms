<?php 

/**
* 4.6 Number of ISFs per LGU by Type of Loss
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09 . 17
*/
class Class_4_6
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

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND type='ISF'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        // unset($columns['Valenzuela (Depot)']);

        $this->tbl_cols = $tbl_cols = array('owner_res', 'owner_cibe', 'renter', 'tenants', 'vendors', 'coowner', 'sharer', 'insti', 'tenant', 'caretaker','total');

        $append = [];


        foreach ($tbl_cols as $field) $this->total[$field] = array('stay' => [], 'move' => []);

        foreach ($columns as $mun => $brgys) {

            foreach ($tbl_cols as $field) $data[$mun][$field] = array('stay' => [], 'move' => []);
            
            $query = "SELECT * FROM survey WHERE type='ISF' AND is_deleted = 0 AND `address` LIKE '%" . $mun . "%' ";
            if ($mun == "Valenzuela") $query =  $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);

            while ($row = $result->fetch_assoc()) {
                $extent = $row['extent'];
                $type = $row['type'];
                $dp = $row['structure_dp'];
                $use = $row['structure_use'];

                $category = '';

                $displacement = strtoupper(trim($row['extent']));

                if ($displacement == '< THAN 20%' || $displacement == '< 20%' || $displacement == 'AUXILIARY') {
                    $displacement = 'stay';
                } else {
                    $displacement = 'move';
                }

                if ($displacement == 'move' || $displacement == 'stay') {
                    //structure owners
                    if ($category == '') {
                        if ($dp == 'Structure Owner' || $dp == 'Structure owner' || $dp == 'Absentee Structure Owner' || $dp == 'Absentee Structure owner') {
                            $category = 'owner_';
                        } elseif ($dp == 'Structure Renter' || $dp == 'structure renter' || $dp == 'Structure renter') {
                            $category = 'renter';
                        } elseif ($dp == 'Commercial Tenant') {
                            $category = 'tenants';
                        } elseif ($dp == 'Co-owner' || $dp == 'Co-Owner') {
                            $category = 'coowner';
                        } elseif ($dp == 'Sharer') {
                            $category = 'sharer';
                        } elseif ($dp == 'Institutional Occupant' || $dp == 'Institutional occupant') {
                            $category = 'insti';
                        } elseif ($dp == 'Commercial Tenant') {
                            $category = 'tenant';
                        } elseif ($dp == 'Caretaker') {
                            $category = 'caretaker';
                        } 
                    }

                    if ($category == 'owner_') {
                        if ($use == 'Residential' || $use == 'residential') {
                            $category = $category . 'res';
                        } else {
                            $category = $category . 'cibe';
                        }
                    }
                }

                //FOR LAND OWNERS
                if (trim(strtoupper($row['dp_type'])) == 'LAND OWNER') {
                    $category = 'land_owner';

                    if (trim(strtoupper($row['alo_extent'])) == '< THAN 20%') {
                        $displacement = 'stay';
                    } else {
                        $displacement = 'move';
                    }
                }
                
                if ($category != '' && ($displacement == 'move' || $displacement == 'stay')) {
                    unset($this->unclaimed[$row['uid']]);
                    $data[$mun][$category][$displacement][] = $row['uid'];
                    $data[$mun]['total'][$displacement][] = $row['uid'];
                    
                    $this->total[$category][$displacement][] = $row['uid'];
                    $this->total['total'][$displacement][] = $row['uid'];
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