<?php
/**
 * Представление списка заметок.
 * Получает готовые данные, не читает файлы и не меняет сессию.
 *
 * Ожидаемые переменные:
 * $pageTitle, $displayName, $notes, $loadError, $users, $favorites,
 * $flashError, $flashSuccess
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
        .user-bar { display: flex; align-items: center; gap: 10px; font-size: 14px; flex-wrap: wrap; }
        .user-bar form { display: inline; margin: 0; }
        .user-bar button, .btn { display: inline-block; background: #315efb; color: white; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .user-bar button.secondary, .btn.secondary { background: #4b5563; }
        .user-bar input[type="text"] { padding: 7px 10px; border-radius: 6px; border: 1px solid #374151; background: #1f2937; color: white; width: 130px; }
        .wrap { max-width: 960px; margin: 28px auto; padding: 0 20px; }
        .card { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 8px 28px rgba(0,0,0,.06); margin-bottom: 20px; }
        .pill { display: inline-block; background: #e9efff; color: #315efb; border-radius: 999px; padding: 4px 10px; font-size: 12px; font-weight: 600; }
        .muted { color: #6b7280; }
        .flash { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; }
        .flash.error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .flash.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #e5e7eb; }
        th { font-size: 13px; color: #6b7280; font-weight: 600; }
        .done { text-decoration: line-through; color: #9ca3af; }
        .fav-btn { background: transparent; border: 1px solid #d1d5db; border-radius: 6px; padding: 4px 8px; cursor: pointer; font-size: 16px; }
        .fav-btn.active { color: #f59e0b; border-color: #fbbf24; }
        .form-row { display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap; }
        .form-row input[type="text"] { flex: 1; min-width: 200px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; }
        nav.main { margin-bottom: 16px; }
        nav.main a { color: #315efb; text-decoration: none; margin-right: 16px; font-size: 14px; }
        nav.main a:hover { text-decoration: underline; }
        .error-box { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 16px; border-radius: 10px; }
        .status-ok { color: #059669; font-weight: 600; }
        .status-no { color: #9ca3af; }
        @media (max-width: 640px) { header { padding: 14px 16px; } .user-bar { width: 100%; } }
    </style>
</head>
<body>
<header>
    <div class="brand">Практическая работа 6</div>
    <div class="user-bar">
        <span>Пользователь: <strong><?= e($displayName) ?></strong></span>
        <form method="post" action="/index.php?page=list">
            <input type="hidden" name="action" value="set_name">
            <input type="text" name="display_name" placeholder="Имя" value="<?= e($displayName === 'Гость' ? '' : $displayName) ?>" maxlength="40">
            <button type="submit">Сохранить</button>
        </form>
        <form method="post" action="/index.php?page=list">
            <input type="hidden" name="action" value="clear_name">
            <button type="submit" class="secondary">Сбросить</button>
        </form>
    </div>
</header>

<div class="wrap">
    <nav class="main">
        <a href="/index.php?page=list">Заметки</a>
        <a href="/index.php?page=scenarios">Сценарии сессии</a>
    </nav>

    <?php if (!empty($flashError)): ?>
        <div class="flash error"><?= e($flashError) ?></div>
    <?php endif; ?>
    <?php if (!empty($flashSuccess)): ?>
        <div class="flash success"><?= e($flashSuccess) ?></div>
    <?php endif; ?>

    <div class="card">
        <span class="pill">учебные заметки</span>
        <h1 style="margin: 10px 0 6px;"><?= e($pageTitle) ?></h1>
        <p class="muted">Данные читаются из <code>data/notes.json</code>. Имя пользователя и избранное хранятся в session.</p>

        <?php if ($loadError !== null): ?>
            <div class="error-box">
                <strong>Ошибка загрузки данных</strong><br>
                <?= e($loadError) ?>
            </div>
        <?php elseif (empty($notes)): ?>
            <p class="muted">Заметок пока нет. Добавьте первую.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Статус</th>
                        <th>Избранное</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($notes as $note): ?>
                    <?php
                    $id = (int)($note['id'] ?? 0);
                    $title = (string)($note['title'] ?? '');
                    $done = !empty($note['done']);
                    $isFav = in_array($id, $favorites, true);
                    ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td class="<?= $done ? 'done' : '' ?>"><?= e($title) ?></td>
                        <td>
                            <?php if ($done): ?>
                                <span class="status-ok">готово</span>
                            <?php else: ?>
                                <span class="status-no">в работе</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" action="/index.php?page=list" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_favorite">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit" class="fav-btn <?= $isFav ? 'active' : '' ?>" title="Избранное">
                                    <?= $isFav ? '★' : '☆' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2 style="margin-top:0;">Добавить заметку</h2>
        <form method="post" action="/index.php?page=list">
            <input type="hidden" name="action" value="add_note">
            <div class="form-row">
                <input type="text" name="title" placeholder="Название заметки" required maxlength="200">
                <button type="submit" class="btn">Добавить</button>
            </div>
        </form>
    </div>

    <?php if (!empty($users)): ?>
    <div class="card">
        <h2 style="margin-top:0;">Пользователи из dataset</h2>
        <p class="muted">Данные из <code>users.json</code> (только для демонстрации чтения).</p>
        <ul>
            <?php foreach ($users as $u): ?>
                <li><?= e($u['name'] ?? '') ?> (id: <?= (int)($u['id'] ?? 0) ?>)</li>
            <?php endforeach; ?>
        </ul>
        <p class="muted">Можно быстро подставить имя:</p>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <?php foreach ($users as $u): ?>
                <form method="post" action="/index.php?page=list">
                    <input type="hidden" name="action" value="set_name">
                    <input type="hidden" name="display_name" value="<?= e($u['name'] ?? '') ?>">
                    <button type="submit" class="btn secondary"><?= e($u['name'] ?? '') ?></button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
