<?php
    require('tableManager.php');
    $class = new tableManager('material_cost');

    $data = $class->table_12_1_4();
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
    <h3>Table 12.1-4 Estimated Unit Construction Cost by Materials</h3>
    <table border="1">
        <thead>
            <tr>
                <td>Type of Structures</td>
                <td>Bulacan Province Unit Construction Cost (PhP/sq.m)</td>
                <td>Metro Manila Unit Construction Cost (PhP/sq.m)</td>
                <td>Description of Materials used in the Affected Structures</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $key => $val) {
                    echo "<tr>";
                        echo "<td>" . $val['structure_type'] . "</td>";
                        echo "<td>" . number_format($val['bulacan'], 0) . "</td>";
                        echo "<td>" . number_format($val['manila'], 0) . "</td>";
                        echo "<td>" . $val['description'] . "</td>";
                        
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>