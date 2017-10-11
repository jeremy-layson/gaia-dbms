<?php  
    include('Class_4_3_10c.php');
    $class = new Class_4_3_10c();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-10c</title>
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
    <h3>Table 4.3-10c - Monthly Expenditure of ISFs (LGU Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td><3,000</td>
            <td>3,000-5,000</td>
            <td>5,001-10,000</td>
            <td>10,001-30,000</td>
            <td>>30,001</td>
            <td>Total</td>
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
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,he_total_expenses&id=" . implode(",", $value[$key][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                }
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,he_total_expenses&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";

            echo "<tr>";
                foreach ($class->tbl_cols as $colm) {
                    if ($vals['Total'] == "0") {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round( ($vals[$colm] / $vals['Total']) * 100 , 1) . "%</td>";
                    }
                }
                echo ($vals['Total'] == "0") ? "<td>0%</td>":"<td>100%</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-10c - Monthly Expenditure of ISFs (Barangay Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Barangay</td>
            <td><3,000</td>
            <td>3,000-5,000</td>
            <td>5,001-10,000</td>
            <td>10,001-30,000</td>
            <td>>30,001</td>
            <td>Total</td>
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
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,he_total_expenses&id=" . implode(",", $value[$brgy][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                    }
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,he_total_expenses&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td data-id='percentage'>Percentage</td>";
                foreach ($class->tbl_cols as $colm) {
                    if ($vals['Total'] == "0") {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round( ($vals[$colm] / $vals['Total']) * 100 , 1) . "%</td>";
                    }
                }
                echo ($vals['Total'] == "0") ? "<td>0%</td>":"<td>100%</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,he_total_expenses&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

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