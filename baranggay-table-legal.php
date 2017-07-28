<?php 
    require_once('sql.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Baranggay Table (Legal)</title>
    <link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>
<h1>Table 4.1-3 Legal PAFs by LGUs</h1>
<table border="1" cellpadding="3" cellspacing="0">
    <tr>
        <td rowspan="3">Municipalities and Cities</td>
        <td rowspan="3">Affected Baranggays</td>
        <td rowspan="2" colspan="2">Structure Owners (Residential)</td>
        <td rowspan="2" colspan="2">Structure Owners (Mixed Use)</td>
        <td rowspan="2" colspan="2">Structure Owners (CIBEs)</td>
        <td rowspan="2" colspan="2">Structure Owners (Industrial)</td>
        <td rowspan="1" colspan="2">Renters</td>
        <td rowspan="2" colspan="2">Absentee Structure Owner</td>
        <td rowspan="1" colspan="2">Land</td>
        <td rowspan="2" colspan="2">Commercial Stall Tenants</td>
        <td rowspan="2" colspan="2">Total</td>
    </tr>
    <tr>
        <td rowspan="1" colspan="2">(Residential)</td>
        <td rowspan="1" colspan="2">Owners * 3</td>
    </tr>
    <tr>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
        <td>Stay<sup>1</sup></td>
        <td>Move<sup>2</sup></td>
    </tr>
    <!-- content here -->

    <!-- Loop through each municipalities and baranggays -->
    <?php 
        $municipalities = [];

        $query = "SELECT * FROM municipality";
        $result = $link->query($query);

        while ($row = $result->fetch_assoc()) {
            $municipalities[$row['municipality']][] = $row['baranggay'];
        }
    ?>

    <!-- echo each tables -->

    <?php 
        $total = array(
            'SO_R'      => array(0, 0),
            'SO_MU'     => array(0, 0),
            'SO_CIBE'   => array(0, 0),
            'SO_I'      => array(0, 0),
            'RR'        => array(0, 0),
            'ASO'       => array(0, 0),
            'LO'        => array(0, 0),
            'CST'       => array(0, 0),
            'Total'     => array(0, 0),
            'Excess'    => array()
        );

        $order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'ASO', 'LO', 'CST', 'Total');

        foreach ($municipalities as $municipality => $baranggays) {
            //add subtotal
            $subtotal = array(
                'SO_R'      => array(0, 0),
                'SO_MU'     => array(0, 0),
                'SO_CIBE'   => array(0, 0),
                'SO_I'      => array(0, 0),
                'RR'        => array(0, 0),
                'ASO'       => array(0, 0),
                'LO'        => array(0, 0),
                'CST'       => array(0, 0),
                'Total'     => array(0, 0),
                'Excess'    => array()
            );

            $isFirst = true;
    ?>
            <?php 
                foreach ($baranggays as $baranggay) {
                    $rowspan = count($baranggays);
                    echo "<tr>";
                    if ($isFirst === true) {
                        echo "<td rowspan='$rowspan'>$municipality</td>";
                        $isFirst = false;
                    }
                    echo '<td>' . $baranggay . '</td>';
                    printData($municipality, $baranggay, $link, $subtotal);
                    echo "</tr>";
                }
                
                //subtotal print
                echo '<tr class="subtotal">';
                echo '<td colspan="2">Sub Total</td>';
                foreach ($order as $key) {
                    //add to total
                    $total[$key][0] += $subtotal[$key][0];
                    $total[$key][1] += $subtotal[$key][1];
                    
                    echo '<td>' . $subtotal[$key][0] . '</td>';
                    echo '<td>' . $subtotal[$key][1] . '</td>';
                }
                $total['Excess'] = array_merge($total['Excess'], $subtotal['Excess']);
                echo '</tr>';
    }
    //total print
    echo '<tr>';
    echo '<td colspan="2">Grand Total</td>';
    foreach ($order as $key) {
        echo '<td>' . $total[$key][0] . '</td>';
        echo '<td>' . $total[$key][1] . '</td>';
    }
    echo '</tr>';

    //9 sets of stay/move
    function printData($municipality, $baranggay, $link, &$subtotal) {
        $aData = array(
            'SO_R'      => array(0, 0),
            'SO_MU'     => array(0, 0),
            'SO_CIBE'   => array(0, 0),
            'SO_I'      => array(0, 0),
            'RR'        => array(0, 0),
            'ASO'       => array(0, 0),
            'LO'        => array(0, 0),
            'CST'       => array(0, 0),
            'Total'     => array(0, 0)
        );

        //get data
        $query = "SELECT * FROM survey WHERE `type` = 'LEGAL' AND `address` LIKE '%" . $baranggay . "%' OR address LIKE '%" . str_replace('Baranggay', 'Brgy.', $baranggay) . "%'";
        $result = $link->query($query);

        while ($row = $result->fetch_assoc()) {
            $displacement = isStay($row['displacement']);
            //loop through each data and put under category

            $isExcess = false;

            if ($displacement !== -1) {
                //absentee
                if (strpos(strtoupper($row['structure_owner']), '(ABSENTEE)') !== FALSE) {
                    $aData['ASO'][$displacement]++;
                    $subtotal['ASO'][$displacement]++;
                    
                } else {
                    //cneck DP
                    $dp = trim(strtoupper($row['structure_dp']));
                    if ($dp == 'STRUCTURE OWNER' || $dp == 'INSTITUTIONAL OCCUPANT' || $dp == '') {
                        $use = trim(strtoupper($row['structure_use']));

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
                        } elseif ($use == 'INSTITUTIONAL OCCUPANT' || $use == 'INSTITUTIONAL' || $use == 'INDUSTRIAL' || $use == 'I') {
                            $aData['SO_I'][$displacement]++;
                            $subtotal['SO_I'][$displacement]++;
                        } else {
                            $subtotal['Excess'][] = $row['uid'];
                            $isExcess = true;
                        }
                    } elseif ($dp == 'STRUCTURE RENTER') {
                        $aData['RR'][$displacement]++;
                        $subtotal['RR'][$displacement]++;
                    } elseif ($dp == 'CO-OWNER') {
                        $aData['LO'][$displacement]++;
                        $subtotal['LO'][$displacement]++;
                    } elseif ($dp == 'SHARER') {
                        $aData['LO'][$displacement]++;
                        $subtotal['LO'][$displacement]++;
                    } elseif ($dp == 'COMMERCIAL TENANT') {
                        $aData['CST'][$displacement]++;
                        $subtotal['CST'][$displacement]++;
                    } else {
                        $subtotal['Excess'][] = $row['uid'];
                        $isExcess = true;
                    }
                }    
            } else {
                $isExcess = true;
                $subtotal['Excess'][] = $row['uid'];
            }
            

            if ($isExcess === false) {
                $aData['Total'][$displacement]++;
                $subtotal['Total'][$displacement]++;
            }
            
        }

        //print

        $order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'ASO', 'LO', 'CST', 'Total');
        foreach ($order as $key) {
            echo '<td>' . $aData[$key][0] . '</td>';
            echo '<td>' . $aData[$key][1] . '</td>';
        }
    }

    //pass extent here
    function isStay($type) {
        $type = trim(strtoupper($type));
        if (strpos($type, 'CAN STAY') !== FALSE) {
            return 0; //add to stay
        } elseif ($type == 'NEED DISPLACEMENT') {
            return 1;
        } else {
            return -1;
        }
    }

    ?>
