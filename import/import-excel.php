<?php
    if (isset($_FILES['excel']) === TRUE) {
        //process excel file
        $filename = $_POST['filename'];
        // var_dump($_FILES['excel']);
        move_uploaded_file($_FILES['excel']['tmp_name'], "../import/$filename.xlsx");
        // header("Location: /index.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Excel File</title>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
    <h3>Upload excel file</h3>
    <form action="/import/import-excel.php" method="POST" enctype="multipart/form-data">
        <input style="width: 300px;" type="text" name="filename" placeholder="Filename (without extension)">
        <input type="file" name="excel">
        <input type="submit" value="Upload">
    </form>
    <br><br><hr><br><br>
    <h3>Import Excel Data</h3>
    <input style="width: 500px;" type="text" id="filename" placeholder="File name of excel file (without extension)"><br>
    <input style="width: 500px;" type="text" id="maxrow" placeholder="Last row with data" value="831"><br>
    <button id="import">Begin Import</button>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#import').on('click', function(){
                window.open('/import/importer.php?max_row=' + $('#maxrow').val() + '&filename=' + $('#filename').val(), '_blank');
            });
        });
    </script>
</body>
</html>