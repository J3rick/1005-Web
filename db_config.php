<?php
// db_config.php - Store all database credentials here
// This file should be kept secure and not exposed to public

// Remote database configuration
$config['remote'] = [
    'servername' => 'localhost',
    'username' => 'inf1005-sqldev',
    'password' => 'Tri3IsCom1ing_ToAnEndS0on!',
    'dbname' => 'Memorial_Map'
];

// Local database configuration
$config['local'] = [
    'servername' => 'localhost',
    'username' => 'admin', // Replace with your local username
    'password' => 'test@123',     // Replace with your local password
    'dbname' => 'Memorial_Map' // Replace with your local database name
];
?>