</table>
<br><br>

<h3>Uncategorized Data</h3>
<?php 
//Excess IDs

$fields = array(
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
    foreach ($fields as $key => $val) {
        echo "<td>$key</td>";
    }
    echo '</tr>';
echo '</thead>';
echo '<tbody>';
    foreach ($total['Excess'] as $excess) {
        $query = 'SELECT * FROM survey WHERE uid = ' . $excess;
        $result = $link->query($query);
        $data = $result->fetch_assoc();

        echo '<tr>';
            foreach ($fields as $key => $val) {
                echo "<td>" . $data[$val] . "</td>";
            }
        echo '</tr>';
    }
    echo '<tr>';
        echo '<td colspan="' . count($fields) . '">Total Count: ' . count($total['Excess']) . '</td>';
    echo '</tr>';
echo '</tbody>';
echo '</table>';
?>
<br><br>
<h3>Unread Data (Address format)</h3>
<table border="1" cellpadding="3" cellspacing="0">
    <thead>
        <?php 
            echo '<tr>';
            foreach ($fields as $key => $val) {
                echo "<td>$key</td>";
            }
            echo '</tr>';
        ?>
    </thead> 
    <tbody>
        <?php 
            $query = 'SELECT * FROM survey WHERE `type` = "LEGAL" AND NOT(address LIKE "%Tikay%" OR address LIKE "%Bulihan%" OR address LIKE "%San Pablo%" OR address LIKE "%Catmon%" OR address LIKE "%Poblacion%" OR address LIKE "%Tuktukan%" OR address LIKE "%Sta. Cruz%" OR address LIKE "%Tabang%" OR address LIKE "%Burol I%" OR address LIKE "%Taal%" OR address LIKE "%Igulot%" OR address LIKE "%Bundukan%" OR address LIKE "%Abangan Norte%" OR address LIKE "%Saog%" OR address LIKE "%Ibayo%" OR address LIKE "%Pandayan%" OR address LIKE "%Tugatog%" OR address LIKE "%Bancal%" OR address LIKE "%Malhacan%" OR address LIKE "%Baranggay 9%" OR address LIKE "%Baranggay 15%" OR address LIKE "%Baranggay 17%" OR address LIKE "%Baranggay 19%" OR address LIKE "%Baranggay 21%" OR address LIKE "%Baranggay 25%" OR address LIKE "%Baranggay 29%" OR address LIKE "%Baranggay 32%" OR address LIKE "%Baranggay 33%" OR address LIKE "%Baranggay 152%" OR address LIKE "%Baranggay 155%" OR address LIKE "%Baranggay 156%" OR address LIKE "%Baranggay 159%" OR address LIKE "%Baranggay 164%" OR address LIKE "%Baranggay 165%" OR address LIKE "%Baranggay 184%" OR address LIKE "%Baranggay 185%" OR address LIKE "%Baranggay 186%" OR address LIKE "%Baranggay 199%" OR address LIKE "%Baranggay 200%" OR address LIKE "%Baranggay 204%" OR address LIKE "%Baranggay 48%" OR address LIKE "%Baranggay 50%" OR address LIKE "%Baranggay 51%" OR address LIKE "%Baranggay 53%" OR address LIKE "%Malanday%" OR address LIKE "%Dalandanan%" OR address LIKE "%Malinta%" OR address LIKE "%Brgy.9%" OR address LIKE "%Brgy.15%" OR address LIKE "%Brgy.17%" OR address LIKE "%Brgy.19%" OR address LIKE "%Brgy.21%" OR address LIKE "%Brgy.25%" OR address LIKE "%Brgy.29%" OR address LIKE "%Brgy.32%" OR address LIKE "%Brgy.33%" OR address LIKE "%Brgy.152%" OR address LIKE "%Brgy.155%" OR address LIKE "%Brgy.156%" OR address LIKE "%Brgy.159%" OR address LIKE "%Brgy.164%" OR address LIKE "%Brgy.165%" OR address LIKE "%Brgy.184%" OR address LIKE "%Brgy.185%" OR address LIKE "%Brgy.186%" OR address LIKE "%Brgy.199%" OR address LIKE "%Brgy.200%" OR address LIKE "%Brgy.204%" OR address LIKE "%Brgy.48%" OR address LIKE "%Brgy.50%" OR address LIKE "%Brgy.51%" OR address LIKE "%Brgy.53%" OR address LIKE "%Malanday%" OR address LIKE "%Dalandanan%" OR address LIKE "%Malinta%")';
            $result = $link->query($query);
            while ($data = $result->fetch_assoc()) {
                echo '<tr>';
                foreach ($fields as $key => $val) {
                    echo "<td>" . $data[$val] . "</td>";
                    
                }
                echo '</tr>';
            }
                echo '<td colspan="' . count($fields) . '">Total Count: ' . $result->num_rows . '</td>';
            echo '</tr>';
        ?>
    </tbody>
</table>
</body>
</html>