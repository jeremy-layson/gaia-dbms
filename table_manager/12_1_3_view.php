<?php
    require('tableManager.php');
    $class = new tableManager('market_value');

    $data = $class->table_12_1_3();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-3</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        table td {
            padding: 3px;
        }
    </style>
</head>
<body>
    <h3>Table 12.1-3 Estimated Replacement Cost of Private Lands</h3>
    <table border="1">
        <thead>
            <tr>
                <td rowspan="2">City/Municipality</td>
                <td colspan="3">Residential</td>
                <td colspan="3">Commercial</td>
                <td colspan="3">Institutional</td>
                <td colspan="3">Industrial</td>
                <td colspan="3">Agricultural</td>
                <td colspan="3">All Lands</td>
            </tr>
            <tr>
                <td>Total Area Affected</td>
                <td>%</td>
                <td>Total Cost (PhP)</td>
                <td>Total Area Affected</td>
                <td>%</td>
                <td>Total Cost (PhP)</td>
                <td>Total Area Affected</td>
                <td>%</td>
                <td>Total Cost (PhP)</td>
                <td>Total Area Affected</td>
                <td>%</td>
                <td>Total Cost (PhP)</td>
                <td>Total Area Affected</td>
                <td>%</td>
                <td>Total Cost (PhP)</td>
                <td>Total Area Affected</td>
                <td>%</td>
                <td>Total Cost (PhP)</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $mun => $val) {
                    echo "<tr>";
                        echo "<td>$mun</td>";
                        echo "<td>" . $val['Residential']['area'] . "</td>";
                        echo "<td>0%</td>";
                        echo "<td>" . $val['Residential']['multiplier'] . "</td>";
                        
                        echo "<td>" . $val['Commercial']['area'] . "</td>";
                        echo "<td>0%</td>";
                        echo "<td>" . $val['Commercial']['multiplier'] . "</td>";

                        echo "<td>" . $val['Institutional']['area'] . "</td>";
                        echo "<td>0%</td>";
                        echo "<td>" . $val['Institutional']['multiplier'] . "</td>";

                        echo "<td>" . $val['Industrial']['area'] . "</td>";
                        echo "<td>0%</td>";
                        echo "<td>" . $val['Industrial']['multiplier'] . "</td>";

                        echo "<td>" . $val['Agricultural']['area'] . "</td>";
                        echo "<td>0%</td>";
                        echo "<td>" . $val['Agricultural']['multiplier'] . "</td>";

                        echo "<td>--</td>";
                        echo "<td>--</td>";
                        echo "<td>--</td>";
                        
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>