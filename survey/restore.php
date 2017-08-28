<?php 
    require_once('surveyClass.php');
    $survey = new Survey();
    $post = $_POST['id'];

    echo $survey->restore($post);
?>