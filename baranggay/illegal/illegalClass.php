<?php
include_once('../baranggayClass.php');
/**
* illegal class that uses Baranggay
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 07 . 30
*/
class Illegal extends Baranggay
{
    private $rawTotal;
    private $fields;

    public function buildTable()
    {
        $this->rawTotal = $this->total;
        foreach ($this->municipalities as $municipality => $baranggays) {
            //add subtotal
            $subtotal = $this->rawTotal;

            $isFirst = true;

            foreach ($baranggays as $baranggay) {
                $rowspan = count($baranggays);
                echo "<tr>";
                if ($isFirst === true) {
                    echo "<td rowspan='$rowspan'>$municipality</td>";
                    $isFirst = false;
                }
                echo '<td>' . $baranggay[0] . '</td>';
                $this->printData($municipality, $baranggay[1], $subtotal);
                echo "</tr>";
            }
                
            //subtotal print
            echo '<tr class="subtotal">';
            echo '<td colspan="2">Sub Total</td>';
            foreach ($this->order as $key) {
                //add to total
                $this->total[$key][0] += $subtotal[$key][0];
                $this->total[$key][1] += $subtotal[$key][1];
                
                echo '<td>' . $subtotal[$key][0] . '</td>';
                echo '<td>' . $subtotal[$key][1] . '</td>';
            }
            $this->total['Excess'] = array_merge($this->total['Excess'], $subtotal['Excess']);
            echo '</tr>';
        }

        //total print
        echo '<tr>';
        echo '<td colspan="2">Grand Total</td>';
        foreach ($this->order as $key) {
            echo '<td>' . $this->total[$key][0] . '</td>';
            echo '<td>' . $this->total[$key][1] . '</td>';
        }
        echo '</tr>';
    }

    //9 sets of stay/move
    public function printData($municipality, $baranggay, &$subtotal) {
        $aData = $this->rawTotal;

        //get data
        $query = "SELECT * FROM survey WHERE `type` = 'ISF' AND " . $this->getWildcard($baranggay);
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $displacement = $this->isStay($row['displacement']);
            //loop through each data and put under category

            $isExcess = false;

            if ($displacement !== -1) {
                //cneck DP
                $dp = trim(strtoupper($row['structure_dp']));
                $use = trim(strtoupper($row['structure_use']));

                // if ($dp == 'STRUCTURE OWNER' || $dp == 'INSTITUTIONAL OCCUPANT') {
                // if ($dp != 'STRUCTURE RENTER') {
                //check SO category
                if ($use == 'RESIDENTIAL' || $use == 'R') {
                    $aData['SO_R'][$displacement]++;
                    $subtotal['SO_R'][$displacement]++;
                } elseif ($use == 'COMMERCIAL' || $use == 'C') {
                    $aData['SO_CIBE'][$displacement]++;
                    $subtotal['SO_CIBE'][$displacement]++;
                } elseif ($use == 'MIXED USE' || $use = 'R/C' || $use =='R/I') {
                    $aData['SO_MU'][$displacement]++;
                    $subtotal['SO_MU'][$displacement]++;
                } elseif ($use == 'INSTITUTIONAL OCCUPANT' || $use == 'INSTITUTIONAL' || $use == 'I' ||$use == 'INDUSTRIAL') {
                    $aData['SO_I'][$displacement]++;
                    $subtotal['SO_I'][$displacement]++;
                } elseif ($dp == 'STRUCTURE RENTER') {
                    $aData['RR'][$displacement]++;
                    $subtotal['RR'][$displacement]++;
                } else {
                    $subtotal['Excess'][] = $row['uid'];
                    $isExcess = true;
                }
            } else {
                $isExcess = true;
                $subtotal['Excess'][] = $row['uid'];
            }
            
            if ($isExcess === false) {
                $this->included[] = $row['uid'];
                $aData['Total'][$displacement]++;
                $subtotal['Total'][$displacement]++;
            }
            
        }
        //print
        foreach ($this->order as $key) {
            echo '<td>' . $aData[$key][0] . '</td>';
            echo '<td>' . $aData[$key][1] . '</td>';
        }
    }
    
    public function printUncategorized()
    {
        $this->fields = array(
            'Asset #'           => 'asset_num',
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
            foreach ($this->fields as $key => $val) {
                echo "<td>$key</td>";
            }
            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
            foreach ($this->total['Excess'] as $excess) {
                $query = 'SELECT * FROM survey WHERE uid = ' . $excess;
                $result = $this->db->query($query);
                $data = $result->fetch_assoc();

                echo '<tr>';
                    foreach ($this->fields as $key => $val) {
                        echo "<td>" . $data[$val] . "</td>";
                    }
                echo '</tr>';
            }
            echo '<tr>';
                echo '<td colspan="' . count($this->fields) . '">Total Count: ' . count($this->total['Excess']) . '</td>';
            echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    }

    public function printUnincluded()
    {
        echo '<thead>';
            echo '<tr>';
            foreach ($this->fields as $key => $val) {
                echo "<td>$key</td>";
            }
            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
            $query = 'SELECT * FROM survey WHERE `type` = "ISF" AND `uid` NOT IN (' . implode(', ', $this->included) . ')';
            $result = $this->db->query($query);
            while ($data = $result->fetch_assoc()) {
                echo '<tr>';
                foreach ($this->fields as $key => $val) {
                    echo "<td>" . $data[$val] . "</td>";
                    
                }
                echo '</tr>';
            }
                echo '<td colspan="' . count($this->fields) . '">Total Count: ' . $result->num_rows . '</td>';
            echo '</tr>';
        echo '</tbody>';
    }

}