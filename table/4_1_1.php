<?php 
    include_once('Class_4_1_1.php');
    $class = new Class_4_1_1();
    $data = $class->getData();

    $headers = '';
    $max_row = 0;

    foreach ($data as $key => $value) {
        $headers = $headers . "<td  class='gray' width='400px'>" . $key . "</td>";

        if (count($value) > $max_row) $max_row = count($value);
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Table 4.1-1</title>
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

        #header {
            text-align: center;
        }

        .gray {
            background-color: #e3e3e3;
        }
    </style>
</head>
<body>
    <a href="/">Back</a>

    <h3>Table 4.1-1 Affected Cities and Municipalities and Corresponding Baranggays by NSCR Project</h3>
    <table border="1">
        <tr><td colspan="<?=count($data)+1?>" id="header">Cities and Municipalities</td></tr>
        <tr>
            <td></td>
            <?=$headers?>
        </tr>
        <?php
            

            for ($i=0; $i < $max_row; $i++) {
                echo "<tr>";
                if ($i == 0) {
                    echo "<td id='brgy' rowspan='" . $max_row . "'>B\na\nr\na\nn\ng\ng\na\ny\ns</td>";
                }
                foreach ($data as $muns => $brgys) {
                    if (count($brgys) > $i) {
                        $style = '';
                        if (count($brgys[$i][1]) == 0) $style = 'gray';
                        echo "<td class='$style'><a target='_blank' href='/viewer.php?field=uid,asset_num,name,address,baranggay&id=" . implode(",", $brgys[$i][1]) . "'>" . $brgys[$i][0] . "</a></td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
        ?>
    </table>
    
    <br><br>
    <a target="_blank" href="/viewer.php?field=uid,name,address,baranggay&id=<?=implode(',', $class->unclaimed)?>">Uncategorized Data</a>
</body>
</html>