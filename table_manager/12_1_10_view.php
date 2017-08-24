<?php
    require('tableManager.php');
    $class = new tableManager('material_cost');

    $data = $class->table_12_1_10();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-10</title>
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
    <h3>Table 12.1-10 Summary of Affected Trees and Plants</h3>
    <table border="1">
        <thead>
            <tr>
                <td rowspan="3">City/Municipality</td>
                <td colspan="2">Fruit Trees</td>
                <td colspan="2">Timber/Non fruit bearing trees</td>
                <td colspan="2">Crops by Area</td>
                <td colspan="2">All Trees and Plans</td>
            </tr>
            <tr>
                <td rowspan="2">Number / sq.m</td>
                <td>Total</td>
                <td rowspan="2">Number</td>
                <td rowspan="2">Total Cost, PhP</td>
                <td rowspan="2">Number / sq.m</td>
                <td rowspan="2">Total Cost, PhP</td>
                <td rowspan="2">Number</td>
                <td rowspan="2">Total Cost, PhP</td>
            </tr>
            <tr>
                <td>Cost, PhP</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $key => $val) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    foreach ($class->trees as $tree) {
                        echo "<td>" . number_format($val[$tree]['area'], 0) . "</td>";
                        echo "<td>" . number_format($val[$tree]['cost'], 0) . "</td>";
                    }
                    echo "<td>Total Placeholder</td>";
                    echo "<td>Total Placeholder</td>";
                    
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>