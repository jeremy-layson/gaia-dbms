<?php
/**
* 4.2-1 table area affected class
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 13
*/
class AreaAffected
{
    private $db;
    private $displaced;
    private $stay;
    private $excess;

    public function __construct() {
        include('../sql.php');
        $this->db = $link;
        $this->displaced = array(
            'Residential' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'CIBE' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Mixed' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Subtotal' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
        );

        $this->stay = array(
            'Residential' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'CIBE' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Mixed' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Land_Owner' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Tenant' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Wage_Earner' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Absentee' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Institutional' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
            'Subtotal' => array(
                'PAF_Legal'     => 0,
                'PAF_ISF'       => 0,
                'PAF_Total'     => 0,
                'PAP_Legal'     => 0,
                'PAP_ISF'       => 0,
                'PAP_Total'     => 0,
            ),
              
        );

        $this->excess = [];
    }

    public function buildTable() {
        $this->getData();

        //print data here
        //print displaced
        echo '<tr><td colspan="7" style="text-align:center;">Required for displacement</td></tr>';
        foreach ($this->displaced as $key => $value) {
            echo "<tr>";
                echo "<td>" . $this->humanPrint($key) . "</td>";
                echo "<td>" . $value['PAF_Legal'] . "</td>";
                echo "<td>" . $value['PAF_ISF'] . "</td>";
                echo "<td>" . $value['PAF_Total'] . "</td>";
                echo "<td>" . $value['PAP_Legal'] . "</td>";
                echo "<td>" . $value['PAP_ISF'] . "</td>";
                echo "<td>" . $value['PAP_Total'] . "</td>";
            echo "</tr>";
        }
        //print stay

        //print displaced
        echo '<tr><td colspan="7" style="text-align:center;">Not required for displacement</td></tr>';
        foreach ($this->stay as $key => $value) {
            echo "<tr>";
                echo "<td>" . $this->humanPrint($key) . "</td>";
                echo "<td>" . $value['PAF_Legal'] . "</td>";
                echo "<td>" . $value['PAF_ISF'] . "</td>";
                echo "<td>" . $value['PAF_Total'] . "</td>";
                echo "<td>" . $value['PAP_Legal'] . "</td>";
                echo "<td>" . $value['PAP_ISF'] . "</td>";
                echo "<td>" . $value['PAP_Total'] . "</td>";
            echo "</tr>";
        }

        //print grand total
         echo "<tr>";
            echo "<td>Grand Total</td>";
            echo "<td>" . ($this->stay['Subtotal']['PAF_Legal'] + $this->displaced['Subtotal']['PAF_Legal']) . "</td>";
            echo "<td>" . ($this->stay['Subtotal']['PAF_ISF'] + $this->displaced['Subtotal']['PAF_ISF']) . "</td>";
            echo "<td>" . ($this->stay['Subtotal']['PAF_Total'] + $this->displaced['Subtotal']['PAF_Total']) . "</td>";
            echo "<td>" . ($this->stay['Subtotal']['PAP_Legal'] + $this->displaced['Subtotal']['PAP_Legal']) . "</td>";
            echo "<td>" . ($this->stay['Subtotal']['PAP_ISF'] + $this->displaced['Subtotal']['PAP_ISF']) . "</td>";
            echo "<td>" . ($this->stay['Subtotal']['PAP_Total'] + $this->displaced['Subtotal']['PAP_Total']) . "</td>";
        echo "</tr>";
    }

