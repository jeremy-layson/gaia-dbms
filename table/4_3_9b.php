<?php  
    include('Class_4_3_9b.php');
    $class = new Class_4_3_9b();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-9b</title>
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
    <h3>Table 4.3-9b - Monthly Income of Legal PAFs (LGU Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td colspan="7">Income Range (PhP)</td>
            <td rowspan="2">Total</td>
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
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $colm) {
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$key][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                }
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";
        }
        foreach ($class->tbl_cols as $colm) {
            $range[$colm] = floatval($data['Total']['Total'][$colm]['COUNT']);
        }
        $total_all = floatval($data['Total']['Total']['Total']['COUNT']);
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $colm) {
                echo "<td>" . round(($range[$colm] / $total_all) * 100, 1) . "%</td>";
            }
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-9b - Monthly Income of Legal PAFs (Barangay Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td rowspan="2">Barangay</td>
            <td colspan="7">Income Range (PhP)</td>
            <td rowspan="2">Total</td>
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
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    foreach ($class->tbl_cols as $colm) {
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$brgy][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                    }
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,shi_total_hh_income&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        foreach ($class->tbl_cols as $colm) {
            $range[$colm] = floatval($data['Total']['Total'][$colm]['COUNT']);
        }
        $total_all = floatval($data['Total']['Total']['Total']['COUNT']);
        
        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            foreach ($class->tbl_cols as $colm) {
                echo "<td>" . round(($range[$colm] / $total_all) * 100, 1) . "%</td>";
            }
            echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        echo "</tr>";
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
</script>
</body>
</html>