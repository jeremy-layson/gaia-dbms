<?php  
    include('Class_4_3_4.php');
    $class = new Class_4_3_4();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-4</title>
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
    <h3>Table 4.3-4 Education Attainment of Household Members (LGU Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td rowspan="2">No Schooling</td>
            <td rowspan="2">Pre-Elementary</td>
            <td colspan="2">Elementary</td>
            <td colspan="2">High School</td>
            <td colspan="2">College</td>
            <td colspan="2">Vocational</td>
            <td rowspan="2">Not of School Age</td>
            <td rowspan="2">No Answer</td>
            <td rowspan="2">Total</td>
        </tr>
        <tr>
            <td>Level</td>
            <td>Graduate</td>
            <td>Level</td>
            <td>Graduate</td>
            <td>Level</td>
            <td>Graduate</td>
            <td>Level</td>
            <td>Graduate</td>
            
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
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $col) {
                    echo "<td><a href='/viewer.php?field=uid,address,baranggay,ses_ed_none_male,ses_ed_none_female,ses_ed_pre_male,ses_ed_pre_female,ses_ed_elem_male,ses_ed_elem_female,ses_ed_elemgrad_male,ses_ed_elemgrad_female,ses_ed_hs_male,ses_ed_hs_female,ses_ed_hsgrad_male,ses_ed_hsgrad_female,ses_ed_college_male,ses_ed_college_female,ses_ed_collegegrad_male,ses_ed_collegegrad_female,ses_ed_voc_male,ses_ed_voc_female,ses_ed_vocgrad_male,ses_ed_vocgrad_female,ses_ed_notage_male,ses_ed_notage_female,ses_ed_other_male,ses_ed_other_female&id=" . implode(",", $value[$key][$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                }
            echo "</tr>";
        }

        foreach ($class->tbl_cols as $col) {
            $range[$col] = floatval($data['Total']['Total'][$col]['COUNT']);
        }
        
        echo "<tr>";
            echo "<td>Percentage</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td>" . round(($range[$col] == 0 ? '0': $range[$col] / $range['ses_ed_total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>

<table border="1">
    <h3>Table 4.3-4 Education Attainment of Household Members (Baranggay Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td rowspan="2">Baranggay</td>
            <td rowspan="2">No Schooling</td>
            <td rowspan="2">Pre-Elementary</td>
            <td colspan="2">Elementary</td>
            <td colspan="2">High School</td>
            <td colspan="2">College</td>
            <td colspan="2">Vocational</td>
            <td rowspan="2">Not of School Age</td>
            <td rowspan="2">No Answer</td>
            <td rowspan="2">Total</td>
        </tr>
        <tr>
            <td>Level</td>
            <td>Graduate</td>
            <td>Level</td>
            <td>Graduate</td>
            <td>Level</td>
            <td>Graduate</td>
            <td>Level</td>
            <td>Graduate</td>
            
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
                        echo "<td><a href='/viewer.php?field=uid,address,baranggay,ses_ed_none_male,ses_ed_none_female,ses_ed_pre_male,ses_ed_pre_female,ses_ed_elem_male,ses_ed_elem_female,ses_ed_elemgrad_male,ses_ed_elemgrad_female,ses_ed_hs_male,ses_ed_hs_female,ses_ed_hsgrad_male,ses_ed_hsgrad_female,ses_ed_college_male,ses_ed_college_female,ses_ed_collegegrad_male,ses_ed_collegegrad_female,ses_ed_voc_male,ses_ed_voc_female,ses_ed_vocgrad_male,ses_ed_vocgrad_female,ses_ed_notage_male,ses_ed_notage_female,ses_ed_other_male,ses_ed_other_female&id=" . implode(",", $value[$key][$col]) . "' target='_blank'>" . round($vals[$col], 1) . "</a></td>";
                    }
                echo "</tr>";
            }
        }

        echo "<tr>";
            echo "<td colspan='2'>Percentage</td>";
            foreach ($class->tbl_cols as $col) {
                echo "<td>" . round(($range[$col] == 0 ? '0': $range[$col] / $range['ses_ed_total']) * 100, 1) . "%</td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,asset_num,address,baranggay,ses_ed_none_male,ses_ed_none_female,ses_ed_pre_male,ses_ed_pre_female,ses_ed_elem_male,ses_ed_elem_female,ses_ed_elemgrad_male,ses_ed_elemgrad_female,ses_ed_hs_male,ses_ed_hs_female,ses_ed_hsgrad_male,ses_ed_hsgrad_female,ses_ed_college_male,ses_ed_college_female,ses_ed_collegegrad_male,ses_ed_collegegrad_female,ses_ed_voc_male,ses_ed_voc_female,ses_ed_vocgrad_male,ses_ed_vocgrad_female,ses_ed_notage_male,ses_ed_notage_female,ses_ed_other_male,ses_ed_other_female&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>