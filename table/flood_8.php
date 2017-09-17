<?php  
    include('Class_flood_8.php');
    $class = new Class_flood_8();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 8. Duration of Flood</title>
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
    <h3>Table 8. Duration of Flood (LGU Level)</h3>
    <thead>
        <tr>
            <td>Cities/Municipalities</td>
            <td>&lt;6-hrs</td>
            <td>6-hrs - &lt;12-hrs</td>
            <td>12-hrs - &lt;24-hrs</td>
            <td>24-hrs - &lt;96-hrs</td>
            <td>96-hrs - 168-hrs</td>
            <td>&gt;1-week</td>
            <td>No Idea</td>
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

            echo "<tr>";
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $colm) {
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,flood_5years,flood_subside&id=" . implode(",", $value[$key][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                }
            echo "</tr>";
        }
        foreach ($class->tbl_cols as $colm) {
            $range[$colm] = floatval($data['Total']['Total'][$colm]['COUNT']);
        }
        $total_all = floatval($data['Total']['Total']['total']['COUNT']);
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $colm) {
                echo "<td>" . round(($range[$colm] / $total_all) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 8. Duration of Flood (Baranggay Level)</h3>
    <thead>
        <tr>
            <td>Cities/Municipalities</td>
            <td>Baranggay</td>
            <td>&lt;6-hrs</td>
            <td>6-hrs - &lt;12-hrs</td>
            <td>12-hrs - &lt;24-hrs</td>
            <td>24-hrs - &lt;96-hrs</td>
            <td>96-hrs - 168-hrs</td>
            <td>&gt;1-week</td>
            <td>No Idea</td>
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

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    foreach ($class->tbl_cols as $colm) {
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,flood_5years,flood_subside&id=" . implode(",", $value[$brgy][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                    }
                echo "</tr>";
            }
        }
        foreach ($class->tbl_cols as $colm) {
            $range[$colm] = floatval($data['Total']['Total'][$colm]['COUNT']);
        }
        
        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            foreach ($class->tbl_cols as $colm) {
                echo "<td>" . round(($range[$colm] / $total_all) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,flood_5years,flood_subside&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>