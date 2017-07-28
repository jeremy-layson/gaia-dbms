<?php 
    require_once('sql.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Area Affected 4.2-1</title>
    <link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>
<h1>Table 4.2-1</h1>
<table border="1" cellpadding="3" cellspacing="0">
    <thead>
    <tr>
        <td>Municipalities and Cities</td>
        <td>Barangays</td>
        <td>Residential</td>
        <td>Commercial</td>
        <td>Industrial</td>
        <td>Agricultural</td>
        <td>Mixed use</td>
        <td>Total</td>
    </tr>
    </thead>
    <!-- content here -->

    <?php 
        $municipalities = [];
        $baranggays = [];

        $query = "SELECT * FROM municipality";
        $result = $link->query($query);

        while ($row = $result->fetch_assoc()) {
            $municipalities[$row['municipality']][$row['baranggay']] = array('Residential' => 0, 'Commercial' => 0, 'Industrial' => 0, 'Agricultural' => 0, 'Mixed use' =>0, 'Total' => 0);
            $baranggays[] = array($row['baranggay'], $row['municipality']);
        }

        $excess = [];

        //get data
        $query = "SELECT * FROM survey";
        $result = $link->query($query);

        //loop on each data then add to arrays
        while ($data = $result->fetch_assoc()) {
            /*
                Res, Commercial, Industrial, Agricultural
            */
            $use = trim(strtoupper($data['structure_use']));
            $useAH = trim(strtoupper($data['use']));
            $dp = trim(strtoupper($data['structure_dp']));
            $category = 'EXCESS';
            //check res, cibe or mixed
            if ($useAH == 'AGRICULTURAL') {
                $category = 'Agricultural';
            } elseif ($use == 'RESIDENTIAL' || $use == 'R') {
                $category = 'Residential';
            } elseif ($use == 'COMMERCIAL' || $use == 'C') {
                $category = 'Commercial';
            } elseif ($use == 'MIXED USE' || $use = 'R/C' || $use =='R/I') {
                $category = 'Mixed use';
            } /*elseif (FALSE) {
                $category = 'Land_Owner';
            } elseif ($dp == 'STRUCTURE RENTER') {
                $category = 'Tenant';
            } elseif (FALSE) {
                $category = 'Wage_Earner';
            } elseif (strpos(strtoupper($data['structure_owner']), '(ABSENTEE)') !== FALSE) {
                $category = 'Absentee';
            } elseif ($use == 'INSTITUTIONAL OCCUPANT' || $use == 'INSTITUTIONAL') {
                $category = 'Institutional';
            }*/
            elseif ($use == 'Industrial' || $use =='I') {
                $category = 'Industrial';
            } elseif (FALSE) {
                $category = 'Agricultural';
            } else {
                //excess
                $excess[] = $data['uid'];
            }

            if ($category != 'EXCESS') {
                $baranggay = getMunicipality($data['address'], $baranggays);
                if ($baranggay !== FALSE) {
                    //add
                    $municipalities[$baranggay[1]][$baranggay[0]][$category] += floatval($data['dms_affected']);
                    $municipalities[$baranggay[1]][$baranggay[0]]['Total'] += floatval($data['dms_affected']);
                    
                } else {
                    $excess[] = $data['uid'];
                }
            }
        }
        //print data here

        $total = array('Residential' => 0, 'Commercial' => 0, 'Industrial' => 0, 'Agricultural' => 0, 'Mixed use' =>0, 'Total' => 0);
        foreach ($municipalities as $municipality => $brgys) {
            $first = true;
            $subtotal = array('Residential' => 0, 'Commercial' => 0, 'Industrial' => 0, 'Agricultural' => 0, 'Mixed use' =>0, 'Total' => 0);
            foreach ($brgys as $brgy => $data) {
                echo '<tr>';
                if ($first === TRUE) {
                    echo "<td rowspan='" . (count($brgys) + 1) . "'>$municipality</td>";
                    $first = false;
                }
                
                echo "<td>$brgy</td>";
                foreach($data as $key => $cat) {
                    //add data
                    $subtotal[$key] += $cat;
                    $total[$key] += $cat;

                    echo "<td>$cat</td>";
                }
                echo '</tr>';
            }

            echo '<tr>';
            echo '<td>Subtotal</td>';
                foreach ($subtotal as $key => $val) {
                    echo "<td>$val</td>";
                }
            echo '</tr>';
        }
        echo '<tr>';
            echo '<td>&nbsp;</td>';
            echo '<td>Grand Total</td>';
                foreach ($total as $tot) {
                    echo "<td>$tot</td>";
                }
            echo '</tr>';

        function getMunicipality($mun, $baranggays) {
            if (!empty($mun)) {
                foreach ($baranggays as $baranggay) {
                    if (strpos(strtoupper($mun), strtoupper($baranggay[0])) !== FALSE) {
                        return $baranggay;
                    }
                }
            }
            return FALSE;
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