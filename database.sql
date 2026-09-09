CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'librarian') DEFAULT 'user'
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category_id INT NOT NULL,
    cover_url VARCHAR(1000) NOT NULL,
    stock INT NOT NULL,
    is_electronic BOOLEAN DEFAULT FALSE,
    electronic_url VARCHAR(1000) DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    reserve_date DATE NOT NULL,
    return_date DATE NOT NULL,
    status ENUM('active', 'returned') DEFAULT 'active',
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO categories (name) VALUES 
('Учебники'), 
('Информатика'), 
('Математика'), 
('Научные статьи'), 
('Методические пособия');

INSERT INTO users (full_name, email, password, role) VALUES 
('Администратор Библиотеки', 'admin@synergy.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'librarian'),
('Иванов Иван Иванович', 'ivanov@synergy.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Петров Петр Петрович', 'petrov@synergy.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Смирнова Анна Сергеевна', 'smirnova@synergy.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Кузнецов Алексей Игоревич', 'kuznetsov@synergy.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO books (title, author, description, category_id, cover_url, stock, is_electronic, electronic_url) VALUES 
('Алгоритмы: построение и анализ', 'Томас Кормен, Чарльз Лейзерсон', 'Классический учебник по алгоритмам, охватывающий широкий спектр тем от базовых структур данных до сложных графовых алгоритмов.', 2, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWDaB6Jq--b1zYaDtm2ThDqRpH3QHuj3BYAFmYt5CNKw&s=10', 3, FALSE, NULL),
('PHP 8. Объекты, шаблоны и методики', 'Мэтт Зандстра', 'Глубокое погружение в объектно-ориентированное программирование на PHP 8, шаблоны проектирования и лучшие практики.', 2, 'https://www.williamspublishing.com/Books/thumb/big/978-5-8459-1922-9.jpg', 0, FALSE, NULL),
('Базы данных. Проектирование, реализация', 'Томас Коннолли', 'Исчерпывающее руководство по базам данных, от концептуального проектирования до физической реализации и администрирования.', 2, 'https://imo10.labirint.ru/books/579845/cover.jpg/484-0', 1, FALSE, NULL),
('Компьютерные сети. Принципы, технологии', 'Виктор Олифер', 'Подробное описание принципов работы компьютерных сетей, протоколов маршрутизации и сетевых технологий.', 2, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrnAA8JfChorkTguCy7ea9pUpejXe3Q1kdQinqQjyBlA&s', 10, TRUE, 'https://example.com/networks.pdf'),
('Высшая математика', 'Ильин В.А.', 'Учебник по высшей математике для студентов инженерно-технических специальностей вузов.', 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTav0enKnYX-NAAmIvnhnhSihyaDt_33zOWN9kwx288OQ&s=10', 5, FALSE, NULL),
('Дискретная математика', 'Новиков Ф.А.', 'Основы дискретной математики: множества, логика, графы, комбинаторика и теория автоматов.', 3, 'https://cdn.litres.ru/pub/c/cover/28540038.jpg', 2, FALSE, NULL),
('Теория вероятностей', 'Гмурман В.Е.', 'Руководство к решению задач по теории вероятностей и математической статистике.', 3, 'https://urss.ru/covers500/79857.jpg', 4, FALSE, NULL),
('Основы менеджмента', 'Майкл Мескон', 'Базовый курс по управлению организациями, теория и практика менеджмента.', 1, 'https://cdn.litres.ru/pub/c/cover/8911838.jpg', 8, FALSE, NULL),
('Экономическая теория', 'Борисов Е.Ф.', 'Курс экономической теории, охватывающий микро- и макроэкономику.', 1, 'https://imo10.labirint.ru/books/159065/cover.jpg/242-0', 3, FALSE, NULL),
('Правоведение', 'Марченко М.Н.', 'Основы государства и права Российской Федерации для неюридических вузов.', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqv7eTbkuq64M0Gza279r4H_fA92PCa06T3pmsz4tdEuFyUYcmxGcvr2UZ&s=10', 6, FALSE, NULL),
('Физика', 'Савельев И.В.', 'Курс общей физики. Механика, молекулярная физика и термодинамика.', 1, 'https://urss.ru/covers_max/287553.jpg', 4, FALSE, NULL),
('Машинное обучение', 'Петер Флах', 'Наука и искусство построения алгоритмов, которые извлекают знания из данных.', 2, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWhma416PVv-Udg55pWuKh2swazkmScCfZYUfG2lWXCJWYbE9Qd7o-o5U&s=10', 1, FALSE, NULL),
('История России', 'Орлов А.С.', 'Учебник по истории России с древнейших времен до наших дней.', 1, 'https://www.belykrolik.ru/media/catalog/product_images/7511211_1_istoriya-rossii-v-shemah-orlov-prospekt_small.jpg', 12, FALSE, NULL),
('Методика преподавания', 'Ситаров В.А.', 'Дидактика: учебное пособие для студентов высших педагогических учебных заведений.', 5, 'https://cdn.litres.ru/pub/c/cover/11960028.jpg', 5, FALSE, NULL),
('Научная статья: Квантовые вычисления', 'Иванов А.А.', 'Обзор современного состояния и перспектив развития квантовых вычислений.', 4, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJ4lqVO67LCYvnlPbJPgH1804oYpppuqaLVoezXMn4JABQQKdAdjkO-lRo&s=10', 100, TRUE, 'https://journals.kantiana.ru/upload/iblock/236/11_95-99.pdf');

INSERT INTO reservations (book_id, user_id, reserve_date, return_date, status) VALUES 
(2, 2, '2026-08-01', '2026-08-15', 'active'),
(5, 3, '2026-09-01', '2026-09-15', 'active'),
(12, 4, '2026-08-20', '2026-09-03', 'active'),
(8, 5, '2026-09-05', '2026-09-19', 'active'),
(1, 2, '2026-09-08', '2026-09-22', 'active');