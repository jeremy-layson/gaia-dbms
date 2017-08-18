<?php
    if (isset($_FILES['excel']) === TRUE) {
        include('importer.php');
        $import = new Importer($_FILES['excel']['tmp_name']);
        $workSheets = $import->getWorkSheets();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Survey Import</title>
    <link rel="stylesheet" type="text/css" href="/css/foundation.min.css">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>

</head>
<body>
    <div class="container row">
        <form action="#" method="POST" enctype="multipart/form-data">
            <h3>Choose an excel file to load</h3>
            <input type="file" name="excel" id="excel_upload">
            <input type="submit" value="Open">
        </form>
        <br><br>
        <h3>List of worksheets</h3>
        <?php 
            if (isset($workSheets) === TRUE) {
                foreach ($workSheets as $sheet) {
                    echo $sheet[1] . ' - ' . $sheet[0] . "<br>";
                }
            }
        ?>
    </div>
</body>
</html>