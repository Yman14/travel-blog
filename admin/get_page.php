<?php
$page = $_GET['page'];

// Security: basic check to ensure they aren't loading system files
$allowed = ['profile', 'contacts'];

if (in_array($page, $allowed)) {
    include_once "{$page}.php";
} else {
    include_once "admin/404-admin";
}

