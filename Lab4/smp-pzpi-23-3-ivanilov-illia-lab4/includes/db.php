<?php
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dbFile = 'data/store.db';
        $dsn = 'sqlite:' . $dbFile;
        try {
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Помилка підключення бази даних: " . $e->getMessage());
        }
    }
    return $pdo;
}

function getAllProducts() {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM products");
    return $stmt->fetchAll();
}

function getProductById($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}
?>