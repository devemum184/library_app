<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    die("Доступ запрещен. Только для сотрудников библиотеки.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reservation_id']) && is_numeric($_POST['reservation_id'])) {
        $reservation_id = (int)$_POST['reservation_id'];

        $stmt = $pdo->prepare("SELECT book_id, status FROM reservations WHERE id = :id FOR UPDATE");
        $stmt->execute([':id' => $reservation_id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reservation && $reservation['status'] === 'active') {
            $pdo->beginTransaction();
            try {
                // Обновляем статус бронирования на 'returned'
                $updateRes = $pdo->prepare("UPDATE reservations SET status = 'returned' WHERE id = :id");
                $updateRes->execute([':id' => $reservation_id]);

                // Возвращаем книгу в доступный фонд (увеличиваем stock на 1)
                $updateBook = $pdo->prepare("UPDATE books SET stock = stock + 1 WHERE id = :book_id");
                $updateBook->execute([':book_id' => $reservation['book_id']]);

                $pdo->commit();
                header("Location: admin.php?msg=returned");
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Ошибка при оформлении возврата: " . $e->getMessage());
            }
        } else {
            die("Бронирование не найдено или книга уже возвращена.");
        }
    } else {
        die("Некорректный идентификатор.");
    }
} else {
    header("Location: admin.php");
    exit();
}
?>