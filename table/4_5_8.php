<?php  
    include('Class_4_5_8.php');
    $class = new Class_4_5_8();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.5-8</title>
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
    <h3>Table 4.5-8 Livelihood Rehabilitation Measures (LGU Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td colspan="2">Job Employment</td>
            <td colspan="2">Business Capital/Funds</td>
            <td colspan="2">Livelihood Program</td>
            <td colspan="2">Others</td>
            <td rowspan="2" colspan="2">Total</td>
        </tr>
        <tr>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $value['Sub Total'][$col]['COUNT'];unset($value['Sub Total'][$col]['COUNT']);
            }

            echo "<tr>";
            echo "<td>$mun</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,ialsa_livelihood_assistance,extent&id=" . implode(",", $value['Sub Total'][$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                echo "<td>" . round(($vals[$col] / $class->total[$col]['COUNT']) * 100, 1) . "%</td>";
            }
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td>Total</td>";
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $class->total[$col]['COUNT'];unset($class->total[$col]['COUNT']);
            }
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,ialsa_livelihood_assistance,extent&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                echo "<td>" . round(($vals[$col] / $vals['Total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.5-8 Livelihood Rehabilitation Measures (Baranggay Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td rowspan="2">Baranggay</td>
            <td colspan="2">Job Employment</td>
            <td colspan="2">Business Capital/Funds</td>
            <td colspan="2">Livelihood Program</td>
            <td colspan="2">Others</td>
            <td rowspan="2" colspan="2">Total</td>
        </tr>
        <tr>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $key = $brgy;
                $nVals = [];
                foreach ($class->tbl_cols as $col) {
                    $nVals[$col] = $value[$key][$col]['COUNT'];unset($value[$key][$col]['COUNT']);
                }

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$key</td>";
                    foreach ($class->tbl_cols as $col) {
                        echo "<td><a href='/viewer.php?field=uid,address,baranggay,ialsa_livelihood_assistance,extent&id=" . implode(",", $value[$key][$col]) . "' target='_blank'>" . round($nVals[$col], 1) . "</a></td>";
                        echo "<td>" . round(($nVals[$col] / $vals[$col]) * 100, 1) . "%</td>";
                    }
                echo "</tr>";
            }
        }

        echo "<tr>";
            echo "<td colspan='2'>Total</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,ialsa_livelihood_assistance,extent&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                echo "<td>" . round(($vals[$col] / $vals['Total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,address,baranggay,ialsa_livelihood_assistance,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>