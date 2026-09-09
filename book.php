<?php
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT books.*, categories.name AS category_name FROM books JOIN categories ON books.category_id = categories.id WHERE books.id = :id");
$stmt->execute([':id' => $id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($book['title']); ?> - Библиотека</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-nav">
        <a href="index.php">В каталог</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php">Личный кабинет</a>
        <?php else: ?>
            <a href="login.php">Вход</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert alert-success">Книга успешно забронирована!</div>
        <?php endif; ?>
        
        <div class="book-details">
            <div class="book-details-cover">
                <img src="<?php echo htmlspecialchars($book['cover_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            </div>
            <div class="book-details-info">
                <h1 style="margin-top:0; color: var(--primary);"><?php echo htmlspecialchars($book['title']); ?></h1>
                <h3>Автор: <?php echo htmlspecialchars($book['author']); ?></h3>
                <p><strong>Категория:</strong> <?php echo htmlspecialchars($book['category_name']); ?></p>
                <div class="desc"><?php echo nl2br(htmlspecialchars($book['description'])); ?></div>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <?php if($book['is_electronic']): ?>
                        <p class="status-in">Доступна электронная версия</p>
                        <a href="<?php echo htmlspecialchars($book['electronic_url']); ?>" target="_blank" class="btn">Читать онлайн</a>
                    <?php elseif($book['stock'] > 0): ?>
                        <p class="status-in">Доступно экземпляров: <?php echo $book['stock']; ?></p>
                        <form action="reserve.php" method="POST">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" class="btn">Забронировать</button>
                        </form>
                    <?php else: ?>
                        <p class="status-out">Все экземпляры выданы</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>