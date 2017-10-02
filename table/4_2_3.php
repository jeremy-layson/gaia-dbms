<?php  
    include('Class_4_2_3.php');
    $class = new Class_4_2_3();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.2-3</title>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 5px;
            margin: 0px;
        }

        td a {
            color: black;
            text-decoration: none;
        }

        thead td {
            background-color: #e3e3e3;
            font-weight: bold;
            text-align: center;
        }

        tbody td {
            text-align: center;
        }
    </style>
</head>
<body>
<a href="/">Back</a>

<table border="1">
    <h3>Table 4.2-3 Affected Improvements</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Affected Baranggays</td>
            <td>Type of Improvement</td>
            <td>Residential</td>
            <td>Institutional</td>
            <td>Industrial</td>
            <td>Mixed Use</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            $spanM = true;
            foreach ($value as $brgy => $pop) {
                $key = $brgy;
                $sub = true;
                $spanB = true;
                foreach ($class->improvements as $imp) {
                    if ($sub === false) break;
                    if ($brgy == 'Sub Total') {
                        $imp = 'Sub Total'; $sub = false;
                    }
                    echo "<tr>";
                        if ($sub === false) {
                            echo "<td colspan='3'>$imp</td>";
                        } else {
                            if ($spanM === true) {
                                //count all brgys
                                $nCtr = -1;
                                foreach ($value as $tmpBrgy) {
                                    $nCtr += count($tmpBrgy);
                                }
                                echo "<td rowspan='$nCtr'>$mun</td>";
                                $spanM = false;
                            }

                            if ($spanB === true) echo "<td rowspan='" . count($pop) . "'>$key</td>";$spanB = false;
                            echo "<td>$imp</td>";
                        }
                        foreach ($class->tbl_cols as $field) {
                            if (isset($pop[$imp][$field]['COUNT']) === FALSE) {
                                var_dump($data[$mun]);
                            }
                            $tmpVal = $pop[$imp][$field]['COUNT'];unset($pop[$imp][$field]['COUNT']);

                            echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,structure_use,improve_gate,improve_fence,improve_well,improve_bcourt,improve_pigpen,improve_toilet,improve_bridge,improve_terminal,improve_shed,improve_storage,improve_toilet,improve_watertank,improve_extension,improve_fishpond,improve_garage,improve_sarisari,improve_playground,improve_table,improve_parking&id=" . implode(",", $pop[$imp][$field]) . "' target='_blank'>" . round($tmpVal, 1) . "</a></td>";
                        }
                    echo "</tr>";
                }

            }
        }
        //grand total
        echo "<tr>";
            echo "<td colspan='3'>Grand Total</td>";
            foreach ($class->total as $key => $val) {
                $tmpVal = $val['COUNT'];unset($val['COUNT']);
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,structure_use,improve_gate,improve_fence,improve_well,improve_bcourt,improve_pigpen,improve_toilet,improve_bridge,improve_terminal,improve_shed,improve_storage,improve_toilet,improve_watertank,improve_extension,improve_fishpond,improve_garage,improve_sarisari,improve_playground,improve_table,improve_parking&id=" . implode(",", $val) . "' target='_blank'>" . round($tmpVal, 1) . "</a></td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,structure_use,improve_gate,improve_fence,improve_well,improve_bcourt,improve_pigpen,improve_toilet,improve_bridge,improve_terminal,improve_shed,improve_storage,improve_toilet,improve_watertank,improve_extension,improve_fishpond,improve_garage,improve_sarisari,improve_playground,improve_table,improve_parking&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $('table td:first')
</script>
</body>
</html>