<?php
// 1. URL з GET-параметрами
$url = 'http://lab.vntu.org/api-server/lab8.php?user=student&pass=p@ssw0rd';

// 2. Отримання JSON (варіант через file_get_contents)
$json = file_get_contents($url);
if ($json === false) {
    die('Не вдалося отримати дані з API');
}

// 3. Перетворення JSON у структури PHP
// true -> асоціативні масиви, без true -> об’єкти
$data = json_decode($json, true);
if ($data === null) {
    die('Помилка json_decode()');
}

// 4. Об’єднуємо всі записи людей в один масив
$people = [];

// Якщо структура має кілька масивів з людьми, наприклад:
// ["group1" => [...], "group2" => [...]], проходимо по всьому
foreach ($data as $block) {
    if (is_array($block)) {
        foreach ($block as $person) {
            if (is_array($person)) {
                $people[] = $person;
            }
        }
    }
}

// На випадок, якщо все вже було одним масивом людей
if (empty($people) && is_array($data)) {
    $people = $data;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна 8 – JSON API</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="page-wrap">
        <h1>Linkin Park</h1>
        <nav>
            <ul>
                <li><a href="index.html">Головна</a></li>
                <li><a href="about.html">Про гурт</a></li>
                <li><a href="hybrid-theory.html">Hybrid Theory</a></li>
                <li><a href="meteora.html">Meteora</a></li>
                <li><a href="contacts.html">Контакти</a></li>
                <li><a href="animation.html">Анімація</a></li>
                <li><a href="survey.php">Опитування</a></li>
                <li><a href="jokes.html">Жарти</a></li>
                <li><a href="lab8.php" class="current">JSON API</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="page-wrap">
        <h2>Дані з JSON API</h2>

        <section class="section-card">
            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Ім’я</th>
                    <th>Прізвище</th>
                    <th>Вік</th>
                    <th>Інше</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($people)): ?>
                    <?php foreach ($people as $i => $person): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo htmlspecialchars($person['first_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($person['last_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($person['age'] ?? ''); ?></td>
                            <td>
                                <?php
                                // Виводимо всі інші поля людини
                                foreach ($person as $key => $value) {
                                    if (in_array($key, ['first_name', 'last_name', 'age'])) continue;
                                    echo htmlspecialchars($key . ': ' . $value) . '<br>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">Немає даних для відображення.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</main>

<footer>
    <div class="page-wrap">
        <p>2025 - Linkin Park</p>
    </div>
</footer>
</body>
</html>
