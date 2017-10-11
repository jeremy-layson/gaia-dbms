<?php  
    include('Class_4_33.php');
    $class = new Class_4_33();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.33</title>
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
    <h3>Table 4.33 Religious Affiliation</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Roman Catholic</td>
            <td>INC</td>
            <td>Born Again</td>
            <td>CAMACOP</td>
            <td>Miracle Crusade</td>
            <td>Protestant</td>
            <td>Christian</td>
            <td>Dating Daan</td>
            <td>Islam</td>
            <td>No Answer</td>
            <td>Total</td>
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
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,religion&id=" . implode(",", $value[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
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
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,religion&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
        echo "</tr>";
        echo "<tr>";
            foreach ($class->tbl_cols as $col) {
                $percent = round( ($vals[$col]/$vals['Total']) * 100,1);
                echo "<td>$percent%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<a target="_blank" href="/viewer.php?field=uid,address,baranggay,religion&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>