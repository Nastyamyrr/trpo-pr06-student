<?php

/**
 * Загрузка и сохранение JSON-данных.
 * Централизованная работа с файловым хранилищем.
 */

function getDataPath(string $filename): string
{
    return __DIR__ . '/../data/' . $filename;
}

/**
 * Загружает JSON-файл. При ошибке возвращает null и пишет сообщение в $error.
 *
 * @return array|null
 */
function loadJson(string $filename, ?string &$error = null): ?array
{
    $path = getDataPath($filename);

    if (!file_exists($path)) {
        $error = "Файл данных «{$filename}» не найден.";
        return null;
    }

    $raw = @file_get_contents($path);
    if ($raw === false) {
        $error = "Не удалось прочитать файл «{$filename}».";
        return null;
    }

    try {
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        $error = "Файл «{$filename}» повреждён или содержит некорректный JSON.";
        return null;
    }

    if (!is_array($data)) {
        $error = "Файл «{$filename}» должен содержать JSON-массив.";
        return null;
    }

    return $data;
}

/**
 * Сохраняет массив в JSON-файл.
 */
function saveJson(string $filename, array $data): bool
{
    $path = getDataPath($filename);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        return false;
    }

    return file_put_contents($path, $json . PHP_EOL, LOCK_EX) !== false;
}

/**
 * Загружает заметки.
 *
 * @return array|null
 */
function loadNotes(?string &$error = null): ?array
{
    return loadJson('notes.json', $error);
}

/**
 * Сохраняет заметки.
 */
function saveNotes(array $notes): bool
{
    return saveJson('notes.json', $notes);
}

/**
 * Загружает пользователей.
 *
 * @return array|null
 */
function loadUsers(?string &$error = null): ?array
{
    return loadJson('users.json', $error);
}

/**
 * Генерирует новый уникальный id для заметок.
 */
function nextNoteId(array $notes): int
{
    if (empty($notes)) {
        return 1;
    }

    $max = 0;
    foreach ($notes as $note) {
        $id = (int)($note['id'] ?? 0);
        if ($id > $max) {
            $max = $id;
        }
    }

    return $max + 1;
}
