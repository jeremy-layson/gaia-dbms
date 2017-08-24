<?php
    ini_set('LimitRequestLine', '10000');
    $ids = $_GET['id'];

    if ($ids == '') {
        echo "No data";
        exit();
    }

    include('sql.php');
    $result = $link->query("show full columns from survey");
    $columns[] = array('uid', 'ID');
    while ($row = $result->fetch_assoc()) {
        $comment = explode("|", $row['Comment']);
        if ($comment[0] == '1') {
            $columns[] = array($row['Field'], $comment[1]);
        }
    }

    

    $query = "SELECT * FROM survey WHERE uid IN ($ids) AND is_deleted = 0";
    $result = $link->query($query)->fetch_all(MYSQLI_ASSOC);

    if (isset($_GET['field'])) {
        $fields = explode(",", $_GET['field']);
        foreach ($result as $ctr => $data) {
            foreach ($data as $key => $val) {
                if (in_array($key, $fields) === FALSE) {
                    unset($result[$ctr][$key]);
                    foreach ($columns as $colKey => $column) {
                        if ($column[0] == $key) {
                            unset($columns[$colKey]);
                        }
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Viewer</title>
    <link rel="stylesheet" type="text/css" href="/css/foundation.min.css">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>
    <style type="text/css">
        td {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <h1>Data Count: <?=count($result)?></h1>
    <table>
        <thead>
            <tr>
            <?php 
                foreach ($columns as $column) {
                    echo "<td>" . $column[1] . "</td>";
                }
            ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($result as $data) {
                    echo "<tr>";
                    foreach ($columns as $column) {
                        if ($column[0] == 'uid') {
                            echo "<td><a target='_blank' href='/survey/view.php?id=" . $data['uid'] . "'>" . $data[$column[0]] . "</a></td>";
                        } else {
                            echo "<td>" . $data[$column[0]] . "</td>";
                        }
                    }
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>