<?php 

/**
* 4-1-2 Number of PAFs
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 23
*/
class Class_4_1_2
{
    private $db;
    public $unclaimed;
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
        $data = array('displace' => array(
                                    'owner_res'  => [],
                                    'owner_cibe' => [],
                                    'renter'    => [],
                                    'tenant'    => [],
                                    'absentee'   => [],
                                    'subtotal'  => [],
                                        ),
                      'stay'     => array(
                                    'land_owner'   => [],
                                    'owner_res'    => [],
                                    'owner_cibe'  => [],
                                    'renter'       => [],
                                    'absentee'      => [],
                                    'worker'       => [],
                                    'subtotal'  => [],
                                    ));

        $this->definition = array(
            'owner_res' => 'Structure Owners (Residential)',
            'owner_cibe' => 'Structure Owners (CIBEs)',
            'renter' => 'Renters (Residential)',
            'tenant' => 'Commercial Stall Tenants',
            'absentee' => 'Absentee Structure Owners',
            'land_owner' => 'Land Owners',
            'worker' => 'Workers (Employees of CIBEs)',
            'subtotal' => 'Subtotal',
            
        );
        $this->tbl_cols = $tbl_cols = array('PAF_LEGAL', 'PAF_ISF', 'PAF_Total', 'PAP_LEGAL', 'PAP_ISF', 'PAP_Total');

        foreach ($data as $key => $val) {
            foreach ($val as $key2 => $val2) {
                foreach ($tbl_cols as $field) {
                    if (strpos($field, 'PAF') !== FALSE) {
                        $data[$key][$key2][$field] = [];
                    } else {
                        $data[$key][$key2][$field] = 0;
                    }
                }
            }
        }

        $result = $this->db->query($query = "SELECT * FROM survey WHERE is_deleted = 0");
        while ($row = $result->fetch_assoc()) {

            //if < 20% then stay
            $extent = $row['extent'];
            $type = $row['type'];
            $dp = $row['structure_dp'];
            $use = $row['structure_use'];
            $hh_num = $row['hh_members'];
            $category = '';

            if (strpos($row['structure_owner'], '(Absentee)') !== FALSE) {
                $category = 'absentee';
            }

            $displacement = 'none';
            if ($extent == '< than 20%' || $extent == 'Land Owner' || $extent == 'Land owner') {
                $displacement = 'stay';

            } elseif ($extent != 'Land Lessee' && $extent != 'Auxiliary') {
                $displacement = 'displace';
            }

            if ($displacement != 'none') {
                //structure owners
                if ($category == '') {
                    if ($dp == 'Structure Owner' || $dp == 'Structure owner') {
                        $category = 'owner_';
                    } elseif ($dp == 'Structure Renter') {
                        $category = 'renter';
                    } elseif ($dp == 'Land Owner' && $displacement == 'stay') {
                        $category = 'land_owner';
                    } elseif ($dp == 'Commercial Tenant' && $displacement == 'displace') {
                        $category = 'tenant';
                    }
                }

                if ($category == 'owner_') {
                    if ($use == 'Residential') {
                        $category = $category . 'res';
                    } else {
                        $category = $category . 'cibe';
                    }
                }

                if ($category != '') {
                    unset($this->unclaimed[$row['uid']]);

                    $data[$displacement][$category]['PAF_' . $type][] = $row['uid'];
                    $data[$displacement][$category]['PAF_Total'][] = $row['uid'];

                    $data[$displacement]['subtotal']['PAF_' . $type][] = $row['uid'];
                    $data[$displacement]['subtotal']['PAF_Total'][] = $row['uid'];

                    $data[$displacement][$category]['PAP_' . $type] += $hh_num;
                    $data[$displacement][$category]['PAP_Total'] += $hh_num;  
                    $data[$displacement]['subtotal']['PAP_' . $type] += $hh_num;
                    $data[$displacement]['subtotal']['PAP_Total'] += $hh_num;  
                }
            }
        }

        return $data;
    }
}