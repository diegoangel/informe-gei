<?php

include "config.php";

include "func.php";

include "ez_sql_core.php";
include "ez_sql_mysqli.php";
	
$db = new ezSQL_mysqli(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);




