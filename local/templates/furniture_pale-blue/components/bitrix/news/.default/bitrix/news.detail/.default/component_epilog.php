<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

global $APPLICATION;

//<ex2-108>
/*
В component_epilog.php происходит проверка на наличие в массиве $arResult ключа CANONICAL и если условие выполняется, мы устанавливаем заданное свойство страницы
*/

// У автора в тексте - if($arParams['CANONICAL'] == 'Y'){ - так работать не будет
if ($arParams['CANONICAL']) {
    $APPLICATION->SetPageProperty("canonical", $arResult['CANONICAL']['NAME']);
}
//</ex2-108>


//<ex2-104>
if ($_GET['TYPE'] == 'REPORT_RESULT') {
    if ($_GET['ID']) {
        echo '<script>
		var textElem = document.getElementById("ajax-report-text");
		textElem.innerText = "Ваше мнение учтено, №' . $_GET['ID'] . '";
		window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
	</script>';
    } else {
        echo '<script>
		var textElem = document.getElementById("ajax-report-text");
		textElem.innerText = "Ошибка";
		window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
	</script>';
    }
} else {
    if (isset($_GET['ID'])) {
        $jsonObject = [];
        if (Loader::includeModule('iblock')) {
            $arUser = '';
            if ($USER->IsAuthorized()) {
                $arUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
            } else {
                $arUser = "Не авторизован";
            }
            $arFields = [
                // ИБ "Жалобы на новости"
                'IBLOCK_ID'       => COMPLAIN_NEWS_IBLOCK_ID,
                'NAME'            => 'Новость ' . $_GET['ID'],
                'ACTIVE_FROM'     => ConvertTimeStamp(time(), "FULL"),
                'PROPERTY_VALUES' => [
                    'USER_CODE' => $arUser,
                    'NEWS_CODE' => $_GET['ID'],
                ],
            ];
            $element = new \CIBlockElement(false);
            if ($elId = $element->Add($arFields)) {
                $jsonObject['ID'] = $elId;
                if ($_GET['TYPE'] == 'REPORT_AJAX') {
                    $APPLICATION->RestartBuffer();
                    echo json_encode($jsonObject);
                    exit;
                } else {
                    if ($_GET['TYPE'] == 'REPORT_GET') {
                        LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT&ID=" . $jsonObject['ID']);
                    }
                }
            } else {
                LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT");
            }
        }
    }
}
//</ex2-104>