<?php 
    $input = $_POST['text'];
    $input = explode(",", $input);

	$final = [];
	foreach ($input as $value) {
		$final[$value] = $value;
	}

	echo "Total: " . count($final) . "<br><br><br>";
	echo implode(",", $final);
?>


<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<form action="" method="POST">
    <textarea name="text"></textarea>
    <input type="submit" name="">
</form>
</body>
</html>