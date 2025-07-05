<?php
require 'config/database.php';
$route = $_GET['rp'] ?? '/';
switch ($route) {
  case '/login': require 'routes/login.php'; break;
  case '/dashboard': require 'routes/dashboard.php'; break;
  default: require 'routes/404.php';
}
