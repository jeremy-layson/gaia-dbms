<?php  
    include('Class_4_3_13.php');
    $class = new Class_4_3_13();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-13</title>
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
    <h3>Table 4.3-13 Average Daily Commutation Cost (PhP) (LGU Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Male Household Head</td>
            <td>Female Household Head</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $average_male = 0;
        $average_female = 0;
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            // if ($mun == 'Total') $key = 'Total';
            if ($mun == 'Total') break;
            $vals = [];
            $vals['male'] = $value[$key]['male']['COUNT'];unset($value[$key]['male']['COUNT']);
            $vals['female'] = $value[$key]['female']['COUNT'];unset($value[$key]['female']['COUNT']);
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            $average_male += $vals['male'];
            $average_female += $vals['female'];

            echo "<tr>";
                echo "<td>$mun</td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender,shi_total_transpo&id=" . implode(",", $value[$key]['male']) . "' target='_blank'>" . round($vals['male'], 1) . "</a></td>";
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender,shi_total_transpo&id=" . implode(",", $value[$key]['female']) . "' target='_blank'>" . round($vals['female'], 1) . "</a></td>";
        }
        echo "<tr>";
            echo "<td>Average</td>";
            echo "<td>" . round($average_male / (count($data) - 1), 1) . "</td>";
            echo "<td>" . round($average_female / (count($data) - 1), 1) . "</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-13 Average Daily Commutation Cost (PhP) (Barangay Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Barangay</td>
            <td>Male Household Head</td>
            <td>Female Household Head</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            if ($mun == 'Total') break;
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $vals = [];
                $vals['male'] = $pop['male']['COUNT'];unset($pop['male']['COUNT']);
                $vals['female'] = $pop['female']['COUNT'];unset($pop['female']['COUNT']);
                $vals['Total'] = $pop['Total']['COUNT'];unset($pop['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender,shi_total_transpo&id=" . implode(",", $pop['male']) . "' target='_blank'>" . round($vals['male'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender,shi_total_transpo&id=" . implode(",", $pop['female']) . "' target='_blank'>" . round($vals['female'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td colspan='2'>Average</td>";
            echo "<td>" . round($average_male / (count($data) - 1), 1) . "</td>";
            echo "<td>" . round($average_female / (count($data) - 1), 1) . "</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,family_head_gender,shi_total_transpo&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>