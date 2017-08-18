<?php 
    require_once('../zone/municipalityClass.php');
    $municipality = new Municipality();
    $post = $_POST['id'];

    echo $municipality->delete($post);
?>