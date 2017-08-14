<?php 
    require_once('affected-lands.php');
    $class = new AreaAffected();
?>
<!DOCTYPE html>
<html>
<head>
    <title>4.1-2</title>
    <link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>
<h1>Table 4.1-2 Affected Lands</h1>
<table border="1" cellpadding="3" cellspacing="0">
    <thead>
    <tr>
        <td rowspan="2">Type of Loss</td>
        <td colspan="3">Number of PAFs</td>
        <td colspan="3">Number of Affected PAPs</td>
    </tr>
    <tr>
        <td>Legal<sup>1</sup></td>
        <td>ISF<sup>2</sup></td>
        <td>Total<sup>3</sup></td>
        <td>Legal<sup>1</sup></td>
        <td>ISF<sup>2</sup></td>
        <td>Total<sup>3</sup></td>
    </tr>
    </thead>
    <!-- content here -->

    <?php 
        $class->buildTable();
    ?>
</table>
<br><br>

<h3>Uncategorized Data</h3>
<?php 
    $class->buildExcess();
?>
</body>
</html>