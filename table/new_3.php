<?php  
    include('Class_New_3.php');
    $class = new Class_New_3();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table ##. ISF Rental Fees</title>
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
    <h3>Table ##. ISF Rental Fees (LGU Level)</h3>
    <thead>
        <tr>
            <td>Cities/Municipalities</td>
            <td>< 1000</td>
            <td>1,000 - 1,999</td>
            <td>2,000 - 2,999</td>
            <td>3,000 - 3,999</td>
            <td>4,000 - 4,999</td>
            <td>5,000 - 5,999</td>
            <td>> 6,000</td>
            
            <td>Total</td>
            <td>Percent</td>
        </tr>
    </thead>
    <tbody>
        <?php 

        foreach ($class->tbl_cols as $colm) {
            $range[$colm] = floatval($data['Total']['Total'][$colm]['COUNT']);
        }
        $total_all = floatval($data['Total']['Total']['total']['COUNT']);


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
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,he_rental_amort,dsa_rent_cost&id=" . implode(",", $value[$key][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                }
                $percent = round( ($vals['total'] / $total_all) * 100 , 1);
                echo "<td>$percent%</td>";
            echo "</tr>";
        }
        
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $colm) {
                echo "<td>" . round(($range[$colm] / $total_all) * 100, 1) . "%</td>";
            }
        echo "<td></td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table ##. ISF Rental Fees (Baranggay Level)</h3>
    <thead>
        <tr>
            <td>Cities/Municipalities</td>
            <td>Baranggay</td>
            <td>< 1000</td>
            <td>1,000 - 1,999</td>
            <td>2,000 - 2,999</td>
            <td>3,000 - 3,999</td>
            <td>4,000 - 4,999</td>
            <td>5,000 - 5,999</td>
            <td>> 6,000</td>
            <td>Total</td>
            <td>Percent</td>
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
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,he_rental_amort,dsa_rent_cost&id=" . implode(",", $value[$brgy][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                    }
                $percent = round( ($vals['total'] / $total_all) * 100 , 1);
                echo "<td>$percent%</td>";
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
        echo "<td></td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,he_rental_amort,dsa_rent_cost&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>