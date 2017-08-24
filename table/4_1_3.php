<?php  
    include('Class_4_1_3.php');
    $class = new Class_4_1_3();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.1-3</title>
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
    <h3>Table 4.1-3 Legal PAFs by LGUs</h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td rowspan="2">Affected Baranggays</td>
            <td colspan="2">Structure Owners (Residential)</td>
            <td colspan="2">Structure Owners (CIBEs)</td>
            <td colspan="2">Structure Owners (Institutional)</td>
            <td colspan="2">Renters (Residential)</td>
            <td colspan="2">Absentee Structure Owner</td>
            <td colspan="2">Land Owners</td>
            <td colspan="2">Commercial Stall Tenants</td>
            <td colspan="2">Total</td>
        </tr>
        <tr>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
            <td>Stay</td>
            <td>Move</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            foreach ($value as $brgy => $pop) {
                $key = $brgy;
                $vals = [];
                foreach ($class->tbl_cols as $field) {
                    $vals[$field]['stay'] = count($pop[$field]['stay']);
                    $vals[$field]['move'] = count($pop[$field]['move']);
                }

                echo "<tr data-id='$brgy'>";
                    if ($head == 0) echo "<td rowspan='" . count($value) . "'>$mun</td>";$head = 1;
                    echo "<td>$key</td>";
                    foreach ($class->tbl_cols as $field) {
                        echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $pop[$field]['stay']) . "'>" . $vals[$field]['stay'] . "</a></td>";
                        echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $pop[$field]['move']) . "'>" . $vals[$field]['move'] . "</a></td>";
                    }
                echo "</tr>";
            }
        }
        echo "<tr data-id='grand'>";
            echo "<td colspan='2'>Grand Total</td>";
            foreach ($class->tbl_cols as $field) {
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['stay']) . "'>" . count($class->total[$field]['stay']) . "</a></td>";
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['move']) . "'>" . count($class->total[$field]['move']) . "</a></td>";
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>