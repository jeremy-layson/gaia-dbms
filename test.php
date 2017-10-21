<?php
require('../sql.php');
$link->query("UPDATE municipality SET uid = uid + 100 WHERE municipality = 'Manila'");
