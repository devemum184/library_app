<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    die("Доступ запрещен. Только для сотрудников библиотеки.");
}

$current_date = date('Y-m-d');

$stmt = $pdo->query("SELECT r.id, r.book_id, u.full_name, u.email, b.title, r.reserve_date, r.return_date, r.status FROM reservations r JOIN users u ON r.user_id = u.id JOIN books b ON r.book_id = b.id ORDER BY r.status ASC, r.return_date ASC");
$all_reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$catStmt = $pdo->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель библиотекаря</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-nav">
        <a href="index.php">В каталог</a>
        <a href="profile.php">Личный кабинет</a>
        <a href="logout.php">Выход</a>
    </div>
    <div class="container">
        <div class="header-title">Управление библиотекой</div>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
            <div class="alert alert-success">Новая книга успешно добавлена в каталог.</div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'returned'): ?>
            <div class="alert alert-success">Книга успешно возвращена, экземпляр добавлен в фонд.</div>
        <?php endif; ?>

        <div style="display: flex; gap: 40px; margin-top: 20px;">
            <div style="flex: 1;">
                <h3>Выданные книги и должники</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Читатель</th>
                            <th>Книга</th>
                            <th>Возврат</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_reservations as $res): ?>
                            <?php $is_overdue = ($res['status'] === 'active' && $res['return_date'] < $current_date); ?>
                            <tr style="<?php echo $is_overdue ? 'background-color: #f8d7da;' : ''; ?>">
                                <td><?php echo htmlspecialchars($res['full_name']); ?><br><small><?php echo htmlspecialchars($res['email']); ?></small></td>
                                <td><?php echo htmlspecialchars($res['title']); ?></td>
                                <td><?php echo htmlspecialchars($res['return_date']); ?></td>
                                <td>
                                    <?php if($res['status'] === 'returned'): ?>
                                        <span class="status-in">Сдана</span>
                                    <?php else: ?>
                                        <?php if($is_overdue): ?>
                                            <span class="status-out" style="display: block; margin-bottom: 5px;">Должник</span>
                                        <?php else: ?>
                                            <span style="display: block; margin-bottom: 5px;">На руках</span>
                                        <?php endif; ?>
                                        <form action="return_book.php" method="POST" style="margin: 0;">
                                            <input type="hidden" name="reservation_id" value="<?php echo $res['id']; ?>">
                                            <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 11px;">Оформить возврат</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="flex: 1;">
                <div class="form-container" style="margin:0; width: 100%;">
                    <h3>Добавить книгу</h3>
                    <form action="add_book.php" method="POST">
                        <div class="form-group">
                            <label>Название</label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Автор</label>
                            <input type="text" name="author" required>
                        </div>
                        <div class="form-group">
                            <label>Описание</label>
                            <textarea name="description" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Категория</label>
                            <select name="category_id">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>URL обложки (картинки)</label>
                            <input type="url" name="cover_url" required>
                        </div>
                        <div class="form-group">
                            <label>Кол-во экземпляров</label>
                            <input type="number" name="stock" value="1" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_electronic" value="1" style="width:auto;"> Электронная версия
                            </label>
                        </div>
                        <div class="form-group">
                            <label>URL файла (если электронная)</label>
                            <input type="url" name="electronic_url">
                        </div>
                        <button type="submit" class="btn">Добавить в базу</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

