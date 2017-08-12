<?php 
    require_once('../survey/surveyClass.php');
    $survey = new Survey();
    $post = $_POST['id'];

    echo $survey->delete($post);
?>