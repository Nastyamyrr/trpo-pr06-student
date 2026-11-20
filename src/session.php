<?php

/**
 * Работа с сессией пользователя.
 * session_start() вызывается один раз в точке входа.
 */

const SESSION_DISPLAY_NAME = 'display_name';
const SESSION_FAVORITES   = 'favorites';
const DEFAULT_DISPLAY_NAME = 'Гость';

/**
 * Возвращает имя текущего пользователя из сессии.
 */
function getDisplayName(): string
{
    return $_SESSION[SESSION_DISPLAY_NAME] ?? DEFAULT_DISPLAY_NAME;
}

/**
 * Устанавливает имя пользователя.
 */
function setDisplayName(string $name): void
{
    $name = trim($name);
    if ($name === '') {
        unset($_SESSION[SESSION_DISPLAY_NAME]);
        return;
    }
    $_SESSION[SESSION_DISPLAY_NAME] = $name;
}

/**
 * Сбрасывает имя к значению по умолчанию.
 */
function clearDisplayName(): void
{
    unset($_SESSION[SESSION_DISPLAY_NAME]);
}

/**
 * Список избранных id заметок.
 *
 * @return int[]
 */
function getFavorites(): array
{
    $list = $_SESSION[SESSION_FAVORITES] ?? [];
    if (!is_array($list)) {
        return [];
    }
    return array_map('intval', $list);
}

/**
 * Добавляет id в избранное.
 */
function addFavorite(int $id): void
{
    $list = getFavorites();
    if (!in_array($id, $list, true)) {
        $list[] = $id;
        $_SESSION[SESSION_FAVORITES] = $list;
    }
}

/**
 * Удаляет id из избранного.
 */
function removeFavorite(int $id): void
{
    $list = getFavorites();
    $list = array_values(array_filter($list, static fn(int $v) => $v !== $id));
    $_SESSION[SESSION_FAVORITES] = $list;
}

/**
 * Переключает избранное.
 */
function toggleFavorite(int $id): void
{
    if (in_array($id, getFavorites(), true)) {
        removeFavorite($id);
    } else {
        addFavorite($id);
    }
}
