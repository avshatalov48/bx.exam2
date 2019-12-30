<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент: [ex2-71], [ex2-107]");
?><?$APPLICATION->IncludeComponent(
	"simplecomp:ex2-71",
	"",
	Array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY" => "FIRMS",
		"ID_IB_CATALOG" => "2",
		"ID_IB_CLASS" => "7"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>