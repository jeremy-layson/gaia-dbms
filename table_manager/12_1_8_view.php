<?php
    require('tableManager.php');
    $class = new tableManager('material_cost');

    $data = $class->table_structure();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-8</title>
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
    <h3>Table 12.1-8 Summary of Estimated Costs of Structures</h3>
    <table border="1">
        <thead>
            <tr>
                <td rowspan="2">City/Municipality</td>
                <td colspan="2">Light Material</td>
                <td colspan="2">Semi-Concrete</td>
                <td colspan="2">Concrete</td>
                <td rowspan="2">Total Construction Cost, PhP</td>
            </tr>
            <tr>
                <td>Total Area (sq.m)</td>
                <td>Construction Cost</td>
                <td>Total Area (sq.m)</td>
                <td>Construction Cost</td>
                <td>Total Area (sq.m)</td>
                <td>Construction Cost</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $key => $val) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    foreach ($class->materials as $material) {
                        echo "<td>" . number_format($val[$material]['Legal']['area'], 0) . "</td>";
                        echo "<td>" . number_format($val[$material]['Legal']['cost'], 0) . "</td>";
                    }
                    echo "<td>Total Placeholder</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>