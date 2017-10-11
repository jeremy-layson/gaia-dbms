<?php  
    include('Class_4_4_3.php');
    $class = new Class_4_4_3();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.4-3</title>
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
    <h3>Table 4.4-3 Vulnerable Groups (LGU Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Poor</td>
            <td>Elderly</td>
            <td>Women-headed Household</td>
            <td>Persons Who Need Special Assistance</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];
            foreach (array('sv_10k','sv_60above','sv_hh_woman','sv_special_assist') as $field){ 
                $vals[$field] = $value[$key][$field]['COUNT'];unset($value[$key][$field]['COUNT']);
            }
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            echo "<tr>";
                echo "<td>$mun</td>";
                foreach (array('sv_10k','sv_60above','sv_hh_woman','sv_special_assist') as $field){ 
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,sv_10k,sv_hh_woman,sv_60above,sv_special_assist&id=" . implode(",", $value[$key][$field]) . "' target='_blank'>" . round($vals[$field], 1) . "</a></td>";
                }
                
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,sv_10k,sv_hh_woman,sv_60above,sv_special_assist&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";
        }
        // $total_male = floatval($data['Total']['Total']['male']['COUNT']);
        // $total_female = floatval($data['Total']['Total']['female']['COUNT']);
        // $total_all = floatval($data['Total']['Total']['Total']['COUNT']);
        
        // echo "<tr>";
        //     echo "<td>Percentage</td>";
        //     echo "<td>" . round(($total_male / $total_all) * 100, 1) . "%</td>";
        //     echo "<td>" . round(($total_female / $total_all) * 100, 1) . "%</td>";
        //     echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        // echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.4-3 Vulnerable Groups (Barangay Level)</h3>
    <thead>
        <tr>
            <td>City/Municipality</td>
            <td>Barangay</td>
            <td>Poor</td>
            <td>Elderly</td>
            <td>Women-headed Household</td>
            <td>Persons Who Need Special Assistance</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            foreach ($value as $brgy => $pop) {

                $vals = [];
                foreach (array('sv_10k','sv_60above','sv_hh_woman','sv_special_assist') as $field){ 
                    $vals[$field] = $pop[$field]['COUNT'];unset($pop[$field]['COUNT']);
                }
                $vals['Total'] = $pop['Total']['COUNT'];unset($pop['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    foreach (array('sv_10k','sv_60above','sv_hh_woman','sv_special_assist') as $field){ 
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,sv_10k,sv_hh_woman,sv_60above,sv_special_assist&id=" . implode(",", $value[$brgy][$field]) . "' target='_blank'>" . round($vals[$field], 1) . "</a></td>";
                    }
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,sv_10k,sv_hh_woman,sv_60above,sv_special_assist&id=" . implode(",", $pop['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        // echo "<tr>";
        //     echo "<td colspan='2'>Percentage</td>";
        //     echo "<td>" . round(($total_male / $total_all) * 100, 1) . "%</td>";
        //     echo "<td>" . round(($total_female / $total_all) * 100, 1) . "%</td>";
        //     echo "<td>" . round(($total_all / $total_all) * 100, 1) . "%</td>";
        // echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,sv_10k,sv_hh_woman,sv_60above,sv_special_assist&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>