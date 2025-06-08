<?php
$dbFile = 'data/store.db';
$dsn = 'sqlite:' . $dbFile;

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY,
            name TEXT NOT NULL,
            price REAL NOT NULL
        )
    ");

    $result = $pdo->query("SELECT COUNT(*) FROM products");

    if ($result == 0) {

        $products = [
            [1, "Молоко пастеризоване", 12],
            [2, "Хліб чорний", 9],
            [3, "Сир білий", 21],
            [4, "Сметана 20%", 25],
            [5, "Кефір 1%", 19],
            [6, "Вода газована", 18],
            [7, "Печиво \"Весна\"", 14]
        ];

        $stmt = $pdo->prepare("INSERT INTO products (id, name, price) VALUES (?, ?, ?)");

        foreach ($products as $p) {
            $stmt->execute([$p[0], $p[1], $p[2]]);
        }

    } else {
        echo "Таблиця вже має дані.\n";
    }


} catch (PDOException $e) {
    echo "Помилка бази даних: " . $e->getMessage() . "\n";
    exit(1);
}
