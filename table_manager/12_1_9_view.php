<?php
    require('tableManager.php');
    $class = new tableManager('material_cost');

    $data = $class->table_12_1_9();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-9</title>
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
    <h3>Table 12.1-9 Summary of Affected Improvements</h3>
    <table border="1">
        <thead>
            <tr>
                <td rowspan="4">City/Municipality</td>
                <td colspan="6">Improvements</td>
                <td rowspan="4">Total Cost Improvements, PhP</td>
            </tr>
            <tr>
                <td colspan="2">Fence</td>
                <td colspan="2">Gate</td>
                <td colspan="2">Others*</td>
            </tr>
            <tr>
                <td>Area</td>
                <td>Cost</td>
                <td rowspan="2">Number</td>
                <td>Cost</td>
                <td rowspan="2">Number</td>
                <td>Cost</td>
            </tr>
            <tr>
                <td>(lm)</td>
                <td>(PhP)</td>
                <td>(PhP)</td>
                <td>(PhP)</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $key => $val) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    foreach ($class->improvements as $improvement) {
                        echo "<td>" . number_format($val[$improvement]['area'], 0) . "</td>";
                        echo "<td>" . number_format($val[$improvement]['cost'], 0) . "</td>";
                    }
                    echo "<td>Total Placeholder</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>