<?php  
    include('Class_4_3_6.php');
    $class = new Class_4_3_6();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-2</title>
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

        tbody td:first-child {
            text-align: left;
        }
    </style>
</head>
<body>
<a href="/">Back</a>

<table border="1">
    <h3>Table 4.3-6 - Reason for Establishing Residence in Present Place (LGU Level)</h3>
    <thead>
        <tr>
            <td rowspan="2">Reasons</td>
            <td colspan="2">Residential</td>
            <td colspan="2">CIBEs</td>
            <td colspan="2">ISF</td>
            <td rowspan="2" colspan="2">Total</td>
        </tr>
        <tr>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
            <td>Number</td>
            <td>%</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $definition = array(
            'econ'  => 'Economic Reason',
            'socio' => 'Social Reason',
            'other' => 'Other Reason'
        );
        foreach ($data as $group => $values) {
            echo "<tr><td colspan='9' style='font-weight:bold;'>" . $definition[$group] ."</td></td>";
            foreach ($values as $cat => $val) {
            echo "<tr>";
                echo "<td>" . ucfirst(strtolower($cat)) . "</td>";

                if (isset($val['Residential'])) {
                    $ids = implode(',', $val['Residential']);
                    $total = count($val['Residential']);
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                    echo "<td>" . round((($total / count($class->total['Residential'])) * 100), 1) . "%</td>";
                } else {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id='>0</a></td>";
                    echo "<td>0%</td>";
                }

                if (isset($val['CIBE'])) {
                    $ids = implode(',', $val['CIBE']);
                    $total = count($val['CIBE']);
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                    echo "<td>" . round((($total / count($class->total['CIBE'])) * 100), 1) . "%</td>";
                } else {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id='>0</a></td>";
                    echo "<td>0%</td>";
                }

                if (isset($val['ISF'])) {
                    $ids = implode(',', $val['ISF']);
                    $total = count($val['ISF']);
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                    echo "<td>" . round((($total / count($class->total['ISF'])) * 100), 1) . "%</td>";
                } else {
                    echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id='>0</a></td>";
                    echo "<td>0%</td>";
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
            $ids = implode(',', $class->total['Residential']);
            $total = count($class->total['Residential']);
            echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                echo "<td>" . round(((count($class->total['Residential']) / count($class->total['Total'])) * 100), 1) . "%</td>";
            $ids = implode(',', $class->total['CIBE']);
            $total = count($class->total['CIBE']);
            echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                echo "<td>" . round(((count($class->total['CIBE']) / count($class->total['Total'])) * 100), 1) . "%</td>";
            $ids = implode(',', $class->total['ISF']);
            $total = count($class->total['ISF']);
            echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                echo "<td>" . round(((count($class->total['ISF']) / count($class->total['Total'])) * 100), 1) . "%</td>";
            $ids = implode(',', $class->total['Total']);
            $total = count($class->total['Total']);
            echo "<td><a target='_blank' href='/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=$ids'>$total</a></td>";
                echo "<td>" . round(((count($class->total['Total']) / count($class->total['Total'])) * 100), 1) . "%</td>";
            
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