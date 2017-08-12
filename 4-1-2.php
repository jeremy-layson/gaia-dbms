<?php 
    require_once('sql.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>4.1-2</title>
    <link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>
<h1>Table 4.1-2 Affected Lands</h1>
<table border="1" cellpadding="3" cellspacing="0">
    <thead>
    <tr>
        <td rowspan="2">Type of Loss</td>
        <td colspan="3">Number of PAFs</td>
        <td colspan="3">Number of PAFs</td>
    </tr>
    <tr>
        <td>Legal<sup>1</sup></td>
        <td>ISF<sup>2</sup></td>
        <td>Total<sup>3</sup></td>
        <td>Legal<sup>1</sup></td>
        <td>ISF<sup>2</sup></td>
        <td>Total<sup>3</sup></td>
    </tr>
    </thead>
    <!-- content here -->

    <?php 
        $displaced = array(
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

        $stay = array(
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

        $excess = [];

        //get data
        $query = "SELECT * FROM survey";
        $result = $link->query($query);

        //loop on each data then add to arrays
        while ($data = $result->fetch_assoc()) {
            $displacement = isStay($data['displacement']);
            if ($displacement === 0) {
                /*
                    stay
                    Res, CIBE or Mixed
                    Legal or Not
                */
                $use = trim(strtoupper($data['structure_use']));
                $dp = trim(strtoupper($data['structure_dp']));
                $legal = getLegal($data['type']);
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
                    $excess[] = $data['uid'];
                }

                if ($category != 'EXCESS') {
                    $stay[$category]['PAF_' . $legal]++;
                    $stay[$category]['PAF_Total']++;
                    $stay[$category]['PAP_' . $legal] += $hh_count;
                    $stay[$category]['PAP_Total'] += $hh_count;

                    $stay['Subtotal']['PAF_' . $legal]++;
                    $stay['Subtotal']['PAF_Total']++;
                    $stay['Subtotal']['PAP_' . $legal] += $hh_count;
                    $stay['Subtotal']['PAP_Total'] += $hh_count;
                }
            } elseif ($displacement === 1) {
                /*
                    displaced
                    Res, CIBE or Mixed
                    Legal or Not
                */
                $use = trim(strtoupper($data['structure_use']));
                $legal = getLegal($data['type']);
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
                    $excess[] = $data['uid'];
                }

                if ($category != 'EXCESS') {
                    $displaced[$category]['PAF_' . $legal]++;
                    $displaced[$category]['PAF_Total']++;
                    $displaced[$category]['PAP_' . $legal] += $hh_count;
                    $displaced[$category]['PAP_Total'] += $hh_count;

                    $displaced['Subtotal']['PAF_' . $legal]++;
                    $displaced['Subtotal']['PAF_Total']++;
                 -   $displaced['Subtotal']['PAP_' . $legal] += $hh_count;
                    $displaced['Subtotal']['PAP_Total'] += $hh_count;
                }
            } else {
                $excess[] = $data['uid'];
            }
        }
        //print data here
        //print displaced
        echo '<tr><td colspan="7" style="text-align:center;">Required for displacement</td></tr>';
        foreach ($displaced as $key => $value) {
            echo "<tr>";
                echo "<td>" . humanPrint($key) . "</td>";
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
        foreach ($stay as $key => $value) {
            echo "<tr>";
                echo "<td>" . humanPrint($key) . "</td>";
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
                echo "<td>" . ($stay['Subtotal']['PAF_Legal'] + $displaced['Subtotal']['PAF_Legal']) . "</td>";
                echo "<td>" . ($stay['Subtotal']['PAF_ISF'] + $displaced['Subtotal']['PAF_ISF']) . "</td>";
                echo "<td>" . ($stay['Subtotal']['PAF_Total'] + $displaced['Subtotal']['PAF_Total']) . "</td>";
                echo "<td>" . ($stay['Subtotal']['PAP_Legal'] + $displaced['Subtotal']['PAP_Legal']) . "</td>";
                echo "<td>" . ($stay['Subtotal']['PAP_ISF'] + $displaced['Subtotal']['PAP_ISF']) . "</td>";
                echo "<td>" . ($stay['Subtotal']['PAP_Total'] + $displaced['Subtotal']['PAP_Total']) . "</td>";
            echo "</tr>";

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
    ?>
</table>
<br><br>

<h3>Uncategorized Data</h3>
<?php 
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
    echo '</tr>';
echo '</thead>';
echo '<tbody>';
    foreach ($excess as $ex) {
        $query = 'SELECT * FROM survey WHERE uid = ' . $ex;
        $result = $link->query($query);
        $data = $result->fetch_assoc();

        echo '<tr>';
            foreach ($fields as $key => $val) {
                echo "<td>" . $data[$val] . "</td>";
            }
        echo '</tr>';
    }
    echo '<tr>';
        echo '<td colspan="' . count($fields) . '">Total Count: ' . count($excess) . '</td>';
    echo '</tr>';
echo '</tbody>';
echo '</table>';
?>
</body>
</html>