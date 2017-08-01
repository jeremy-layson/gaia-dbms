<?php 
    include_once('legalClass.php');
    $class = new Legal();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Baranggay Table (Legal)</title>
    <link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>
<h1>Table 4.1-3 Legal PAFs by LGUs</h1>
<table border="1" cellpadding="3" cellspacing="0">
    <thead>
        <tr>
            <td rowspan="3">Municipalities and Cities</td>
            <td rowspan="3">Affected Baranggays</td>
            <td rowspan="2" colspan="2">Structure Owners (Residential)</td>
            <td rowspan="2" colspan="2">Structure Owners (Mixed Use)</td>
            <td rowspan="2" colspan="2">Structure Owners (CIBEs)</td>
            <td rowspan="2" colspan="2">Structure Owners (Industrial)</td>
            <td rowspan="1" colspan="2">Renters</td>
            <td rowspan="2" colspan="2">Absentee Structure Owner</td>
            <td rowspan="1" colspan="2">Land</td>
            <td rowspan="2" colspan="2">Commercial Stall Tenants</td>
            <td rowspan="2" colspan="2">Total</td>
        </tr>
        <tr>
            <td rowspan="1" colspan="2">(Residential)</td>
            <td rowspan="1" colspan="2">Owners * 3</td>
        </tr>
        <tr>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
            <td>Stay<sup>1</sup></td>
            <td>Move<sup>2</sup></td>
        </tr>
    </thead>
    <tbody>
        <?php $class->buildTable(); ?>
    </tbody>
</table>
<br><br>

<h3>Uncategorized Data</h3>
    <?php $class->printUncategorized(); ?>
<br><br>

<h3>Unread Data (Address format)</h3>
<table border="1" cellpadding="3" cellspacing="0">
    <?php $class->printUnincluded('LEGAL'); ?>
</table>
</body>
</html>