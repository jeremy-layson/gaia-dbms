<?php 
    require_once('tableManager.php');
    $survey = new Survey();
    $post = $_POST['id'];

    echo $survey->delete($post);
?>