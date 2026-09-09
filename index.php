<?php
require_once 'config.php';

$stmt = $pdo->query("SELECT books.*, categories.name AS category_name FROM books JOIN categories ON books.category_id = categories.id");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$catStmt = $pdo->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Библиотека ВУЗа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-nav">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php">Личный кабинет</a>
            <?php if($_SESSION['role'] === 'librarian'): ?>
                <a href="admin.php">Панель библиотекаря</a>
            <?php endif; ?>
            <a href="logout.php">Выход (<?php echo htmlspecialchars($_SESSION['full_name']); ?>)</a>
        <?php else: ?>
            <a href="login.php">Вход</a>
            <a href="register.php">Регистрация</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <div class="header-title">Каталог литературы</div>

        <div class="filters">
            <span style="color: var(--text-sec); margin-right: 10px;">≡ Фильтры</span>
            <button class="filter-btn active" data-id="all">Все новинки</button>
            <?php foreach ($categories as $category): ?>
                <button class="filter-btn" data-id="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></button>
            <?php endforeach; ?>
        </div>

        <div class="grid">
            <?php foreach ($books as $book): ?>
                <a href="book.php?id=<?php echo $book['id']; ?>" class="card card-item" data-category="<?php echo $book['category_id']; ?>">
                    <div class="book-cover">
                        <img src="<?php echo htmlspecialchars($book['cover_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    </div>
                    <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>
                    <div class="card-bottom">
                        <?php if($book['is_electronic']): ?>
                            <span class="status-in">Эл. версия</span>
                            <span class="btn" onclick="window.open('<?php echo htmlspecialchars($book['electronic_url']); ?>', '_blank'); return false;">Читать онлайн</span>
                        <?php elseif($book['stock'] > 0): ?>
                            <span class="status-in">В наличии (<?php echo $book['stock']; ?>)</span>
                            <span class="btn">Забронировать</span>
                        <?php else: ?>
                            <span class="status-out">Нет в наличии</span>
                            <span class="btn btn-secondary">В очередь</span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>