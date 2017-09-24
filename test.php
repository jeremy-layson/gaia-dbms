<?php 
    echo strtoupper("Batanggeño");

    //http://[fe80::5215:7bb6:3d47:f48d]%rmnet_data0:9090/sendsms?phone=09152102562&text=test
?>


<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<form action="http://192.168.8.101:1688/services/api/messaging/" method="POST">
    <input type="text" name="to">
    <input type="text" name="message">
    <input type="submit" name="">
</form>
</body>
</html>