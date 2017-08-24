<?php 
    require_once('tableManager.php');
    $post = $_POST['data'];
    $table = $_POST['table'];

    $class = new tableManager($table);

    $class->create($post);
?>