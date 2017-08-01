<?php
include_once('../baranggayClass.php');
/**
* illegal class that uses Baranggay
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 07 . 30
*/
class Illegal extends Baranggay
{

    public function buildTable()
    {
        $this->total = array(
            'SO_R'      => array(0, 0),
            'SO_MU'     => array(0, 0),
            'SO_CIBE'   => array(0, 0),
            'SO_I'      => array(0, 0),
            'RR'        => array(0, 0),
            'Total'     => array(0, 0),
            'Excess'    => array()
        );

        $this->order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'Total');

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
            $this->included[] = $row['uid'];
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
                    $subtotal['Excess'][] = array($row['uid'], 'Parameter');
                    $isExcess = true;
                }
            } else {
                $isExcess = true;
                $subtotal['Excess'][] = array($row['uid'], 'Displacement');
            }
            
            if ($isExcess === false) {
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
    
    

}