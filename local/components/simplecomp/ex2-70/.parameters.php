<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(

        "IBLOCK_CATALOG_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("EX2_70_IBLOCK_CATALOG_ID"),
        ),

        "IBLOCK_NEWS_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("EX2_70_IBLOCK_NEWS_ID"),
        ),

        "USER_PROPERTY" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("EX2_70_USER_PROPERTY"),
        ),

        "CACHE_TIME" => Array("DEFAULT" => 3600),
    ),
);