<?php  
    include('Class_4_9.php');
    $class = new Class_4_9();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.9</title>
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
    <h3>Table 4.9 Area of Affected Private Land by Use</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td rowspan="2">Residential</td>
            <td rowspan="2">Insitutional</td>
            <td rowspan="2">Industrial</td>
            <td rowspan="2">Commercial</td>
            <td rowspan="2">Agricultural</td>
            <td rowspan="2">Mixed Use</td>
            <td colspan="2">Total</td>
        </tr>
        <tr>
            <td>(m<sup>2</sup>)</td>
            <td>%</td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data as $mun => $value) {
            $head = 0;
            $vals = [];
            foreach ($class->tbl_cols as $field) {
                $vals[$field] = $value[$field]['COUNT'];
                unset($value[$field]['COUNT']);
                   
            }

            echo "<tr data-id='$mun'>";
                echo "<td>$mun</td>";
                foreach ($class->tbl_cols as $field) {
                    echo "<td><a target='_blank' href='/viewer.php?field=dp_type,uid,type,asset_num,address,baranggay,use_structure,alo_areaaffected,ownership&id=" . implode(",", $value[$field]) . "'>" . $vals[$field] . "</a></td>";
                       
                }
                echo "<td>" . round(($vals['Total'] / $class->total['Total']['COUNT']) * 100, 1) . "</td>";
            echo "</tr>";
        }

        $totals = [];
        foreach ($class->tbl_cols as $field) {
            $totals[$field] = $class->total[$field]['COUNT'];
            unset($class->total[$field]['COUNT']);
               
        }
        echo "<tr data-id='grand'>";
            echo "<td rowspan='2'>Grand Total</td>";
            foreach ($class->tbl_cols as $field) {
                echo "<td><a target='_blank' href='/viewer.php?field=displacement,alo_extent,dp_type,uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]) . "'>" . count($class->total[$field]) . "</a></td>";
            }
            echo "<td>100%</td>";
        echo "</tr>";
        echo "<tr>";
            foreach ($class->tbl_cols as $field) {
                $percent = round(($totals[$field] / $totals['Total']) * 100, 1);
                echo "<td>$percent%</td>";
            }
            echo "<td></td>";
        echo "</tr>";

        ?>
    </tbody>
</table>
<a target="_blank" href="/viewer.php?field=displacement,alo_extent,dp_type,uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>
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