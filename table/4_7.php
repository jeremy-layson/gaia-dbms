<?php  
    include('Class_4_7.php');
    $class = new Class_4_7();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.7</title>
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
    <h3>Table 4.7 Number of ISFs at Valenzuela Depot by Type of Loss</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td colspan="2">Structure Owners (Residential)</td>
            <!-- <td colspan="2">Structure Owners (CIBEs)</td> -->
            <td colspan="2">Sharer</td>
            <td colspan="2">Commercial Stall Tenants</td>
            <td colspan="2">Vendors</td>
            <td colspan="2">Total PAFs</td>
        </tr>
        <tr>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data as $mun => $value) {
            $vals = [];
            foreach ($class->tbl_cols as $field) {
                $vals[$field]['stay'] = count($value[$field]['stay']);
                $vals[$field]['move'] = count($value[$field]['move']);
            }

            echo "<tr data-id='$mun'>";
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $field) {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $value[$field]['move']) . "'>" . $vals[$field]['move'] . "</a></td>";
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $value[$field]['stay']) . "'>" . $vals[$field]['stay'] . "</a></td>";
                }
            echo "</tr>";
        }
        echo "<tr data-id='grand'>";
            echo "<td rowspan='2'>Grand Total</td>";
            foreach ($class->tbl_cols as $field) {
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['move']) . "'>" . count($class->total[$field]['move']) . "</a></td>";
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]['stay']) . "'>" . count($class->total[$field]['stay']) . "</a></td>";
            }
        echo "</tr>";

        //percentage
        echo "<tr>";
            foreach ($class->tbl_cols as $field) {
                echo "<td>" . round( ( count($class->total[$field]['move']) / count($class->total['total']['move']) ) * 100 , 1) . "%</td>";
                echo "<td>" . round( ( count($class->total[$field]['stay']) / count($class->total['total']['stay']) ) * 100 , 1) . "%</td>";
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