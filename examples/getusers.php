<?php

require_once dirname(__DIR__)."/vendor/autoload.php";

$restMoodle = new Peterojo\RestMoodle([
	'format'	=> 'json',
	'host' 		=> 'http://example.com',
	'token' 	=> 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
]));


$users = $restMoodle->getUserByField('email', 'example@gmail.com');

var_dump($users);
