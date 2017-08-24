<?php 
    require 'sql.php';

    $tables = array('constant', 'market_value', 'material_cost', 'municipality', 'municipality_zone', 'survey');

    foreach ($tables as $key => $value) {
        $query = "DROP TABLE $value";
        $link->query($query);
    }


?>