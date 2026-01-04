<?php
/**
 * Database Initialization Script
 * Run this file once to set up the database
 * Access via: http://localhost:8000/init-database.php
 */

// Include database configuration
require_once 'config.php';

// Read SQL file
$sqlFile = 'database.sql';
$sql = file_get_contents($sqlFile);

if ($sql === false) {
    die("Error: Could not read SQL file: $sqlFile");
}

// Create connection without database selection first
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Split SQL into individual statements
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function($statement) {
        return !empty($statement) && 
               !preg_match('/^--/', $statement) && 
               !preg_match('/^CREATE DATABASE/i', $statement) &&
               !preg_match('/^USE/i', $statement);
    }
);

// Select database
$conn->select_db(DB_NAME);

// If database doesn't exist, create it
if ($conn->error && strpos($conn->error, "Unknown database") !== false) {
    $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->select_db(DB_NAME);
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Initialization</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #e3f2fd; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Food Delivery App - Database Initialization</h1>";

$successCount = 0;
$errorCount = 0;
$errors = [];

// Execute each statement
foreach ($statements as $statement) {
    if (empty(trim($statement))) continue;
    
    if ($conn->query($statement)) {
        $successCount++;
    } else {
        $errorCount++;
        $errors[] = $conn->error . " (Statement: " . substr($statement, 0, 100) . "...)";
    }
}

if ($errorCount === 0) {
    echo "<div class='success'><strong>Success!</strong> Database initialized successfully.</div>";
    echo "<div class='info'>Executed $successCount SQL statements.</div>";
    echo "<div class='info'><strong>Next steps:</strong><br>
          1. You can now use the application<br>
          2. Register a new account to create a user<br>
          3. Sample restaurants and menu items have been added</div>";
} else {
    echo "<div class='error'><strong>Errors occurred:</strong></div>";
    foreach ($errors as $error) {
        echo "<div class='error'>$error</div>";
    }
    echo "<div class='info'>Successfully executed $successCount statements.</div>";
}

// Test connection
echo "<h2>Database Connection Test</h2>";
$testConn = getDBConnection();
if ($testConn) {
    echo "<div class='success'>✓ Database connection successful!</div>";
    
    // Show table count
    $result = $testConn->query("SHOW TABLES");
    $tableCount = $result->num_rows;
    echo "<div class='info'>Found $tableCount tables in database.</div>";
    
    // Show sample data counts
    $tables = ['restaurants', 'menu_items', 'users', 'orders'];
    foreach ($tables as $table) {
        $result = $testConn->query("SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<div class='info'>$table: {$row['count']} records</div>";
        }
    }
} else {
    echo "<div class='error'>✗ Database connection failed!</div>";
}

echo "</body></html>";

$conn->close();
?>

