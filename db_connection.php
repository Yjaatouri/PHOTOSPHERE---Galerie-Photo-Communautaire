
<?php


$host     = 'localhost';
$dbname   = 'photosphere';
$username = 'root';
$password = 'Yahya2025@';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {

    echo "Database connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

return $pdo; 