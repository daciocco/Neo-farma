<?php




return [
	'dbname' => [
		'name' 			=> 'zt000459_pedidos', 
		'username' 		=> 'root',
		'password' 		=> '',
		'connection'	=> 'mysql:host=localhost',
		'charset'		=> 'charset=UTF8',
		'options' 		=> [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		],
	],
	
	'Database' => [
		'name' 			=> 'HiperWin_Falsa',
		'username' 		=> 'sa',
		'password' 		=> 'Yoblondy1963',
		'connection'	=> 'sqlsrv:Server=serverbd',
		'charset'		=> '',
		'options' 		=> [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		],
	],
];
?>