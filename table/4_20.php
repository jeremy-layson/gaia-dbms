<?php  
    include('Class_4_20.php');
    $class = new Class_4_20();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.20</title>
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
    <h3>Table 4.20 - Number of Affected Improvements by Type of Use</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Type of Improvement</td>
            <td>Residential</td>
            <td>Commercial</td>
            <td>Institutional</td>
            <td>Industrial</td>
            <td>Mixed Use</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            foreach ($class->improvements as $imp) {
                echo "<tr>";
                if ($imp == "Fence") echo "<td rowspan='4'>$mun</td>";
                echo "<td>$imp</td>";
                foreach ($class->tbl_cols as $field) {
                    $tmpVal = $value[$imp][$field]['COUNT'];unset($value[$imp][$field]['COUNT']);

                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,structure_use,improve_gate,improve_fence,improve_well,improve_bcourt,improve_pigpen,improve_toilet&id=" . implode(",", $value[$imp][$field]) . "' target='_blank'>" . round($tmpVal, 1) . "</a></td>";
                }
                echo "</tr>";
            }
        }
        //grand total
        echo "<tr>";
            echo "<td rowspan='2' colspan='2'>Grand Total</td>";
            foreach ($class->total as $key => $val) {
                $tmpVal = $val['COUNT'];unset($val['COUNT']);
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,structure_use,improve_gate,improve_fence,improve_well,improve_bcourt,improve_pigpen,improve_toilet&id=" . implode(",", $val) . "' target='_blank'>" . round($tmpVal, 1) . "</a></td>";
            }
        echo "</tr>";
        echo "<tr>";
            foreach ($class->total as $key => $val) {
                echo "<td>" . round(($val['COUNT'] / $class->total['Total']['COUNT']) * 100,1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,structure_use,improve_gate,improve_fence,improve_well,improve_bcourt,improve_pigpen,improve_toilet&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $('table td:first')
</script>
</body>
</html>