<?php  
    include('Class_4_16.php');
    $class = new Class_4_16();
    $data = $class->getData();
   
?>



<!DOCTYPE html>
<html>
<head>
    <title>Table 4.16</title>
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
<h3>Table 4.16 Number of Affected Structures in Valenzuela Depot</h3>
<table border="1">
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Residential</td>
            <td>Commercial</td>
            <td>Industrial</td>
            <td>Institutional</td>
            <td>Mixed Use</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $key => $value) {
            $vals = array(
                $value['RESIDENTIAL']['COUNT'],
                $value['COMMERCIAL']['COUNT'],
                $value['INDUSTRIAL']['COUNT'],
                $value['INSTITUTIONAL']['COUNT'],
                $value['MIXED USE']['COUNT'],
                $value['Total']['COUNT'],
            );
            unset($value['RESIDENTIAL']['COUNT']);
            unset($value['COMMERCIAL']['COUNT']);
            unset($value['INDUSTRIAL']['COUNT']);
            unset($value['INSTITUTIONAL']['COUNT']);
            unset($value['MIXED USE']['COUNT']);
            unset($value['Total']['COUNT']);

            echo "<tr>";
                echo "<td>$key</td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=" . implode(",", $value['RESIDENTIAL']) . "' target='_blank'>" . round($vals[0], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=" . implode(",", $value['COMMERCIAL']) . "' target='_blank'>" . round($vals[1], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=" . implode(",", $value['INDUSTRIAL']) . "' target='_blank'>" . round($vals[2], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=" . implode(",", $value['INSTITUTIONAL']) . "' target='_blank'>" . round($vals[3], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=" . implode(",", $value['MIXED USE']) . "' target='_blank'>" . round($vals[4], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=" . implode(",", $value['Total']) . "' target='_blank'>" . round($vals[5], 1) . "</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,name,address,baranggay,structure_use,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>
</body>
</html>