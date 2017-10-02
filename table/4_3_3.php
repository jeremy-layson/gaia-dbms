<?php  
    include('Class_4_3_3.php');
    $class = new Class_4_3_3();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-3</title>
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
    <h3>Table 4.3-3 Affected Population by Age (LGU Level)</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>0-5</td>
            <td>6-14</td>
            <td>15-30</td>
            <td>31-59</td>
            <td>60 Above</td>
            <td>Other</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];

            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $value[$key][$col]['COUNT'];unset($value[$key][$col]['COUNT']);
            }

            echo "<tr>";
                echo "<td rowspan='2'>$mun</td>";
                foreach ($class->tbl_cols as $col) {
                    echo "<td><a href='/viewer.php?field=uid,address,baranggay,ses_05_male,ses_05_female,ses_614_male,ses_614_female,ses_1530_male,ses_1530_female,ses_3159_male,ses_3159_female,ses_60_male,ses_60_female,ses_other_male,ses_other_female,ses_total_male,ses_total_female&id=" . implode(",", $value[$key][$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                }
            echo "</tr>";
            echo "<tr>";
                foreach ($class->tbl_cols as $col) {
                    echo "<td>" . round( ($vals[$col] / $vals['ses_total']) * 100 ,1) . "%</td>";
                }
            echo "</tr>";
        }

        foreach ($class->tbl_cols as $col) {
            $range[$col] = floatval($data['Total']['Total'][$col]['COUNT']);
        }
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td>" . round(($range[$col] / $range['ses_total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-3 Affected Population by Age (Baranggay Level)</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Baranggay</td>
            <td>0-5</td>
            <td>6-14</td>
            <td>15-30</td>
            <td>31-59</td>
            <td>60 Above</td>
            <td>Other</td>
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
                foreach ($class->tbl_cols as $col) {
                    $vals[$col] = $value[$key][$col]['COUNT'];unset($value[$key][$col]['COUNT']);
                }

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$key</td>";
                    foreach ($class->tbl_cols as $col) {
                        echo "<td><a href='/viewer.php?field=uid,address,baranggay,ses_05_male,ses_05_female,ses_614_male,ses_614_female,ses_1530_male,ses_1530_female,ses_3159_male,ses_3159_female,ses_60_male,ses_60_female,ses_other_male,ses_other_female,ses_total_male,ses_total_female&id=" . implode(",", $value[$key][$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                    }
                echo "</tr>";
            }
        }

        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td>" . round(($range[$col] / $range['ses_total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,ses_05_male,ses_05_female,ses_614_male,ses_614_female,ses_1530_male,ses_1530_female,ses_3159_male,ses_3159_female,ses_60_male,ses_60_female,ses_other_male,ses_other_female,ses_total_male,ses_total_female&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>