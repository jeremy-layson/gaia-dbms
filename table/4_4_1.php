<?php  
    include('Class_4_4_1.php');
    $class = new Class_4_4_1();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.4-1</title>
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
    <h3>Table 4.4-1 Gender of Household Head (LGU Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Male</td>
            <td>Female</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];
            $vals['male'] = $value[$key]['male']['COUNT'];unset($value[$key]['male']['COUNT']);
            $vals['female'] = $value[$key]['female']['COUNT'];unset($value[$key]['female']['COUNT']);
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            echo "<tr>";
                echo "<td>$mun</td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=" . implode(",", $value[$key]['male']) . "' target='_blank'>" . round($vals['male'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=" . implode(",", $value[$key]['female']) . "' target='_blank'>" . round($vals['female'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";
        }
        $total_male = floatval($data['Total']['Total']['male']['COUNT']);
        $total_female = floatval($data['Total']['Total']['female']['COUNT']);
        $total_all = floatval($data['Total']['Total']['Total']['COUNT']);
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            echo "<td>" . round(($total_male / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_female / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.4-1 Gender of Household Head (Barangay Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Barangay</td>
            <td>Male</td>
            <td>Female</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $vals = [];
                $vals['male'] = $pop['male']['COUNT'];unset($pop['male']['COUNT']);
                $vals['female'] = $pop['female']['COUNT'];unset($pop['female']['COUNT']);
                $vals['Total'] = $pop['Total']['COUNT'];unset($pop['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=" . implode(",", $pop['male']) . "' target='_blank'>" . round($vals['male'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=" . implode(",", $pop['female']) . "' target='_blank'>" . round($vals['female'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=" . implode(",", $pop['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            echo "<td>" . round(($total_male / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_female / $total_all) * 100, 1) . "%</td>";
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>