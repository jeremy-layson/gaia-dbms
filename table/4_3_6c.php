<?php  
    include('Class_4_3_6c.php');
    $class = new Class_4_3_6c();
    $data = $class->getData();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.3-6c</title>
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
<pre>
    <?php //var_dump($data); ?>
</pre>
<table border="1">
    <h3>Table 4.3-6c - Reason for Establishing Residence in Present Place per LGU</h3>
    <thead>
        <tr>
            <td rowspan="2" style="width:400px;">Municipalities</td>
            
            <?php foreach ($class->fields as $key => $value) {
                echo '<td colspan="' . count($value) . '">' . $class->definition[$key] . '</td>';
            } ?>
            
            <td rowspan="2" colspan="2">Total</td>
        </tr>
        <tr>
            <?php foreach ($class->fields as $key => $value) {
                foreach ($value as $k => $v) {
                    echo '<td>' . $k . '</td>';
                }
            } ?>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($data as $key => $value) {
                echo "<tr>";
                    echo "<td>$key</td>";

                    foreach ($value as $group => $members) {
                        foreach ($members as $reason => $ids) {
                            $val = count($ids);
                            echo '<td><a target="_blank" href="/viewer.php?field=uid,type,use,hdi_reason_econ,hdi_reason_social,hdi_reason_other&id=' . implode(',', $ids) . '">' . $val . '</a></td>';
                        }
                    }
                    $percent = round( (count($value['Total']['Total']) / count($data['Total']['Total']['Total'])) * 100, 1);
                    echo "<td>$percent%</td>";
                echo "</tr>";
            }

            echo "<tr>";
                echo "<td>Percentage</td>";
                foreach ($data['Total'] as $key => $value) {
                    foreach ($value as $reason => $ids) {
                        $percent = round( (count($ids) / count($data['Total']['Total']['Total'])) * 100, 1);
                        echo "<td>$percent%</td>";
                    }
                }
                echo "<td></td>";
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