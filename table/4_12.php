<?php  
    include('Class_4_12.php');
    $class = new Class_4_12();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.12</title>
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
    <h3>Table 4.12 Proof of Ownership Presented by Legal Landowners per LGU</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td rowspan="2">Title</td>
            <td rowspan="2">Real Estate Tax</td>
            <td rowspan="2">Deed/Mortgage</td>
            <td rowspan="2">Land Plan</td>
            <td rowspan="2">Barangay Residency Certificate</td>
            <td rowspan="2">Land Rights</td>
            <td rowspan="2">Lease Contact</td>
            <td rowspan="2">Certificate of Lot Award</td>
            <td rowspan="2">No Answer</td>
            <td colspan="2">Total</td>
        </tr>
        <tr>
            <td>No.</td>
            <td>%</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $value[$col]['COUNT'];unset($value[$col]['COUNT']);
            }

            echo "<tr>";
            echo "<td>$mun</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,kd_title,kd_real,kd_deed,kd_landplan,kd_brgycert,kd_landrights,kd_lease,kd_certaward&id=" . implode(",", $value[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
            $percent = round( ($vals['Total'] / $class->total['Total']['COUNT']) * 100,1);
            echo "<td>$percent%</td>";
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td>Total</td>";
            $vals = [];
            foreach ($class->tbl_cols as $col) {
                $vals[$col] = $class->total[$col]['COUNT'];unset($class->total[$col]['COUNT']);
            }
            foreach ($class->tbl_cols as $col) {
                echo "<td><a href='/viewer.php?field=uid,address,baranggay,kd_title,kd_real,kd_deed,kd_landplan,kd_brgycert,kd_landrights,kd_lease,kd_certaward&id=" . implode(",", $class->total[$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
            }
            echo "<td>100%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<a target="_blank" href="/viewer.php?field=uid,address,baranggay,kd_title,kd_real,kd_deed,kd_landplan,kd_brgycert,kd_landrights,kd_lease,kd_certaward&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>