<?php
// Retrieve instance of the framework
$f3 = require('lib/base.php');

// Initialize
$f3->config('src/config.ini');

// Define routes
$f3->config('src/routes.ini');

// Execute application
$f3->run();