<?php
    require('tableManager.php');
    $class = new tableManager('market_value');

    $data = $class->table_12_1_2();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-2</title>
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
    <h3>Table 12.1-2 Market Values for the Project Affected Lands (PhP/sq.m)</h3>
    <table border="1">
        <thead>
            <tr>
                <td>City/Municipality</td>
                <td>Residential</td>
                <td>Commercial</td>
                <td>Industrial</td>
                <td>Agricultural</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $mun => $val) {
                    echo "<tr>";
                        echo "<td>$mun</td>";
                        echo "<td>" . $val['Residential'] . "</td>";
                        echo "<td>" . $val['Commercial'] . "</td>";
                        echo "<td>" . $val['Industrial'] . "</td>";
                        echo "<td>" . $val['Agricultural'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>