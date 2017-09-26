<?php  
    include('Class_4_3_6b.php');
    $class = new Class_4_3_6b();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-6b</title>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <style type="text/css">
        table {
            width: 150%;
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

        .gray {
            background-color: #e3e3e3;
        }

        thead td {
            background-color: #e3e3e3;
            font-weight: bold;
            text-align: center;
        }

        tbody td {
            text-align: center;
        }

        tbody tr:hover {
            background-color: #e3e3e3;
        }

        tbody td:first-child {
            text-align: left;
        }
    </style>
</head>
<body>
<a href="/">Back</a>

<table border="1">
    <h3>Table 4.3-6b - Reason for Establishing Residence in Present Place per LGU</h3>
    <thead>
        <tr>
            <td rowspan="2" style="width:400px;">Reasons</td>
            
            <?php foreach ($class->municipalities as $key => $value) {
                echo '<td colspan="2">' . $key . '</td>';
            } ?>
            
            <td rowspan="2" colspan="2">Total</td>
        </tr>
        <tr>
            <?php foreach ($class->municipalities as $key => $value) echo "<td>Number</td><td>%</td>"?>
        </tr>
    </thead>
    <tbody>
        <?php
        $definition = array(
            'econ'  => 'Economic Reason',
            'socio' => 'Social Reason',
            'other' => 'Other Reason',
            'noans' => 'No Answer',
        );
        foreach ($data as $group => $values) {
            $colspan = 1 + ((count($class->municipalities) + 1) * 2);
            echo "<tr><td class='gray' colspan='$colspan' style='font-weight:bold;'>" . $definition[$group] ."</td></td>";
            foreach ($values as $cat => $val) {
            echo "<tr>";
                echo "<td>" . ucfirst(strtolower($cat)) . "</td>";
                $total = count($val['Total']);
                foreach ($class->municipalities as $key => $value) {
                    if (isset($val[$key]) === FALSE) {
                        $ids = "";
                        $count = 0;
                    } else {
                        $ids = implode(",", $val[$key]);
                        $count = count($val[$key]);
                    }
                    
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$count</a></td>";
                    echo "<td>" . round((($count / $total) * 100), 1) . "%</td>";
                }

                if (isset($val['Total'])) {
                    $ids = implode(',', $val['Total']);
                    $total = count($val['Total']);
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                    echo "<td>" . round((($total / count($class->total['Total'])) * 100), 1) . "%</td>";
                } else {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id='>0</a></td>";
                    echo "<td>0%</td>";
                }
                
                echo "</tr>";
            }
        }
        echo "<tr style='font-weight:bold;'>";
            echo "<td>Total</td>";

            $total = count($class->total['Total']);
            foreach ($class->municipalities as $key => $value) {
                if (isset($class->total[$key]) === FALSE) {
                    $ids = "";
                    $count = 0;
                } else {
                    $ids = implode(',', $class->total[$key]);
                    $count = count($class->total[$key]);
                }
                echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$count</a></td>";
                echo "<td>" . round((($count / $total) * 100), 1) . "%</td>";
            }
            $ids = implode(",", $class->total['Total']);
            echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                echo "<td>" . round((($total / $total) * 100), 1) . "%</td>";
        echo "</tr>";

        ?>
    </tbody>
</table>

<a target="_blank" href="/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>

<script type="text/javascript">
    $("[data-id='Sub Total']").css('font-weight', 'bold');

    var grand = $("[data-id='Total'] td");
    $(grand[0]).prop('colspan', '2');
    $(grand[1]).remove();
    $(grand).parent().css('font-weight', 'bold');
</script>
</body>
</html>