<?php  
    include('Class_4_3_23.php');
    $class = new Class_4_3_23();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-23</title>
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
    <h3>Table 4.3-23 Household Assets (LGU Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Tricycle</td>
            <td>Motorcycle</td>
            <td>Computer</td>
            <td>Electric Fan</td>
            <td>Television</td>
            <td>Radio</td>
            <td>Music Component</td>
            <td>Amplifier</td>
            <td>Refrigerator</td>
            <td>Stove</td>
            <td>Super Kalan</td>
            <td>Portable DVD</td>
            <td>Car</td>
            <td>Gadget</td>
            <td>Trike/Bike</td>
            <td>Rice Cooker</td>
            <td>Jeepney</td>
            <td>Water Purifier</td>
            <td>Aircon</td>
            <td>Washing Machine</td>
            <td>Sewing Machine</td>
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
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,ha_tricycle,ha_motorcycle,ha_computer,ha_electricfan,ha_tv,ha_radio,ha_music,ha_amplifier,ha_refrigerator,ha_stove,ha_superkalan,ha_dvd,ha_car,ha_gadget,ha_bike,ha_ricecooker,ha_jeep,ha_waterpurifier,ha_aircon,ha_washingmachine,ha_sewingmachine&id=" . implode(",", $value[$key][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
                }
            echo "</tr>";
        }
        foreach ($class->tbl_cols as $colm) {
            $range[$colm] = floatval($data['Total']['Total'][$colm]['COUNT']);
        }
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $colm) {
                echo "<td>" . round(($range[$colm] / $range['Total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-23 Household Assets (Barangay Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Barangay</td>
            <td>Tricycle</td>
            <td>Motorcycle</td>
            <td>Computer</td>
            <td>Electric Fan</td>
            <td>Television</td>
            <td>Radio</td>
            <td>Music Component</td>
            <td>Amplifier</td>
            <td>Refrigerator</td>
            <td>Stove</td>
            <td>Super Kalan</td>
            <td>Portable DVD</td>
            <td>Car</td>
            <td>Gadget</td>
            <td>Trike/Bike</td>
            <td>Rice Cooker</td>
            <td>Jeepney</td>
            <td>Water Purifier</td>
            <td>Aircon</td>
            <td>Washing Machine</td>
            <td>Sewing Machine</td>
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
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,ha_tricycle,ha_motorcycle,ha_computer,ha_electricfan,ha_tv,ha_radio,ha_music,ha_amplifier,ha_refrigerator,ha_stove,ha_superkalan,ha_dvd,ha_car,ha_gadget,ha_bike,ha_ricecooker,ha_jeep,ha_waterpurifier,ha_aircon,ha_washingmachine,ha_sewingmachine&id=" . implode(",", $value[$brgy][$colm]) . "' target='_blank'>" . round($vals[$colm], 1) . "</a></td>";
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
                echo "<td>" . round(($range[$colm] / $range['Total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,ha_tricycle,ha_motorcycle,ha_computer,ha_electricfan,ha_tv,ha_radio,ha_music,ha_amplifier,ha_refrigerator,ha_stove,ha_superkalan,ha_dvd,ha_car,ha_gadget,ha_bike,ha_ricecooker,ha_jeep,ha_waterpurifier,ha_aircon,ha_washingmachine,ha_sewingmachine&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>