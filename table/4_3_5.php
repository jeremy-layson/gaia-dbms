<?php  
    include('Class_4_3_5.php');
    $class = new Class_4_3_5();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-5</title>
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
    <h3>Table 4.3-5 Length of Stay in Present Place (LGU Level)</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>< 1 Year</td>
            <td>>1 year, < 10 Years</td>
            <td>> 10 Years</td>
            <td>No Answer</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];
            $vals['less'] = $value[$key]['less']['COUNT'];unset($value[$key]['less']['COUNT']);
            $vals['110yrs'] = $value[$key]['110yrs']['COUNT'];unset($value[$key]['110yrs']['COUNT']);
            $vals['else'] = $value[$key]['else']['COUNT'];unset($value[$key]['else']['COUNT']);
            $vals['noans'] = $value[$key]['noans']['COUNT'];unset($value[$key]['noans']['COUNT']);
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            echo "<tr>";
                echo "<td>$mun</td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['less']) . "' target='_blank'>" . round($vals['less'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['110yrs']) . "' target='_blank'>" . round($vals['110yrs'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['else']) . "' target='_blank'>" . round($vals['else'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['noans']) . "' target='_blank'>" . round($vals['noans'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";
        }
        $range_6 = floatval($data['Total']['Total']['less']['COUNT']);
        $range_9 = floatval($data['Total']['Total']['110yrs']['COUNT']);
        $range_else = floatval($data['Total']['Total']['else']['COUNT']);
        $range_noans = floatval($data['Total']['Total']['noans']['COUNT']);
        $total_all = floatval($data['Total']['Total']['Total']['COUNT']);
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            echo "<td>" . round(($range_6 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_9 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_else / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_noans / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-5 Length of Stay in Present Place (Baranggay Level)</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Baranggay</td>
            <td>< 1 Year</td>
            <td>>1 year, < 10 Years</td>
            <td>> 10 Years</td>
            <td>No Answer</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $key = $brgy;
                $vals = [];
                $vals['less'] = $value[$key]['less']['COUNT'];unset($value[$key]['less']['COUNT']);
                $vals['110yrs'] = $value[$key]['110yrs']['COUNT'];unset($value[$key]['110yrs']['COUNT']);
                $vals['else'] = $value[$key]['else']['COUNT'];unset($value[$key]['else']['COUNT']);
                $vals['noans'] = $value[$key]['noans']['COUNT'];unset($value[$key]['noans']['COUNT']);
                $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$key</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['less']) . "' target='_blank'>" . round($vals['less'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['110yrs']) . "' target='_blank'>" . round($vals['110yrs'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['else']) . "' target='_blank'>" . round($vals['else'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['noans']) . "' target='_blank'>" . round($vals['noans'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            echo "<td>" . round(($range_6 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_9 / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_else / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($range_noans / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,hdi_length_stay&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>