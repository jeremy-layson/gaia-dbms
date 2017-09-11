<?php  
    include('Class_4_1_2b.php');
    $class = new Class_4_1_2b();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.1-2b Number of Affected PAFs and PAPs by LGUs</title>
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
        foreach ($data as $mun => $value) {
            $head = 0;
            $vals = [];
            foreach ($class->tbl_cols as $field) {
                $vals[$field] = $value['Sub Total'][$field]['COUNT'];
                unset($value['Sub Total'][$field]['COUNT']);
            }

            echo "<tr data-id='$mun'>";
            echo "<td>$mun</td>";
            foreach ($class->tbl_cols as $field) {
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $value['Sub Total'][$field]) . "'>" . $vals[$field] . "</a></td>";
            }
            echo "</tr>";
        }
        echo "<tr data-id='grand'>";
        echo "<td>Grand Total</td>";
        foreach ($class->tbl_cols as $field) {
            $tmpVal = $class->total[$field]['COUNT'];
            unset($class->total[$field]['COUNT']);
            echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent&id=" . implode(",", $class->total[$field]) . "'>" . $tmpVal . "</a></td>";
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