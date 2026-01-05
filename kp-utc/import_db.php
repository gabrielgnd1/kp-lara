<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=kp-utc', 'root', '');

$sqlFile = 'database_backup.sql';
$sql = file_get_contents($sqlFile);

// Split by semicolon, handle multi-line statements
$statements = preg_split('/;(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql);

$count = 0;
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement) && !str_starts_with(trim($statement), '--')) {
        try {
            $pdo->exec($statement);
            $count++;
            echo ".";
        } catch (PDOException $e) {
            echo "\nError: " . $e->getMessage() . "\n";
            echo "Statement: " . substr($statement, 0, 100) . "...\n";
        }
    }
}

echo "\n\nImport completed! $count statements executed.\n";
?>
