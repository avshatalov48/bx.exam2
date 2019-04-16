<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("EX2_70_NAME"),
	"DESCRIPTION" => GetMessage("EX2_70_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"SORT" => 1,
	"PATH" => array(
		// Раздел для отображения компонента в визуальном редакторе
	    "ID" => GetMessage("EX2_70_SECTION_NAME"),
	),
);