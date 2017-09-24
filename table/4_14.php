<?php  
    include('Class_4_14.php');
    $class = new Class_4_14();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.14</title>
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
    <h3>Table 4.14 Affected Structures and Improvements </h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td colspan="2">Yes</td>
            <td colspan="2">No</td>
            <td colspan="2">Total</td>
        </tr>
        <tr>
            <td>Severely Affected</td>
            <td>Marginally Affected</td>
            <td>Severely Affected</td>
            <td>Marginally Affected</td>
            <td>Severely Affected</td>
            <td>Marginally Affected</td>
            
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $value[$col]['COUNT'];unset($value[$col]['COUNT']);
            }

            echo "<tr>";
            echo "<td>$mun</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,extent,type&id=" . implode(",", $value[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
            
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td rowspan='2'>Total</td>";
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $class->total[$col]['COUNT'];unset($class->total[$col]['COUNT']);
            }
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,extent,type&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
            
        echo "</tr>";
        echo "<tr>";
            foreach ($class->tbl_cols as $col) {
                $total = $vals['Total_Severe'] + $vals['Total_Margin'];
                $percent = round( ($vals[$col] / $total) * 100,1);
                echo "<td>$percent%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<a target="_blank" href="/viewer.php?field=uid,address,baranggay,extent,type&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>