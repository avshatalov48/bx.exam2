<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент: [ex2-71], [ex2-107], [ex2-60], [ex2-49]");
?><?$APPLICATION->IncludeComponent(
	"simplecomp:ex2-71", 
	".default", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY" => "FIRMS",
		"ID_IB_CATALOG" => "2",
		"ID_IB_CLASS" => "7",
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENTS_PER_PAGE" => "2"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>