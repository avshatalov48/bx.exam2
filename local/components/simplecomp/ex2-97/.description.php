<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentDescription = [
    // "NAME" - название компонента
    "NAME"        => GetMessage('EX2_97_NAME'),
    // "DESCRIPTION" - описание компонента
    "DESCRIPTION" => GetMessage('EX2_97_NAME'),
    // "CACHE_PATH" - если значение равно "Y", отображается кнопка очистки кэша компонента в режиме редактирования сайта
    "CACHE_PATH"  => "Y",
    "SORT"        => 1,
    "PATH"        => [
        // "ID" - код ветки дерева
        "ID"   => "exam2",
        // "NAME" - название ветки дерева
        "NAME" => GetMessage('EX2_97_PATH_NAME'),
    ],
];