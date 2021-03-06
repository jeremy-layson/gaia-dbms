<?php 

/**
* 4.1-3 Legal PAFs by LGUs
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_1_3
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

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND type='LEGAL'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();

        $this->tbl_cols = $tbl_cols = array('owner_res', 'owner_cibe', 'owner_insti', 'renter', 'land_owner', 'tenant', 'insti_occ', 'caretaker', 'total');

        $append = [];


        foreach ($tbl_cols as $field) $this->total[$field] = array('stay' => [], 'move' => []);

        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $field) $data[$mun]['Sub Total'][$field] = array('stay' => [], 'move' => []);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $field) $data[$mun][$col[0]][$field] = array('stay' => [], 'move' => []);
                
                $wildcard = $this->getWildcard($col[1]);

                $query = "SELECT * FROM survey WHERE type='LEGAL' AND is_deleted = 0 AND `address` LIKE '%" . $mun . "%' AND ($wildcard)";
                if ($mun == "Valenzuela") $query = $query . " AND NOT `address` LIKE '%(Depot)%'";
                $result = $this->db->query($query);


                while ($row = $result->fetch_assoc()) {
                    $extent = $row['extent'];
                    $type = $row['type'];
                    $dp = $row['structure_dp'];
                    $use = $row['structure_use'];

                    $category = '';
                    if (strpos($row['structure_owner'], '(Absentee)') !== FALSE) {
                        // $category = 'absentee';
                    }

                    $displacement = 'none';
                    if ($extent == '< than 20%' || $extent == 'Auxiliary' || $extent == 'Land owner' || $extent == 'Land Owner') {
                        $displacement = 'stay';

                    } elseif ($extent != 'Land Lessee') {
                        $displacement = 'move';
                    }

                    if ($displacement != 'none') {
                        //structure owners
                        if ($category == '') {
                            if ($dp == 'Structure Owner' || $dp == 'Structure owner' || $dp == 'Co-owner' || $dp == 'Co-Owner' || $dp == 'Auxiliary') {
                                $category = 'owner_';
                            } elseif ($dp == 'Structure Renter') {
                                $category = 'renter';
                            } elseif ($dp == 'Land Owner') {
                                $category = 'land_owner';
                                $displacement = 'stay';
                            } elseif ($dp == 'Commercial Tenant') {
                                $category = 'tenant';
                            } elseif ($dp == 'Institutional Occupant') {
                                $category = 'insti_occ';
                            } elseif ($dp == 'Caretaker' || $dp == 'Sharer') {
                                $category = 'caretaker';
                            }
                        }

                        if ($category == 'owner_') {
                            if ($use == 'Residential') {
                                $category = $category . 'res';
                            } elseif ($use == 'Institutional') {
                                $category = $category . 'insti';
                            } else {
                                $category = $category . 'cibe';
                            }
                        }
                    }

                    if ($category != '') {
                        unset($this->unclaimed[$row['uid']]);
                        $data[$mun][$col[0]][$category][$displacement][] = $row['uid'];
                        $data[$mun][$col[0]]['total'][$displacement][] = $row['uid'];
                        
                        $data[$mun]['Sub Total'][$category][$displacement][] = $row['uid'];
                        $data[$mun]['Sub Total']['total'][$displacement][] = $row['uid'];
                        
                        $this->total[$category][$displacement][] = $row['uid'];
                        $this->total['total'][$displacement][] = $row['uid'];
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