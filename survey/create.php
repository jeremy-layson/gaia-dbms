<?php 
    require_once('surveyClass.php');
    $survey = new Survey();
    $post = $_POST;

    echo json_encode($survey->create($post));
?>