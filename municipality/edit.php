<?php 
    require_once('../municipality/municipalityClass.php');
    $municipality = new Municipality();
    $post = $_POST;

    echo json_encode($municipality->edit($post));
?>