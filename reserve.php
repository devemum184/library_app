<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = (int)$_POST['book_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT stock, is_electronic FROM books WHERE id = :id FOR UPDATE");
    $stmt->execute([':id' => $book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book && !$book['is_electronic'] && $book['stock'] > 0) {
        $pdo->beginTransaction();
        try {
            $update = $pdo->prepare("UPDATE books SET stock = stock - 1 WHERE id = :id");
            $update->execute([':id' => $book_id]);

            $reserve_date = date('Y-m-d');
            $return_date = date('Y-m-d', strtotime('+14 days'));

            $insert = $pdo->prepare("INSERT INTO reservations (book_id, user_id, reserve_date, return_date, status) VALUES (:book_id, :user_id, :reserve_date, :return_date, 'active')");
            $insert->execute([
                ':book_id' => $book_id,
                ':user_id' => $user_id,
                ':reserve_date' => $reserve_date,
                ':return_date' => $return_date
            ]);

            $pdo->commit();
            header("Location: book.php?id=" . $book_id . "&msg=success");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Ошибка бронирования.");
        }
    } else {
        die("Книга недоступна.");
    }
} else {
    header("Location: index.php");
    exit();
}
?>