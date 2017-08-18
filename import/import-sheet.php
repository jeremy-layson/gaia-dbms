<?php 
    include('importer.php');
    $import = new Importer(html_entity_decode($_GET['tmp_name']));
        
?>