<?php  
    include('Class_4_19.php');
    $class = new Class_4_19();
    $data = $class->getData();
   
?>



<!DOCTYPE html>
<html>
<head>
    <title>Table 4.19</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
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
        }

        tbody td:first-child {
            background-color: #e3e3e3;
            font-weight: bold;
        }
    </style>
</head>
<body>
<a href="/">Back</a>
<h3>Table 4.19 Number of Affected Improvements per LGU</h3>
<table border="1">
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <?php 
                foreach ($class->tbl_cols as $col) {
                    echo "<td>" . $class->definition[$col] . "</td>";
                }
            ?>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $key => $value) {

            echo "<tr>";
                echo "<td>$key</td>";
                foreach ($value as $column) {
                    echo "<td>" . $column . "</td>";
                }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>
</body>
</html>