<?php

	if ($_SERVER['HTTP_HOST'] == 'chali.thet.com.ar') {
		
		define("DB_USER"		, "root");		// <-- mysql db user
		define("DB_PASSWORD"	, "root");	// <-- mysql db password
		define("DB_NAME"		, "min_ambiente");		// <-- mysql db pname
		define("DB_HOST"		, "127.0.0.1");			// <-- mysql server host
	
	}

	if ($_SERVER['HTTP_HOST'] == 'thet.com.ar') {
		
		define("DB_USER"		, "thet_minamb");		// <-- mysql db user
		define("DB_PASSWORD"	, "VeDwOtr3ChPF");	// <-- mysql db password
		define("DB_NAME"		, "thet_minamb");		// <-- mysql db pname
		define("DB_HOST"		, "localhost");			// <-- mysql server host
	
	}



