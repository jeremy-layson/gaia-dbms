<?php 

/**
* 4.1-4 ISFs by LGUs
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 23
*/
class Class_4_1_4
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

        $this->tbl_cols = $tbl_cols = array('owner_res', 'owner_cibe', 'renter', 'coowner', 'sharer', 'insti', 'tenant', 'caretaker', 'total');

        $append = [];


        foreach ($tbl_cols as $field) $this->total[$field] = array('stay' => [], 'move' => []);

        foreach ($columns as $mun => $brgys) {
            foreach ($tbl_cols as $field) $data[$mun]['Sub Total'][$field] = array('stay' => [], 'move' => []);

            foreach ($brgys as $brgy => $col) {
                foreach ($tbl_cols as $field) $data[$mun][$col[0]][$field] = array('stay' => [], 'move' => []);
                
                $wildcard = $this->getWildcard($col[1]);
                $result = $this->db->query($query = "SELECT * FROM survey WHERE type='ISF' AND is_deleted = 0 AND `address` LIKE '%" . $mun . "%' AND ($wildcard)");
                while ($row = $result->fetch_assoc()) {
                    $extent = $row['extent'];
                    $type = $row['type'];
                    $dp = $row['structure_dp'];
                    $use = $row['structure_use'];

                    $category = '';

                    $displacement = 'none';
                    if ($extent == '< than 20%' || $extent == 'Auxiliary') {
                        $displacement = 'stay';

                    } elseif ($extent != 'Land Lessee' && $extent != 'Land owner' && $extent != 'Land Owner') {
                        $displacement = 'move';
                    }

                    if ($displacement != 'none') {
                        //structure owners
                        if ($category == '') {
                            if ($dp == 'Structure Owner' || $dp == 'Structure owner') {
                                $category = 'owner_';
                            } elseif ($dp == 'Structure Renter' || $dp == 'Structure renter' || $dp == 'structure renter') {
                                $category = 'renter';
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