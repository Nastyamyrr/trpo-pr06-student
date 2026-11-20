<?php
/**
 * Простая страница 404 (подготовка к маршрутизации MVC).
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> — ПР6</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f6fb; color: #1f2937; font-family: Arial, Helvetica, sans-serif; line-height: 1.5; }
        header { background: #111827; color: white; padding: 18px 32px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-weight: 800; font-size: 20px; }
        .wrap { max-width: 640px; margin: 60px auto; padding: 0 20px; text-align: center; }
        .card { background: white; border-radius: 14px; padding: 40px 32px; box-shadow: 0 8px 28px rgba(0,0,0,.06); }
        .code { font-size: 64px; font-weight: 800; color: #315efb; margin: 0; }
        .muted { color: #6b7280; }
        a.btn { display: inline-block; background: #315efb; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; margin-top: 16px; }
    </style>
</head>
<body>
<header>
    <div class="brand">Практическая работа 6</div>
    <span>ТРПО</span>
</header>

<div class="wrap">
    <div class="card">
        <p class="code">404</p>
        <h1 style="margin-top:0;"><?= e($pageTitle) ?></h1>
        <p class="muted">
            Запрошенная страница
            <?php if ($requested !== ''): ?>
                <code><?= e((string)$requested) ?></code>
            <?php endif; ?>
            не существует.
        </p>
        <p class="muted">Это простая обработка неизвестного маршрута — заготовка под роутер MVC.</p>
        <a class="btn" href="/index.php?page=list">Вернуться к заметкам</a>
    </div>
</div>
</body>
</html>
