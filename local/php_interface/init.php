<?php

/**
 * @const Путь к корневой директории
 */
define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

/**
 * @var string Путь к включаемому файлу
 */
$sPathFileName = ROOT_DIR . "/local/vendor/autoload.php";

/**
 * Проверка существования файла
 */
if (file_exists($sPathFileName)) {
    require_once($sPathFileName);
}