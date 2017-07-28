<?php 
    require_once('sql.php');
    $query = 'SELECT * FROM municipality';
    $result = $link->query($query);

    while ($row = $result->fetch_assoc()) {
        echo 'address LIKE "%' . $row['baranggay'] . '%" OR ';
    }
?>