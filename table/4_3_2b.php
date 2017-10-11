<?php  
    include('Class_4_3_2b.php');
    $class = new Class_4_3_2b();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-2B</title>
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
    <h3>Table 4.3-2B Structure of Household (LGU Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Single-headed Household</td>
            <td>Widowed-head</td>
            <td>Separated-head</td>
            <td>Others</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $value['Sub Total'][$col]['COUNT'];unset($value['Sub Total'][$col]['COUNT']);
            }

            echo "<tr>";
            echo "<td rowspan='2'>$mun</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,civil_status&id=" . implode(",", $value['Sub Total'][$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
            echo "</tr>";
            echo "<tr>";
                foreach ($class->tbl_cols as $col) {
                    if ($vals['Total'] == "0") {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round( ($vals[$col] /  $vals['Total']) * 100,1) . "%</td>";
                    }
                }
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td rowspan='2'>Total</td>";
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $class->total[$col]['COUNT'];unset($class->total[$col]['COUNT']);
            }
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,civil_status&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
        echo "</tr>";
        echo "<tr>";
                foreach ($class->tbl_cols as $col) {
                    if ($vals['Total'] == "0") {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round( ($vals[$col] /  $vals['Total']) * 100,1) . "%</td>";
                    }
                }
            echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-2B Structure of Household (Barangay Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Barangay</td>
            <td>Single-headed Household</td>
            <td>Widowed-head</td>
            <td>Separated-head</td>
            <td>Others</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $key = $brgy;
                $nVals = [];
                foreach ($class->tbl_cols as $col) {
                    $nVals[$col] = $pop[$col]['COUNT'];unset($pop[$col]['COUNT']);
                }

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . (count($value) + 1) . "'>$mun</td>";$head = 1;
                    echo "<td>$key</td>";
                    foreach ($class->tbl_cols as $col) {
                        echo "<td><a href='/viewer.php?field=uid,address,baranggay,civil_status&id=" . implode(",", $pop[$col]) . "' target='_blank'>" . round($nVals[$col], 1) . "</a></td>";
                    }
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td>Percentage</td>";
                foreach ($class->tbl_cols as $col) {
                    if ($value['Sub Total']['Total']['COUNT'] == "0") {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round( ($value['Sub Total'][$col]['COUNT'] /  $value['Sub Total']['Total']['COUNT']) * 100,1) . "%</td>";
                    }
                }
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td rowspan='2' colspan='2'>Total</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,civil_status&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
        echo "</tr>";
        echo "<tr>";
                foreach ($class->tbl_cols as $col) {
                    if ($vals['Total'] == "0") {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>" . round( ($vals[$col] /  $vals['Total']) * 100,1) . "%</td>";
                    }
                }
            echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,address,baranggay,civil_status&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>