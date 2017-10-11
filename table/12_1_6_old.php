<?php  
    include('Class_12_1_6_old.php');
    $class = new Class_12_1_6_old();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 12.1-6</title>
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
    <h3>Table 12.1-6 Estimated Cost of ISF Structures based on RCS</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td colspan="3">Marginally Affected</td>
            <td colspan="3">Severely Affected</td>
            <td rowspan="2">Total Cost</td>
        </tr>
        <tr>
            <td>No.</td>
            <td>Area Affected</td>
            <td>Cost</td>
            <td>No.</td>
            <td>Area Affected</td>
            <td>Cost</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {

            echo "<tr>";
            echo "<td>$mun</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td>" . round($value[$col], 1) . "</td>";
            }
            
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td>Total</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td>" . round($class->total[$col], 1) . "</td>";
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