<?php  
    include('Class_4_2_1.php');
    $class = new Class_4_2_1();
    $data = $class->getData();
   
?>



<!DOCTYPE html>
<html>
<head>
    <title>Table 4.2-1</title>
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
    </style>
</head>
<body>
<a href="/">Back</a>
<h3>Table 4.2-1 Affected Lands: Area(m<sup>2</sup>)</h3>
<table border="1">
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Residential</td>
            <td>Commercial</td>
            <td>Industrial</td>
            <td>Institutional</td>
            <td>Mixed Use</td>
            <td>Agricultural</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $brgy) {
            $brgy = array('Sub Total' => $brgy['Sub Total']);
            $added = FALSE;
            foreach ($brgy as $key => $value) {
                $vals = array(
                    $value['RESIDENTIAL']['COUNT'],
                    $value['COMMERCIAL']['COUNT'],
                    $value['INDUSTRIAL']['COUNT'],
                    $value['INSTITUTIONAL']['COUNT'],
                    $value['MIXED USE']['COUNT'],
                    $value['AGRICULTURAL']['COUNT'],
                    $value['Total']['COUNT'],
                );
                unset($value['RESIDENTIAL']['COUNT']);
                unset($value['COMMERCIAL']['COUNT']);
                unset($value['INDUSTRIAL']['COUNT']);
                unset($value['INSTITUTIONAL']['COUNT']);
                unset($value['MIXED USE']['COUNT']);
                unset($value['AGRICULTURAL']['COUNT']);
                unset($value['Total']['COUNT']);

                echo "<tr>";
                    echo "<td>$mun</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['RESIDENTIAL']) . "' target='_blank'>" . round($vals[0], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['COMMERCIAL']) . "' target='_blank'>" . round($vals[1], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['INDUSTRIAL']) . "' target='_blank'>" . round($vals[2], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['INSTITUTIONAL']) . "' target='_blank'>" . round($vals[3], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['MIXED USE']) . "' target='_blank'>" . round($vals[4], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['AGRICULTURAL']) . "' target='_blank'>" . round($vals[5], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['Total']) . "' target='_blank'>" . round($vals[6], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td>Grand Total</td>";

            $totals = array(
                $class->total['RESIDENTIAL']['COUNT'],
                $class->total['COMMERCIAL']['COUNT'],
                $class->total['INDUSTRIAL']['COUNT'],
                $class->total['INSTITUTIONAL']['COUNT'],
                $class->total['MIXED USE']['COUNT'],
                $class->total['AGRICULTURAL']['COUNT'],
                $class->total['Total']['COUNT'],
            );
            unset($class->total['RESIDENTIAL']['COUNT']);
            unset($class->total['COMMERCIAL']['COUNT']);
            unset($class->total['INDUSTRIAL']['COUNT']);
            unset($class->total['INSTITUTIONAL']['COUNT']);
            unset($class->total['MIXED USE']['COUNT']);
            unset($class->total['AGRICULTURAL']['COUNT']);
            unset($class->total['Total']['COUNT']);
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[0], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[1], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[2], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[3], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[4], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[5], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[6], 1) . "</a></td>";
            
        echo "</tr>";
        ?>
    </tbody>
</table>
<h3>Table 4.2-1 Affected Lands: Area(m<sup>2</sup>)</h3>
<table border="1">
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Baranggay</td>
            <td>Residential</td>
            <td>Commercial</td>
            <td>Industrial</td>
            <td>Institutional</td>
            <td>Mixed Use</td>
            <td>Agricultural</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $brgy) {
            $added = FALSE;
            foreach ($brgy as $key => $value) {
                $vals = array(
                    $value['RESIDENTIAL']['COUNT'],
                    $value['COMMERCIAL']['COUNT'],
                    $value['INDUSTRIAL']['COUNT'],
                    $value['INSTITUTIONAL']['COUNT'],
                    $value['MIXED USE']['COUNT'],
                    $value['AGRICULTURAL']['COUNT'],
                    $value['Total']['COUNT'],
                );
                unset($value['RESIDENTIAL']['COUNT']);
                unset($value['COMMERCIAL']['COUNT']);
                unset($value['INDUSTRIAL']['COUNT']);
                unset($value['INSTITUTIONAL']['COUNT']);
                unset($value['MIXED USE']['COUNT']);
                unset($value['AGRICULTURAL']['COUNT']);
                unset($value['Total']['COUNT']);

                echo "<tr>";
                    if ($added === FALSE) {
                        $colNum = count($brgy);
                        echo "<td rowspan='$colNum'>$mun</td>";
                        $added = TRUE;
                    }
                    echo "<td>$key</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['RESIDENTIAL']) . "' target='_blank'>" . round($vals[0], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['COMMERCIAL']) . "' target='_blank'>" . round($vals[1], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['INDUSTRIAL']) . "' target='_blank'>" . round($vals[2], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['INSTITUTIONAL']) . "' target='_blank'>" . round($vals[3], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['MIXED USE']) . "' target='_blank'>" . round($vals[4], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['AGRICULTURAL']) . "' target='_blank'>" . round($vals[5], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $value['Total']) . "' target='_blank'>" . round($vals[6], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td colspan='2'>Grand Total</td>";

            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[0], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[1], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[2], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[3], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[4], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[5], 1) . "</a></td>";
            echo "<td><a href='/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=" . implode(",", $class->total['Total']) . "' target='_blank'>" . round($totals[6], 1) . "</a></td>";
            
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,name,address,baranggay,use,alo_affectedarea&id=<?=implode(',', $class->unclaimed)?>">Uncategoriz   ed Data</a>
</body>
</html>