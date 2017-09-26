<?php
    require('tableManager.php');
    $class = new tableManager('material_cost');

    $mult = $class->getPreparedMaterialCost($class->data);
    $data = $class->table_12_1_8();
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
                foreach ($data as $key => $make) {
                    $mun = "";

                    if ($key == "Manila") {
                        $mun = "manila";
                    } elseif ($key == "Valenzuela") {
                        $mun = "valenzuela";
                    } elseif ($key == "Valenzuela (Depot)") {
                        $mun = "valenzuela";
                    } else {
                        $mun = "bulacan";
                    }

                    echo "<tr>";
                    echo "<td>$key</td>";
                    echo "<td>" . $make['light'] . "</td>";
                    echo "<td>" . ($make['light'] * $mult['Light Material'][$mun]) . "</td>";
                    
                    echo "<td>" . $make['semi'] . "</td>";
                    echo "<td>" . ($make['semi'] * $mult['Semi-concrete'][$mun]) . "</td>";
                    
                    echo "<td>" . $make['concrete'] . "</td>";
                    echo "<td>" . ($make['concrete'] * $mult['Concrete'][$mun]) . "</td>";
                    
                    $total = ($make['light'] * $mult['Light Material'][$mun]) + ($make['semi'] * $mult['Semi-concrete'][$mun]) + ($make['concrete'] * $mult['Concrete'][$mun]);

                    
                    echo "<td>$total</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>