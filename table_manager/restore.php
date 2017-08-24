<?php 
    require_once('tableManager.php');
    $post = $_POST['id'];
    $table = $_POST['table'];

    $class = new tableManager($table);
    echo $class->restore($post);
?>