    private function getData() {
        //get data
        $query = "SELECT * FROM survey";
        $result = $this->db->query($query);

        //loop on each data then add to arrays
        while ($data = $result->fetch_assoc()) {
            $displacement = $this->isStay($data['displacement']);
            if ($displacement === 0) {
                /*
                    stay
                    Res, CIBE or Mixed
                    Legal or Not
                */
                $use = trim(strtoupper($data['structure_use']));
                $dp = trim(strtoupper($data['structure_dp']));
                $legal = $this->getLegal($data['type']);
                $hh_count = intval($data['hh_members']); //get household count
                $category = 'EXCESS';
                //check res, cibe or mixed
                if ($use == 'RESIDENTIAL' || $use == 'R') {
                    $category = 'Residential';
                } elseif ($use == 'COMMERCIAL' || $use == 'C') {
                    $category = 'CIBE';
                } elseif ($use == 'MIXED USE' || $use = 'R/C' || $use =='R/I') {
                    $category = 'Mixed';
                } elseif (FALSE) {
                    $category = 'Land_Owner';
                } elseif ($dp == 'STRUCTURE RENTER') {
                    $category = 'Tenant';
                } elseif (FALSE) {
                    $category = 'Wage_Earner';
                } elseif (strpos(strtoupper($data['structure_owner']), '(ABSENTEE)') !== FALSE) {
                    $category = 'Absentee';
                } elseif ($use == 'INSTITUTIONAL OCCUPANT' || $use == 'INSTITUTIONAL') {
                    $category = 'Institutional';
                } else {
                    //excess
                    $this->excess[] = array($data['uid'], 'Legal/Uncategorized');
                }

                if ($category != 'EXCESS') {
                    $this->stay[$category]['PAF_' . $legal]++;
                    $this->stay[$category]['PAF_Total']++;
                    $this->stay[$category]['PAP_' . $legal] += $hh_count;
                    $this->stay[$category]['PAP_Total'] += $hh_count;

                    $this->stay['Subtotal']['PAF_' . $legal]++;
                    $this->stay['Subtotal']['PAF_Total']++;
                    $this->stay['Subtotal']['PAP_' . $legal] += $hh_count;
                    $this->stay['Subtotal']['PAP_Total'] += $hh_count;
                }
            } elseif ($displacement === 1) {
                /*
                    displaced
                    Res, CIBE or Mixed
                    Legal or Not
                */
                $use = trim(strtoupper($data['structure_use']));
                $legal = $this->getLegal($data['type']);
                $hh_count = intval($data['hh_members']); //get household count
                $category = 'EXCESS';
                //check res, cibe or mixed
                if ($use == 'RESIDENTIAL' || $use == 'R') {
                    $category = 'Residential';
                } elseif ($use == 'COMMERCIAL' || $use == 'C') {
                    $category = 'CIBE';
                } elseif ($use == 'MIXED USE' || $use = 'R/C' || $use =='R/I') {
                    $category = 'Mixed';
                } else {
                    //excess
                    $this->excess[] = array($data['uid'], 'ISF/Uncategorized')
                }

                if ($category != 'EXCESS') {
                    $this->displaced[$category]['PAF_' . $legal]++;
                    $this->displaced[$category]['PAF_Total']++;
                    $this->displaced[$category]['PAP_' . $legal] += $hh_count;
                    $this->displaced[$category]['PAP_Total'] += $hh_count;

                    $this->displaced['Subtotal']['PAF_' . $legal]++;
                    $this->displaced['Subtotal']['PAF_Total']++;
                    $this->displaced['Subtotal']['PAP_' . $legal] += $hh_count;
                    $this->displaced['Subtotal']['PAP_Total'] += $hh_count;
                }
            } else {
                $this->excess[] = array($data['uid'], 'Displacement');
            }
        }
    }

    public function buildExcess() {
        //Excess IDs

        $fields = array(
            'Asset #'           => 'asset_num',
            'Type'              => 'type',
            'Name'              => 'name',
            'Address'           => 'address',
            'Structure Type'    => 'structure_type',
            'Structure Owner'   => 'structure_owner',
            'Use'               => 'structure_use',
            'DP Type'           => 'structure_dp',
            'Displacement'      => 'displacement'
        );
        echo '<table border="1" cellpadding="3" cellspacing="0">';
        echo '<thead>';
            echo '<tr>';
            foreach ($fields as $key => $val) {
                echo "<td>$key</td>";
            }
            echo "<td>Reason</td>";
            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
            foreach ($this->excess as $ex) {
                $query = 'SELECT * FROM survey WHERE uid = ' . $ex[0];
                $result = $this->db->query($query);
                $data = $result->fetch_assoc();

                echo '<tr>';
                foreach ($fields as $key => $val) {
                    echo "<td>" . $data[$val] . "</td>";
                }
                echo "<td>" . $ex[1] . "</td>";
                echo '</tr>';
            }
            echo '<tr>';
                echo '<td colspan="' . count($fields) . '">Total Count: ' . count($this->excess) . '</td>';
            echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    }

    function humanPrint($text) {
        switch ($text) {
            case 'Residential': return 'Residential';break;
            case 'CIBE': return 'CIBEs';break;
            case 'Mixed': return 'Mixed use';break;
            case 'Land_Owner': return 'Land owners';break;
            case 'Tenant': return 'Tenant famers/renters';break;
            case 'Wage_Earner': return 'Wage earners (Employees of CIBEs)';break;
            case 'Absentee': return 'Absentee Structure Owner';break;
            case 'Institutional': return 'Institutional/Industrial';break;
            case 'Subtotal': return 'Subtotal';break;
               
        }
    }

    function getLegal($legal) {
        $legal = trim(strtoupper($legal));
        if ($legal == 'LEGAL') {
            return 'Legal';
        } elseif ($legal == 'ISF') {
            return 'ISF';
        } else {
            return 'UKNOWN';
        }
    }

    function isStay($type) {
        $type = trim(strtoupper($type));
        if (strpos($type, 'CAN STAY') !== FALSE) {
            return 1; //add to stay
        } elseif ($type == 'NEED DISPLACEMENT') {
            return 0;
        } else {
            return -1;
        }
    }
}