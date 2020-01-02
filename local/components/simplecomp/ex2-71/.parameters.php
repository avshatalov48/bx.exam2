<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = [
    "GROUPS"     => [
    ],
    "PARAMETERS" => [
        // ID инфоблока с каталогом товаров, строка
        "ID_IB_CATALOG" => [
            "PARENT" => "BASE",
            "NAME"   => GetMessage("EX2_71_ID_IB_CATALOG"),
            "TYPE"   => "STRING",
        ],
        // ID инфоблока с классификатором, строка
        "ID_IB_CLASS"   => [
            "PARENT" => "BASE",
            "NAME"   => GetMessage("EX2_71_ID_IB_CLASS"),
            "TYPE"   => "STRING",
        ],
        // Код свойства товара, в котором хранится привязка товара к классификатору, строка
        "CODE_PROPERTY" => [
            "PARENT" => "BASE",
            "NAME"   => GetMessage("EX2_71_CODE_PROPERTY"),
            "TYPE"   => "STRING",
        ],
        // ex2-60
        "ELEMENTS_PER_PAGE" => [
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("EX2_60_ELEMENTS_PER_PAGE"),
            "TYPE"    => "STRING",
            "DEFAULT" => "2",
        ],
        // Время кеширования (сек.)
        "CACHE_TIME"    => ["DEFAULT" => 3600],
        // [Y|N] При отмеченной опции будут учитываться права доступа при кешировании
        "CACHE_GROUPS"  => [
            "PARENT"  => "CACHE_SETTINGS",
            "NAME"    => GetMessage("CP_BPR_CACHE_GROUPS"),
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "Y",
        ],
    ],
];