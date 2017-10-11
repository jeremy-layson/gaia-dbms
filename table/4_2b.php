<?php  
    include('Class_4_2b.php');
    $class = new Class_4_2b();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.2b Number of PAFs and PAPs by LGUs</title>
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
    <h3>Table 4.2b Number of PAFs and PAPs by LGUs</h3>
    <thead>
        <tr>
            <td rowspan="2">City/Municipality</td>
            <td colspan="3">Legal PAFs/PAPs</td>
            <td colspan="3">ISFs</td>
            <td colspan="4">Total</td>
        </tr>
        <tr>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Sub total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Sub total</td>
            <td>To be relocated</td>
            <td>Not to be relocated</td>
            <td>Total</td>
            <td>%</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = [];
        $total['PAF'] = intval($class->total['PAF']['TOTAL_TOTAL']['COUNT']);
        // $total['PAP'] = intval($class->total['PAP']['TOTAL_TOTAL']['COUNT']);
        
        foreach ($data as $mun => $value) {
            $head = 0;
            $vals = array('PAF' => [], 'PAP' => []);
            foreach ($class->tbl_cols as $field) {
                // $vals['PAP'][$field] = $value['PAP'][$field]['COUNT'];
                $vals['PAF'][$field] = $value['PAF'][$field]['COUNT'];
                // unset($value['PAP'][$field]['COUNT']);
                unset($value['PAF'][$field]['COUNT']);
            }

            foreach (array('PAF') as $fam) {
                echo "<tr data-id='$mun'>";
                if ($fam == 'PAF') echo "<td>$mun</td>";
                // echo "<td>{$fam}s</td>";
                foreach ($class->tbl_cols as $field) {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent,hh_members&id=" . implode(",", $value[$fam][$field]) . "'>" . $vals[$fam][$field] . "</a></td>";
                }
                echo "<td>" . round((intval($vals[$fam]['TOTAL_TOTAL']) / $total[$fam]) * 100, 1) . "%</td>";
                echo "</tr>";
                
            }
        }
            
        foreach (array('PAF') as $fam) {
            echo "<tr rowspan='2' data-id='grand'>";
            if ($fam == "PAF") echo "<td rowspan='2'>Grand Total</td>";
            // echo "<td>{$fam}s</td>";
            $temp = [];
            foreach ($class->tbl_cols as $field) {
                $tmpVal = $class->total[$fam][$field]['COUNT'];
                $temp[$field] = $class->total[$fam][$field]['COUNT'];
                unset($class->total[$fam][$field]['COUNT']);
                
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,asset_num,address,baranggay,structure_owner,structure_use,structure_dp,extent,hh_members&id=" . implode(",", $class->total[$fam][$field]) . "'>" . $tmpVal . "</a></td>";
            }
            echo "<td>100%</td>";
            echo "</tr>";
            echo "<tr>";
                foreach ($class->tbl_cols as $field) {
                    echo "<td>" . round( ($temp[$field] / $temp['TOTAL_TOTAL']) * 100 , 1) . "%</td>";
                }
                echo "<td></td>";
            echo "</tr>";
        }
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