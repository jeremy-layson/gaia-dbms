<?php  
    include('Class_4_5_7.php');
    $class = new Class_4_5_7();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.5-7</title>
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
    <h3>Table 4.5-7 Most Desired Services and Facilities in Relocation Site (LGU Level)</h3>
    <thead>
        <tr>
            <td>Municipalities and Cities</td>
            <td>Health Center</td>
            <td>Private Clinic</td>
            <td>Public Hospital</td>
            <td>Police Outpost</td>
            <td>Livelihood Center</td>
            <td>Market</td>
            <td>School</td>
            <td>Baranggay Hall</td>
            <td>Transporation</td>
            <td>Others</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $key = 'Sub Total';
            if ($mun == 'Total') $key = 'Total';
            $vals = [];
            foreach ($class->tbl_cols as $field){ 
                $vals[$field] = $value[$key][$field]['COUNT'];unset($value[$key][$field]['COUNT']);
            }
            $vals['Total'] = $value[$key]['Total']['COUNT'];unset($value[$key]['Total']['COUNT']);

            echo "<tr>";
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $field){ 
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,rpo_desired_service_health_center,rpo_desired_service_private_clinic,rpo_desired_service_gov_hospital,rpo_desired_service_police_outpost,rpo_desired_service_livelihood,rpo_desired_service_market,rpo_desired_service_school,rpo_desired_service_brgy_hall,rpo_desired_service_transport,rpo_desired_service_others&id=" . implode(",", $value[$key][$field]) . "' target='_blank'>" . round($vals[$field], 1) . "</a></td>";
                }
                
                echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,rpo_desired_service_health_center,rpo_desired_service_private_clinic,rpo_desired_service_gov_hospital,rpo_desired_service_police_outpost,rpo_desired_service_livelihood,rpo_desired_service_market,rpo_desired_service_school,rpo_desired_service_brgy_hall,rpo_desired_service_transport,rpo_desired_service_others&id=" . implode(",", $value[$key]['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
            echo "</tr>";
        }

        foreach ($class->tbl_cols as $field) $total[$field] = floatval($data['Total']['Total'][$field]['COUNT']);
        $total['Total'] = floatval($data['Total']['Total']['Total']['COUNT']);
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $field) echo "<td>" . round(($total[$field] / $total['Total']) * 100, 1) . "%</td>";
            echo "<td>" . round(($total['Total'] / $total['Total']) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.5-7 Most Desired Services and Facilities in Relocation Site (Baranggay Level)</h3>
    <thead>
        <tr>
            <td width='200px'>Municipalities and Cities</td>
            <td width="200px">Baranggay</td>
            <td>Health Center</td>
            <td>Private Clinic</td>
            <td>Public Hospital</td>
            <td>Police Outpost</td>
            <td>Livelihood Center</td>
            <td>Market</td>
            <td>School</td>
            <td>Baranggay Hall</td>
            <td>Transporation</td>
            <td>Others</td>
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
                foreach ($class->tbl_cols as $field){ 
                    $vals[$field] = $pop[$field]['COUNT'];unset($pop[$field]['COUNT']);
                }
                $vals['Total'] = $pop['Total']['COUNT'];unset($pop['Total']['COUNT']);

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$brgy</td>";
                    foreach ($class->tbl_cols as $field){ 
                        echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,rpo_desired_service_health_center,rpo_desired_service_private_clinic,rpo_desired_service_gov_hospital,rpo_desired_service_police_outpost,rpo_desired_service_livelihood,rpo_desired_service_market,rpo_desired_service_school,rpo_desired_service_brgy_hall,rpo_desired_service_transport,rpo_desired_service_others&id=" . implode(",", $value[$brgy][$field]) . "' target='_blank'>" . round($vals[$field], 1) . "</a></td>";
                    }
                    echo "<td><a href='/viewer.php?field=uid,asset_num,address,baranggay,rpo_desired_service_health_center,rpo_desired_service_private_clinic,rpo_desired_service_gov_hospital,rpo_desired_service_police_outpost,rpo_desired_service_livelihood,rpo_desired_service_market,rpo_desired_service_school,rpo_desired_service_brgy_hall,rpo_desired_service_transport,rpo_desired_service_others&id=" . implode(",", $pop['Total']) . "' target='_blank'>" . round($vals['Total'], 1) . "</a></td>";
                echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            foreach ($class->tbl_cols as $field) echo "<td>" . round(($total[$field] / $total['Total']) * 100, 1) . "%</td>";
            echo "<td>" . round(($total['Total'] / $total['Total']) * 100, 1) . "%</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,rpo_desired_service_health_center,rpo_desired_service_private_clinic,rpo_desired_service_gov_hospital,rpo_desired_service_police_outpost,rpo_desired_service_livelihood,rpo_desired_service_market,rpo_desired_service_school,rpo_desired_service_brgy_hall,rpo_desired_service_transport,rpo_desired_service_others&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>