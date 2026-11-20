<?php

/**
 * Точка входа приложения.
 * Все запросы проходят через этот файл.
 */

declare(strict_types=1);

// Сессия должна запускаться до любого вывода
session_start();

require_once __DIR__ . '/../src/storage.php';
require_once __DIR__ . '/../src/session.php';
require_once __DIR__ . '/../src/view.php';

// Маршрутизация по параметру page (подготовка к MVC-роутеру)
$page = $_GET['page'] ?? 'list';
$page = is_string($page) ? trim($page) : 'list';

// ---------- Обработка POST-действий (PRG: Post/Redirect/Get) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_note':
            handleAddNote();
            break;

        case 'set_name':
            handleSetName();
            break;

        case 'clear_name':
            clearDisplayName();
            redirect('list');
            break;

        case 'toggle_favorite':
            handleToggleFavorite();
            break;

        default:
            // Неизвестное действие — просто редирект
            redirect('list');
    }
}

// ---------- GET-маршруты ----------
switch ($page) {
    case 'list':
        showList();
        break;

    case 'scenarios':
        showScenarios();
        break;

    default:
        show404();
        break;
}

// ==================== Обработчики ====================

function handleAddNote(): void
{
    $title = trim((string)($_POST['title'] ?? ''));
    $error = null;

    if ($title === '') {
        // Сохраняем ошибку в сессии, чтобы показать после редиректа
        $_SESSION['flash_error'] = 'Название заметки не может быть пустым.';
        redirect('list');
        return;
    }

    $notes = loadNotes($error);
    if ($notes === null) {
        $_SESSION['flash_error'] = $error ?? 'Не удалось загрузить заметки.';
        redirect('list');
        return;
    }

    $newNote = [
        'id'    => nextNoteId($notes),
        'title' => $title,
        'done'  => false,
    ];

    $notes[] = $newNote;

    if (!saveNotes($notes)) {
        $_SESSION['flash_error'] = 'Не удалось сохранить заметку.';
        redirect('list');
        return;
    }

    $_SESSION['flash_success'] = 'Заметка добавлена.';
    redirect('list');
}

function handleSetName(): void
{
    $name = trim((string)($_POST['display_name'] ?? ''));
    setDisplayName($name);
    $_SESSION['flash_success'] = 'Имя обновлено.';
    redirect('list');
}

function handleToggleFavorite(): void
{
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        toggleFavorite($id);
    }
    redirect('list');
}

function redirect(string $page): never
{
    $url = '/index.php?page=' . urlencode($page);
    // Для встроенного сервера PHP относительный путь работает
    header('Location: ' . $url);
    exit;
}

// ==================== Страницы ====================

function showList(): void
{
    $error = null;
    $notes = loadNotes($error);
    $users = loadUsers(); // ошибки users не критичны

    $flashError   = $_SESSION['flash_error'] ?? null;
    $flashSuccess = $_SESSION['flash_success'] ?? null;
    unset($_SESSION['flash_error'], $_SESSION['flash_success']);

    $favorites = getFavorites();

    // Подготовка данных до подключения шаблона
    $viewData = [
        'pageTitle'    => 'Список учебных заметок',
        'displayName'  => getDisplayName(),
        'notes'        => $notes,
        'loadError'    => $error,
        'users'        => $users ?? [],
        'favorites'    => $favorites,
        'flashError'   => $flashError,
        'flashSuccess' => $flashSuccess,
    ];

    render('list', $viewData);
}

function showScenarios(): void
{
    $error = null;
    $scenarios = loadJson('session_scenarios.json', $error);

    $viewData = [
        'pageTitle'   => 'Сценарии сессии',
        'displayName' => getDisplayName(),
        'scenarios'   => $scenarios,
        'loadError'   => $error,
    ];

    render('scenarios', $viewData);
}

function show404(): void
{
    http_response_code(404);

    $viewData = [
        'pageTitle'   => 'Страница не найдена',
        'displayName' => getDisplayName(),
        'requested'   => $_GET['page'] ?? '',
    ];

    render('404', $viewData);
}
