<?php  
    include('Class_4_3_2.php');
    $class = new Class_4_3_2();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-2</title>
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
    <h3>Table 4.3-2 Size of Household (LGU Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td colspan="4">Range of Household Member</td>
            <td rowspan="2">Total</td>
        </tr>
        <tr>
            <td>0-3</td>
            <td>4-6</td>
            <td>7-9</td>
            <td>>9</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];
            $vals['3'] = $value[$key]['3']['COUNT'];unset($value[$key]['3']['COUNT']);
            $vals['6'] = $value[$key]['6']['COUNT'];unset($value[$key]['6']['COUNT']);
            $vals['9'] = $value[$key]['9']['COUNT'];unset($value[$key]['9']['COUNT']);
            $vals['else'] = $value[$key]['else']['COUNT'];unset($value[$key]['else']['COUNT']);
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            echo "<tr>";
                echo "<td>$mun</td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['3']) . "' target='_blank'>" . round($vals['3'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['6']) . "' target='_blank'>" . round($vals['6'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['9']) . "' target='_blank'>" . round($vals['9'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['else']) . "' target='_blank'>" . round($vals['else'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";
        }
        $range_3 = floatval($data['Total']['Total']['3']['COUNT']);
        $range_6 = floatval($data['Total']['Total']['6']['COUNT']);
        $range_9 = floatval($data['Total']['Total']['9']['COUNT']);
        $range_else = floatval($data['Total']['Total']['else']['COUNT']);
        $total_all = floatval($data['Total']['Total']['Total']['COUNT']);
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            echo "<td>" . round(($range_3 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_6 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_9 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_else / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-2 Size of Household (Barangay Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td rowspan="2">Barangay</td>
            <td colspan="4">Range of Household Member</td>
            <td rowspan="2">Total</td>
        </tr>
        <tr>
            <td>0-3</td>
            <td>4-6</td>
            <td>7-9</td>
            <td>>9</td>
        </tr>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $key = $brgy;
                $vals = [];
                $vals['3'] = $value[$key]['3']['COUNT'];unset($value[$key]['3']['COUNT']);
                $vals['6'] = $value[$key]['6']['COUNT'];unset($value[$key]['6']['COUNT']);
                $vals['9'] = $value[$key]['9']['COUNT'];unset($value[$key]['9']['COUNT']);
                $vals['else'] = $value[$key]['else']['COUNT'];unset($value[$key]['else']['COUNT']);
                $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$key</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['3']) . "' target='_blank'>" . round($vals['3'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['6']) . "' target='_blank'>" . round($vals['6'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['9']) . "' target='_blank'>" . round($vals['9'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['else']) . "' target='_blank'>" . round($vals['else'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            echo "<td>" . round(($range_3 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_6 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_9 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_else / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,hh_members&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>