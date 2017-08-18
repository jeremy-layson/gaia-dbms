<?php 
    require_once('../zone/municipalityClass.php');
    $municipality = new Municipality();
    $post = $_POST;

    echo json_encode($municipality->edit($post));
?>