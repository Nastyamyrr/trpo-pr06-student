<?php

/**
 * Простой механизм передачи данных в шаблон (подготовка к View в MVC).
 *
 * @param string $template Путь относительно templates/ (без .php)
 * @param array  $data     Ассоциативный массив переменных для шаблона
 */
function render(string $template, array $data = []): void
{
    $path = __DIR__ . '/../templates/' . $template . '.php';

    if (!file_exists($path)) {
        http_response_code(500);
        echo 'Шаблон не найден: ' . htmlspecialchars($template, ENT_QUOTES, 'UTF-8');
        return;
    }

    // Извлекаем переменные в локальную область видимости шаблона
    extract($data, EXTR_SKIP);

    require $path;
}

/**
 * Безопасный вывод HTML.
 */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
