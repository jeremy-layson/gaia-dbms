<?php
    require('tableManager.php');
    $class = new tableManager('material_cost');

    $data = $class->table_structure();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-5</title>
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
    <h3>Table 12.1-5 Estimated Costs for Structures of Light Materials</h3>
    <table border="1">
        <thead>
            <tr>
                <td rowspan="2">City/Municipality</td>
                <td colspan="2">Legal</td>
                <td colspan="2">ISF</td>
                <td rowspan="2">Total Construction Cost, PhP</td>
            </tr>
            <tr>
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
                        echo "<td>" . number_format($val['light']['Legal']['area'], 0) . "</td>";
                        echo "<td>" . number_format($val['light']['Legal']['cost'], 0) . "</td>";
                        echo "<td>" . number_format($val['light']['ISF']['cost'], 0) . "</td>";
                        echo "<td>" . number_format($val['light']['ISF']['cost'], 0) . "</td>";
                        
                        echo "<td>Total Placeholder</td>";
                        
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>