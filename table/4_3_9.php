<?php  
    include('Class_4_3_9.php');
    $class = new Class_4_3_9();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-9</title>
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
    <h3>Table 4.3-9 Monthly Income of Household (LGU Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td colspan="7">Income Range (PhP)</td>
            <td rowspan="2">Total</td>
            <td rowspan="2">Average</td>
        </tr>
        <tr>
            <td><5,000</td>
            <td>5,000-10,000</td>
            <td>10,001-15,000</td>
            <td>15,001-20,000</td>
            <td>20,000-30,000</td>
            <td>30,001-50,000</td>
            <td>>50,001</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];
            foreach ($class->tbl_cols as $colm) {
                $vals[$colm] = $value[$key][$colm]['COUNT'];unset($value[$key][$colm]['COUNT']);
            }
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            echo "<tr>";
                echo "<td rowspan='2'>$mun</td>";
                foreach ($class->tbl_cols as $colm) {
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$key][$colm]) . "' target='_blank'>" . round(count($value[$key][$colm]), 1) . "</a></td>";
                }
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round(count($value[$key]['Total']), 1) . "</a></td>";




                if (count($value[$key]['Total']) == 0) {
                    echo "<td>0</td>";
                } else {
                    echo "<td>" . number_format(round(  $vals['Total'] / count($value[$key]['Total']), 1), 1) . "</td>";
                }
                
            echo "</tr>";

            echo "<tr>";
                foreach ($class->tbl_cols as $colm) {
                    if (count($value[$key]['Total']) == 0) {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round(count($value[$key][$colm]) / count($value[$key]['Total']) * 100, 1) . "%</td>";
                    }
                }
                echo (count($value[$key]['Total']) == 0) ? "<td>0%</td>":"<td>100%</td>";
                echo "<td></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-9 Monthly Income of Household (Baranggay Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td rowspan="2">Baranggay</td>
            <td colspan="7">Income Range (PhP)</td>
            <td rowspan="2">Total</td>
            <td rowspan="2">Average</td>
        </tr>
        <tr>
            <td><5,000</td>
            <td>5,000-10,000</td>
            <td>10,001-15,000</td>
            <td>15,001-20,000</td>
            <td>20,000-30,000</td>
            <td>30,001-50,000</td>
            <td>>50,001</td>
        </tr>
    </thead>
    <tbody>

        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $key = 'Sub Total';
                if ($mun == 'Total') $key = 'Total';
                $vals = [];
                foreach ($class->tbl_cols as $colm) {
                    $vals[$colm] = $value[$brgy][$colm]['COUNT'];unset($value[$brgy][$colm]['COUNT']);
                }
                $vals['Total'] = $value[$brgy]['Total']['COUNT'];unset($value[$brgy]['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . (count($value) + 1) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    foreach ($class->tbl_cols as $colm) {
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$brgy][$colm]) . "' target='_blank'>" . round(count($value[$brgy][$colm]), 1) . "</a></td>";
                    }
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round(count($value[$brgy]['Total']), 1) . "</a></td>";

                    if (count($value[$key]['Total']) == 0) {
                        echo "<td>0</td>";
                    } else {
                        echo "<td>" . number_format(round(  $vals['Total'] / count($value[$key]['Total']), 1), 1) . "</td>";
                    }
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td data-id='percentage'>Percentage</td>";
                foreach ($class->tbl_cols as $colm) {
                    if (count($value[$key]['Total']) == 0) {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round(count($value[$key][$colm]) / count($value[$key]['Total']) * 100, 1) . "%</td>";
                    }
                }
                echo (count($value[$key]['Total']) == 0) ? "<td>0%</td>":"<td>100%</td>";
                echo "<td></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
    $("[data-id='percentage']").last().remove();
</script>
</body>
</html>