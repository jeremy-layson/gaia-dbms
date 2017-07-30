<?php 
    require_once('sql.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Baranggay Table (ISF)</title>
    <link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>
<h1>Table 4.1-4 ISF by LGUs</h1>
<table border="1" cellpadding="3" cellspacing="0">
    <tr>
        <td rowspan="3">Municipalities and Cities</td>
        <td rowspan="3">Affected Baranggays</td>
        <td rowspan="2" colspan="2">Structure Owners (Residential)</td>
        <td rowspan="2" colspan="2">Structure Owners (Mixed Use)</td>
        <td rowspan="2" colspan="2">Structure Owners (CIBEs)</td>
        <td rowspan="2" colspan="2">Structure Owners (Industrial)</td>
        <td rowspan="1" colspan="2">Renters</td>
        <td rowspan="2" colspan="2">Total</td>
    </tr>
    <tr>
        <td rowspan="1" colspan="2">(Residential)</td>
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
    </tr>
    <!-- content here -->

    <!-- Loop through each municipalities and baranggays -->
    <?php 
        $included = [];
        $municipalities = [];

        $query = "SELECT * FROM municipality";
        $result = $link->query($query);

        while ($row = $result->fetch_assoc()) {
            $municipalities[$row['municipality']][] = array($row['baranggay'], $row['wildcard']);
        }

        $total = array(
            'SO_R'      => array(0, 0),
            'SO_MU'     => array(0, 0),
            'SO_CIBE'   => array(0, 0),
            'SO_I'      => array(0, 0),
            'RR'        => array(0, 0),
            'Total'     => array(0, 0),
            'Excess'    => array()
        );

        $order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'Total');

        foreach ($municipalities as $municipality => $baranggays) {
            //add subtotal
            $subtotal = array(
                'SO_R'      => array(0, 0),
                'SO_MU'     => array(0, 0),
                'SO_CIBE'   => array(0, 0),
                'SO_I'      => array(0, 0),
                'RR'        => array(0, 0),
                'Total'     => array(0, 0),
                'Excess'    => array()
            );

            $isFirst = true;

                foreach ($baranggays as $baranggay) {
                    $rowspan = count($baranggays);
                    echo "<tr>";
                    if ($isFirst === true) {
                        echo "<td rowspan='$rowspan'>$municipality</td>";
                        $isFirst = false;
                    }
                    echo '<td>' . $baranggay[0] . '</td>';
                    printData($municipality, $baranggay[1], $link, $subtotal);
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
    //returns the wildcarded string
    function getWildcard($wildcard) {
        $ret = '';
        $wc = explode(',', $wildcard);
        foreach ($wc as $card) {
            $ret = $ret . " address LIKE '%" . trim($card) . "%' OR";
        }

        return substr($ret, 0, -3);
    }
    //9 sets of stay/move
    function printData($municipality, $baranggay, $link, &$subtotal) {
        $aData = array(
            'SO_R'      => array(0, 0),
            'SO_MU'     => array(0, 0),
            'SO_CIBE'   => array(0, 0),
            'SO_I'      => array(0, 0),
            'RR'      => array(0, 0),
            'Total'     => array(0, 0)
        );

        //get data
        $query = "SELECT * FROM survey WHERE `type` = 'ISF' AND " . getWildcard($baranggay);
        $result = $link->query($query);
        while ($row = $result->fetch_assoc()) {
            $displacement = isStay($row['displacement']);
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
                $included[] = $row['uid'];
                $aData['Total'][$displacement]++;
                $subtotal['Total'][$displacement]++;
            }
            
        }
        //print

        $order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'Total');
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
var_dump($included);
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
            $query = 'SELECT * FROM survey WHERE `uid` IN (' . implode(', ', $included) . ')';
            echo $query;
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