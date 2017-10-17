<?php  
    include('Class_4_35.php');
    $class = new Class_4_35();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.35</title>
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
    <h3>Table 4.35 Primary Occupation</h3>
    <thead>
        <tr>
            <td>Occupation</td>
            <td>Husband</td>
            <td>Wife</td>
            <td>Member</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data as $key => $value) {
            $husband = isset($value['husband']) === TRUE ? $value['husband']['COUNT'] : "0";
            $wife = isset($value['wife']) === TRUE ? $value['wife']['COUNT'] : "0";
            $member = isset($value['member']) === TRUE ? $value['member']['COUNT'] : "0";
            $total = $value['Total']['COUNT'];

            echo "<tr>";
                echo "<td>" . $key . "</td>";
                echo "<td>$husband</td>";
                echo "<td>$wife</td>";
                echo "<td>$member</td>";
                echo "<td>$total</td>";
            echo "</tr>";
        }

        echo "<tr>";
            echo "<td>Grand Total</td>";
            echo "<td>" . $class->total['husband']['COUNT'] . "</td>";
            echo "<td>" . $class->total['wife']['COUNT'] . "</td>";
            echo "<td>" . $class->total['member']['COUNT'] . "</td>";
            echo "<td>" . $class->total['Total']['COUNT'] . "</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>

<a target="_blank" href="/viewer.php?field=uid,address,baranggay,religion&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>