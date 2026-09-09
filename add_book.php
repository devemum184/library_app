<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    die("Доступ запрещен.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $cover_url = trim($_POST['cover_url']);
    $stock = (int)$_POST['stock'];
    $is_electronic = isset($_POST['is_electronic']) ? 1 : 0;
    $electronic_url = trim($_POST['electronic_url']);
    
    if (empty($electronic_url)) {
        $electronic_url = NULL;
    }
    
    if (empty($title) || empty($author) || empty($cover_url)) {
        die("Заполните обязательные поля.");
    }

    if ($stock < 0) {
        die("Ошибка: Количество экземпляров не может быть меньше нуля!");
    }

    if (!$is_electronic && $stock == 0) {
        die("Ошибка: Для физической книги необходимо указать минимум 1 экземпляр в наличии.");
    }

    $stmt = $pdo->prepare("INSERT INTO books (title, author, description, category_id, cover_url, stock, is_electronic, electronic_url) VALUES (:title, :author, :description, :category_id, :cover_url, :stock, :is_electronic, :electronic_url)");
    $stmt->execute([
        ':title' => $title,
        ':author' => $author,
        ':description' => $description,
        ':category_id' => $category_id,
        ':cover_url' => $cover_url,
        ':stock' => $stock,
        ':is_electronic' => $is_electronic,
        ':electronic_url' => $electronic_url
    ]);

    header("Location: admin.php?msg=added");
    exit();
}
?>