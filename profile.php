<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

$stmt = $pdo->prepare("SELECT r.id, b.title, b.author, r.reserve_date, r.return_date, r.status FROM reservations r JOIN books b ON r.book_id = b.id WHERE r.user_id = :user_id ORDER BY r.return_date ASC");
$stmt->execute([':user_id' => $user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-nav">
        <a href="index.php">В каталог</a>
        <?php if($_SESSION['role'] === 'librarian'): ?>
            <a href="admin.php">Панель библиотекаря</a>
        <?php endif; ?>
        <a href="logout.php">Выход</a>
    </div>
    <div class="container">
        <div class="header-title">Личный кабинет</div>
        <h3>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h3>
        
        <h4 style="margin-top:40px;">Мои книги и задолженности</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Книга</th>
                    <th>Автор</th>
                    <th>Дата выдачи</th>
                    <th>Срок возврата</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservations as $res): ?>
                    <?php
                        $is_overdue = ($res['status'] === 'active' && $res['return_date'] < $current_date);
                    ?>
                    <tr style="<?php echo $is_overdue ? 'background-color: #f8d7da;' : ''; ?>">
                        <td><?php echo htmlspecialchars($res['title']); ?></td>
                        <td><?php echo htmlspecialchars($res['author']); ?></td>
                        <td><?php echo htmlspecialchars($res['reserve_date']); ?></td>
                        <td><?php echo htmlspecialchars($res['return_date']); ?></td>
                        <td>
                            <?php if($res['status'] === 'returned'): ?>
                                <span class="status-in">Возвращена</span>
                            <?php elseif($is_overdue): ?>
                                <span class="status-out">Просрочена (Долг)</span>
                            <?php else: ?>
                                <span style="color: #17a2b8; font-weight:600;">На руках</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($reservations)): ?>
                    <tr><td colspan="5">У вас нет активных или прошлых выдач.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>