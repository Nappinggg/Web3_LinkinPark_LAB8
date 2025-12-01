<?php
// Флаг AJAX-відповіді
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$submitted_at = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name   = trim($_POST["name"] ?? "");
    $email  = trim($_POST["email"] ?? "");
    $q1     = $_POST["q1"] ?? "";
    $q2     = $_POST["q2"] ?? "";
    $q3     = trim($_POST["q3"] ?? "");

    $dir = __DIR__ . "/survey";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    date_default_timezone_set("Europe/Kyiv");
    $timestamp = date("Y-m-d_H-i-s");
    $fileName = $dir . "/survey_" . $timestamp . ".txt";

    $content  = "Час заповнення: " . date("Y-m-d H:i:s") . PHP_EOL;
    $content .= "Ім'я: " . $name . PHP_EOL;
    $content .= "Email: " . $email . PHP_EOL;
    $content .= "Питання 1: Улюблений альбом LP: " . $q1 . PHP_EOL;
    $content .= "Питання 2: Як часто слухаєте LP: " . $q2 . PHP_EOL;
    $content .= "Питання 3: Коментар: " . $q3 . PHP_EOL;

    file_put_contents($fileName, $content);

    $submitted_at = date("d.m.Y H:i:s");

    // Якщо запит AJAX – повертаємо JSON і завершуємо скрипт
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'ok',
            'submitted_at' => $submitted_at,
            'message' => 'Ваша відповідь збережена.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Опитування про Linkin Park</title>
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
                <li><a href="survey.php" class="current">Опитування</a></li>
                <li><a href="jokes.html">Жарти</a></li>
            </ul>
        </nav>
    </div>
</header>


<main>
    <div class="page-wrap">
        <h2>Опитування: улюблений альбом Linkin Park</h2>

        <section id="survey-message" class="section-card" style="display: <?php echo $submitted_at && !$isAjax ? 'block' : 'none'; ?>;">
            <h3>Дякуємо за відповідь!</h3>
            <?php if ($submitted_at && !$isAjax): ?>
                <p>Ваша форма успішно надіслана.</p>
                <p>Час та дата заповнення:
                    <strong><?php echo htmlspecialchars($submitted_at); ?></strong>
                </p>
            <?php else: ?>
                <p></p>
            <?php endif; ?>
        </section>

        <section class="section-card">
            <h3>Анкета респондента</h3>
            <form id="survey-form" method="POST" action="survey.php" class="contacts-grid">
                <div>
                    <label for="name">Ім’я респондента:</label><br>
                    <input type="text" id="name" name="name" required>
                </div>

                <div>
                    <label for="email">Email респондента:</label><br>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="q1">Улюблений альбом Linkin Park:</label><br>
                    <select id="q1" name="q1" required>
                        <option value="">Оберіть варіант</option>
                        <option value="Hybrid Theory">Hybrid Theory</option>
                        <option value="Meteora">Meteora</option>
                        <option value="Minutes to Midnight">Minutes to Midnight</option>
                        <option value="A Thousand Suns">A Thousand Suns</option>
                        <option value="Інший">Інший</option>
                    </select>
                </div>

                <div>
                    <label for="q2">Як часто ви слухаєте Linkin Park?</label><br>
                    <select id="q2" name="q2" required>
                        <option value="">Оберіть варіант</option>
                        <option value="Щодня">Щодня</option>
                        <option value="Кілька разів на тиждень">Кілька разів на тиждень</option>
                        <option value="Кілька разів на місяць">Кілька разів на місяць</option>
                        <option value="Рідко">Рідко</option>
                    </select>
                </div>

                <div>
                    <label for="q3">Ваш короткий відгук про гурт:</label><br>
                    <textarea id="q3" name="q3" rows="4" cols="40"
                              placeholder="Напишіть кілька слів..."></textarea>
                </div>

                <div>
                    <button type="submit" class="contact-link">
                        <span>Надіслати відповідь</span>
                        
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>

<footer>
    <div class="page-wrap">
        <p>2025 - Linkin Park</p>
    </div>
</footer>

<script>
    const form = document.getElementById('survey-form');
    const messageBox = document.getElementById('survey-message');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // не перезавантажувати сторінку

        const formData = new FormData(form);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'survey.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        const resp = JSON.parse(xhr.responseText);
                        if (resp.status === 'ok') {
                            messageBox.style.display = 'block';
                            messageBox.innerHTML =
                                '<h3>Дякуємо за відповідь!</h3>' +
                                '<p>Ваша форма успішно надіслана.</p>' +
                                '<p>Час та дата заповнення: <strong>' +
                                resp.submitted_at +
                                '</strong></p>';
                            form.reset();
                        } else {
                            alert('Сталася помилка при збереженні відповіді.');
                        }
                    } catch (e) {
                        alert('Некоректна відповідь від сервера.');
                    }
                } else {
                    alert('Помилка запиту: ' + xhr.status);
                }
            }
        };

        xhr.send(formData);
    });
</script>
</body>
</html>
