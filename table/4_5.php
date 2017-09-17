<?php  
    include('Class_4_5.php');
    $class = new Class_4_5();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.5</title>
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
    <h3>Table 4.5 - Number of Legal PAFs at Valenzuela Depot by Type of Loss  </h3>
    <thead>
        <tr>
            <td rowspan="2">Municipalities and Cities</td>
            <td colspan="3">Land Owners</td>
            <td colspan="3">Structure Owners (Residential)</td>
            <td colspan="3">Structure Owners (Mixed Use)</td>
            <td colspan="3">Structure Owners (CIBEs)</td>
            <td colspan="3">Renters (Residential)</td>
            <td colspan="3">Absentee Structure Owner</td>
            <td colspan="3">Workers (Employees of CIBEs)</td>
            <td colspan="3">Total</td>
        </tr>
        <tr>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $head = 0;
            $vals = [];
            foreach ($class->tbl_cols as $field) {
                $vals[$field]['stay'] = count($value[$field]['stay']);
                $vals[$field]['move'] = count($value[$field]['move']);
                $vals[$field]['total'] = count($value[$field]['total']);
                   
            }

            echo "<tr data-id='$mun'>";
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $field) {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $value[$field]['move']) . "'>" . $vals[$field]['move'] . "</a></td>";
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $value[$field]['stay']) . "'>" . $vals[$field]['stay'] . "</a></td>";
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $value[$field]['total']) . "'>" . $vals[$field]['total'] . "</a></td>";
                       
                }
            echo "</tr>";
        }
        echo "<tr data-id='grand'>";
            echo "<td rowspan='2'>Grand Total</td>";
            foreach ($class->tbl_cols as $field) {
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['move']) . "'>" . count($class->total[$field]['move']) . "</a></td>";
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['stay']) . "'>" . count($class->total[$field]['stay']) . "</a></td>";
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['total']) . "'>" . count($class->total[$field]['total']) . "</a></td>";
            }
        echo "</tr>";

        //percentage
        echo "<tr>";
            foreach ($class->tbl_cols as $field) {
                echo "<td>" . round( ( count($class->total[$field]['move']) / count($class->total['total']['move']) ) * 100 , 1) . "%</td>";
                echo "<td>" . round( ( count($class->total[$field]['stay']) / count($class->total['total']['stay']) ) * 100 , 1) . "%</td>";
                echo "<td>" . round( ( count($class->total[$field]['total']) / count($class->total['total']['total']) * 100 ) , 1) . "%</td>";
                   
            }
        echo "</tr>";
        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>
<!-- <a href="#" id="depot">Depot Only</a>
<a href="#" id="all">Show All</a> -->

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');

    $("#depot").on('click', function(){
        $.each($('tbody tr'), function(index, data){
            if ($(this).attr('data-id') != "Valenzuela (Depot)") {
                $(this).hide();
            }
        });
    });

    $("#all").on('click', function(){
        $.each($('tbody tr'), function(index, data){
            $(this).show();
        });
    });
</script>
</body>
</html>