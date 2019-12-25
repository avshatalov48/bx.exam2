<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("ex2-97");
?><?$APPLICATION->IncludeComponent(
	"simplecomp:ex2-97", 
	".default", 
	array(
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"FIELD_AUTHOR_CODE" => "UF_AUTHOR_TYPE",
		"NEWS_IBLOCK_ID" => "1",
		"PROPERTY_AUTHOR_CODE" => "AUTHOR_CODE",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>