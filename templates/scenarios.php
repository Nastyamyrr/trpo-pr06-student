<?php
/**
 * Страница сценариев session_scenarios.json
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
        header { background: #111827; color: white; padding: 18px 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .brand { font-weight: 800; font-size: 20px; }
        .user-bar { font-size: 14px; }
        .wrap { max-width: 960px; margin: 28px auto; padding: 0 20px; }
        .card { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 8px 28px rgba(0,0,0,.06); margin-bottom: 20px; }
        .pill { display: inline-block; background: #e9efff; color: #315efb; border-radius: 999px; padding: 4px 10px; font-size: 12px; font-weight: 600; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #e5e7eb; }
        th { font-size: 13px; color: #6b7280; font-weight: 600; }
        nav.main { margin-bottom: 16px; }
        nav.main a { color: #315efb; text-decoration: none; margin-right: 16px; font-size: 14px; }
        .error-box { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 16px; border-radius: 10px; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>
<header>
    <div class="brand">Практическая работа 6</div>
    <div class="user-bar">Пользователь: <strong><?= e($displayName) ?></strong></div>
</header>

<div class="wrap">
    <nav class="main">
        <a href="/index.php?page=list">Заметки</a>
        <a href="/index.php?page=scenarios">Сценарии сессии</a>
    </nav>

    <div class="card">
        <span class="pill">session_scenarios.json</span>
        <h1 style="margin: 10px 0 6px;"><?= e($pageTitle) ?></h1>
        <p class="muted">
            Сценарии из dataset. Проверьте их в обычной вкладке и в приватном окне:
            в приватном режиме сессия всегда начинается заново (ожидается «Гость»).
        </p>

        <?php if ($loadError !== null): ?>
            <div class="error-box"><?= e($loadError) ?></div>
        <?php elseif (empty($scenarios)): ?>
            <p class="muted">Сценарии не найдены.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Сценарий</th>
                        <th>Значение</th>
                        <th>Ожидание</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($scenarios as $s): ?>
                    <tr>
                        <td><code><?= e($s['scenario'] ?? '') ?></code></td>
                        <td><?= e($s['value'] ?? '—') ?></td>
                        <td><?= e($s['expected'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h3>Как проверить</h3>
        <ol class="muted">
            <li><strong>new_session</strong> — откройте сайт в приватном окне: имя должно быть «Гость».</li>
            <li><strong>set_name</strong> — на странице заметок сохраните имя (например, Student A): оно должно сохраниться после перезагрузки.</li>
            <li><strong>clear</strong> — нажмите «Сбросить»: имя снова станет «Гость».</li>
        </ol>
        <p class="muted">Текущее имя в этой сессии: <strong><?= e($displayName) ?></strong></p>
    </div>
</div>
</body>
</html>
