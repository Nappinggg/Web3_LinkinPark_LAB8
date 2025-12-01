<?php
// 1. URL з GET-параметрами
$url = 'http://lab.vntu.org/api-server/lab8.php?user=student&pass=p@ssw0rd';

// 2. Отримання JSON
$json = file_get_contents($url);
if ($json === false) {
    die('Не вдалося отримати дані з API');
}

// 3. Перетворення JSON у структури PHP
$data = json_decode($json, true);
if ($data === null) {
    die('Помилка json_decode()');
}

// 4. Об’єднуємо всі записи людей в один масив
$people = [];

foreach ($data as $block) {
    if (is_array($block)) {
        foreach ($block as $person) {
            if (is_array($person)) {
                $people[] = $person;
            }
        }
    }
}

if (empty($people) && is_array($data)) {
    $people = $data;
}

// 5. Визначаємо всі можливі ключі (поля)
$allKeys = [];
foreach ($people as $person) {
    if (is_array($person)) {
        $allKeys = array_unique(array_merge($allKeys, array_keys($person)));
    }
}
sort($allKeys);
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
            <?php if (!empty($people) && !empty($allKeys)): ?>
                <table border="1" cellpadding="6" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <?php foreach ($allKeys as $key): ?>
                            <th><?php echo htmlspecialchars($key); ?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($people as $i => $person): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <?php foreach ($allKeys as $key): ?>
                                <td><?php echo htmlspecialchars($person[$key] ?? ''); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Немає даних для відображення.</p>
            <?php endif; ?>
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
