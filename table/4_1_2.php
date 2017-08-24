<?php  
    include('Class_4_1_2.php');
    $class = new Class_4_1_2();
    $data = $class->getData();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Table 4.1-2</title>
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
<h3>Table 4.1-2 Number of Affected PAFs</h3>
<table border="1">
    <thead>
        <tr>
            <td rowspan="2">Type of Loss</td>
            <td colspan="3">Number of PAFs</td>
            <td colspan="3">Number of Affected PAPs</td>
        </tr>
        <tr>
            <td>Legal</td>
            <td>ISFs</td>
            <td>Total</td>
            <td>Legal</td>
            <td>ISFs</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $displacement => $value) {
            echo "<tr><td colspan='7'>" . ($displacement == 'stay' ? 'Not required for displacement':'Required for displacement') . "</td></tr>";
            foreach ($value as $category => $value2) {
            echo "<tr>";
                echo "<td>" . $class->definition[$category] . "</td>";
                echo "<td>" . '<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=' . implode(',', $value2['PAF_LEGAL']) . '">' . count($value2['PAF_LEGAL']) . '</a></td>';
                echo "<td>" . '<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=' . implode(',', $value2['PAF_ISF']) . '">' . count($value2['PAF_ISF']) . '</a></td>';
                echo "<td>" . '<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=' . implode(',', $value2['PAF_Total']) . '">' . count($value2['PAF_Total']) . '</a></td>';
                echo "<td>" . ($value2['PAP_LEGAL']) . "</td>";
                echo "<td>" . ($value2['PAP_ISF']) . "</td>";
                echo "<td>" . ($value2['PAP_Total']) . "</td>";
            echo "</tr>";
            }
        }
        echo "<tr>";
            echo "<td>Grand Total</td>";
            echo "<td>" . '<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=' . implode(',', $data['stay']['subtotal']['PAF_LEGAL']) . "," . implode(',', $data['displace']['subtotal']['PAF_LEGAL']) . '">' . (count($data['stay']['subtotal']['PAF_LEGAL']) + count($data['displace']['subtotal']['PAF_LEGAL'])) . '</a></td>';
            echo "<td>" . '<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=' . implode(',', $data['stay']['subtotal']['PAF_ISF']) . "," . implode(',', $data['displace']['subtotal']['PAF_ISF']) . '">' . (count($data['stay']['subtotal']['PAF_ISF']) + count($data['displace']['subtotal']['PAF_ISF'])) . '</a></td>';
            echo "<td>" . '<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=' . implode(',', $data['stay']['subtotal']['PAF_Total']) . "," . implode(',', $data['displace']['subtotal']['PAF_Total']) . '">' . (count($data['stay']['subtotal']['PAF_Total']) + count($data['displace']['subtotal']['PAF_Total'])) . '</a></td>';
            echo "<td>" . ($data['stay']['subtotal']['PAP_LEGAL'] + $data['displace']['subtotal']['PAP_LEGAL']) . "</td>";
            echo "<td>" . ($data['stay']['subtotal']['PAP_ISF'] + $data['displace']['subtotal']['PAP_ISF']) . "</td>";
            echo "<td>" . ($data['stay']['subtotal']['PAP_Total'] + $data['displace']['subtotal']['PAP_Total']) . "</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,hh_members,structure_owner,structure_use,structure_dp,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Grand Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>