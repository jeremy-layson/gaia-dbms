<?php 
    require_once('../municipality/municipalityClass.php');
    $municipality = new Municipality();
    $post = $_POST['id'];

    echo $municipality->delete($post);
?>