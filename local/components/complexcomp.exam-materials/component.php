<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if($arParams["USE_FILTER"]=="Y")
{
	if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
		$arParams["FILTER_NAME"] = "arrFilter";
}
else
	$arParams["FILTER_NAME"] = "";

// Для задания путей по умолчанию для работы в ЧПУ режиме. Каждый элемент массива является шаблоном пути
$arDefaultUrlTemplates404 = array(
	"sections_top" => "",
	"section" => "#SECTION_ID#/",
	"detail" => "#SECTION_ID#/#ELEMENT_ID#/",
	"exampage" => "#PARAM1#/?PARAM2=#PARAM2#",
);

// Для задания псевдонимов по умолчанию переменных в режиме ЧПУ. Как правило, этот массив пуст (используются реальные имена переменных).
$arDefaultVariableAliases404 = array();

// Для задания псевдонимов по умолчанию переменных в режиме не ЧПУ. Как правило, этот массив пуст, то есть используются реальные имена переменных.
$arDefaultVariableAliases = array();

// Для задания списка переменных, которые компонент может принимать в HTTP запросе и которые могут иметь псевдонимы.
// Каждый элемент массива является именем переменной.
$arComponentVariables = array(
	"SECTION_ID",
	"SECTION_CODE",
	"ELEMENT_ID",
	"ELEMENT_CODE",
	"PARAM1",
	"PARAM2",
);

if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();

	// Метод служит для поддержки ЧПУ режима в комплексных компонентах.
    // Метод принимает на входе шаблоны путей по умолчанию и шаблоны путей,
    // переданные во входных параметрах компонента и заменяет те шаблоны путей по умолчанию,
    // которые были переопределены во входных параметрах компонента. Статический метод.
	$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);

    // Метод принимает псевдонимы переменных по умолчанию и псевдонимы переменных,
    // переданные во входных параметрах компонента и заменяет те псевдонимы переменных по умолчанию,
    // которые были переопределены во входных параметрах компонента. Статический метод.
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

	$engine = new CComponentEngine($this);
	if (CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_FOLDER"],
		$arUrlTemplates,
		$arVariables
	);

	$b404 = false;
	if(!$componentPage)
	{
		$componentPage = "sections_top";
		$b404 = true;
	}

	if(
		$componentPage == "section"
		&& isset($arVariables["SECTION_ID"])
		&& intval($arVariables["SECTION_ID"])."" !== $arVariables["SECTION_ID"]
	)
		$b404 = true;

	if($b404 && CModule::IncludeModule('iblock'))
	{
		$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
		if ($folder404 != "/")
			$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
		if (substr($folder404, -1) == "/")
			$folder404 .= "index.php";

		if ($folder404 != $APPLICATION->GetCurPage(true))
		{
			\Bitrix\Iblock\Component\Tools::process404(
				""
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SHOW_404"] === "Y")
				,$arParams["FILE_404"]
			);
		}
	}

	CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases,
	);
}
else
{
	$arVariables = array();

	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

	$componentPage = "";

	if(isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
		$componentPage = "section";
	elseif(isset($arVariables["SECTION_CODE"]) && strlen($arVariables["SECTION_CODE"]) > 0)
		$componentPage = "section";
	// Условие, по которому открывается страница exampage.php без ЧПУ режима: задано значение переменной PARAM1.
	elseif(isset($arVariables["PARAM1"]) && strlen($arVariables["PARAM1"]) > 0)
		$componentPage = "exampage";
	else
		$componentPage = "sections_top";

	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => Array(
			"section" => htmlspecialcharsbx($APPLICATION->GetCurPage())."?".$arVariableAliases["SECTION_ID"]."=#SECTION_ID#",
			"detail" => htmlspecialcharsbx($APPLICATION->GetCurPage())."?".$arVariableAliases["SECTION_ID"]."=#SECTION_ID#"."&".$arVariableAliases["ELEMENT_ID"]."=#ELEMENT_ID#",
			// Строим шаблон URL для не ЧПУ
            "exampage" => htmlspecialcharsbx($APPLICATION->GetCurPage())."?".$arVariableAliases["PARAM1"]."=#PARAM1#" . '&' . $arVariableAliases["PARAM2"]."=#PARAM2#",
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}
$this->IncludeComponentTemplate($componentPage);
?>