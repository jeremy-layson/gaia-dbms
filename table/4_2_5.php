<?php  
    include('Class_4_2_5.php');
    $class = new Class_4_2_5();
    $data = $class->getData();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Table 4.2-5</title>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <style type="text/css">
        table {
            border-collapse: collapse;
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
<h3>Table 4.2-5 Affected Trees</h3>
<table border="1">
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Affected Barangays</td>
            <td width="150px">Trees (Fruit Bearing)</td>
            <td width="150px">Trees (Timber, Non-Fruit Bearing)</td>
            <td width="150px">Plants / Cash Trees</td>
            <td width="150px">Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $trees) {
                $vals = [];
                $vals['trees_fb'] = $trees['trees_fb']['COUNT'];unset($trees['trees_fb']['COUNT']);
                $vals['trees_nonfb'] = $trees['trees_nonfb']['COUNT'];unset($trees['trees_nonfb']['COUNT']);
                $vals['trees_cash'] = $trees['trees_cash']['COUNT'];unset($trees['trees_cash']['COUNT']);
                $vals['Total'] = $trees['Total']['COUNT'];unset($trees['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,trees_fb,trees_nonfb,trees_cash&id=" . implode(",", $trees['trees_fb']) . "' target='_blank'>" . round($vals['trees_fb'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,trees_fb,trees_nonfb,trees_cash&id=" . implode(",", $trees['trees_nonfb']) . "' target='_blank'>" . round($vals['trees_nonfb'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,trees_fb,trees_nonfb,trees_cash&id=" . implode(",", $trees['trees_cash']) . "' target='_blank'>" . round($vals['trees_cash'], 1) . "</a></td>";
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,trees_fb,trees_nonfb,trees_cash&id=" . implode(",", $trees['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }

        }
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,trees_fb,trees_nonfb,trees_cash&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Grand Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>