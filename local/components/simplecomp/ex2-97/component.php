<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if (!Loader::includeModule("iblock")) {
    ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
    return;
}

global $USER;
$arResult["COUNT"] = 0;

// Неавторизованному пользователю данные не выводятся
if ($USER->IsAuthorized()) {
    $iCurUserId = $USER->GetId();

    // Получаем тип текущего пользователя по ID
    $iCurUserType = \CUser::GetList(
        ($by = "id"),
        ($order = "asc"),
        ["ID" => $USER->GetId()],
        ["SELECT" => [$arParams["FIELD_AUTHOR_CODE"]]]
    )->Fetch()[$arParams["FIELD_AUTHOR_CODE"]];

    // Зависимость кеширования – от пользователя
    if ($this->startResultCache(false, $iCurUserType) && !empty($iCurUserType)) {
        // Получаем всех пользователей такого же типа, как у текущего, кроме текущего
        $resUsers = \CUser::GetList(
            ($by = "id"),
            ($order = "desc"),
            [
                // Отображаются только те авторы, у которых тот же «тип» что и у текущего пользователя
                $arParams["FIELD_AUTHOR_CODE"] => $iCurUserType,
                // Текущий пользователь и его новости – не выводятся
                "!ID"                          => $iCurUserId,
            ],
            ["SELECT" => ["LOGIN", "ID"]]
        );
        while ($arUser = $resUsers->Fetch()) {
            $arUserList[$arUser["ID"]] = ["LOGIN" => $arUser["LOGIN"]];
            $arUserIds[] = $arUser["ID"];
        }

        if (!empty($arUserIds)) {
            $arNewsList = [];

            // Получаем новости, принадлежащие пользователем такого же типа, как у текущего, кроме текущего пользователя
            $resNews = \CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_ID"                                      => $arParams["NEWS_IBLOCK_ID"],
                    // Новости, в которых в авторстве присутствует текущий пользователь, не выводятся у других авторов
                    "!PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"] => $iCurUserId,
                    "PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"]  => $arUserIds,
                ],
                false,
                false,
                [
                    "NAME",
                    "ACTIVE_FROM",
                    "ID",
                    "IBLOCK_ID",
                    "PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"]
                ]
            );

            while ($arNewsItem = $resNews->Fetch()) {
                // Группируем новости по пользователям
                $iAuthorId = $arNewsItem["PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"] . "_VALUE"];
                $arUserList[$iAuthorId]["NEWS"][] = $arNewsItem;

                // Составляем массив из уникальных новостей для подсчета
                if (empty($arNewsList[$arNewsItem["ID"]])) {
                    $arNewsList[$arNewsItem["ID"]] = $arNewsItem;
                }
            };

            $iNewsCount = count($arNewsList);

            $arResult["AUTHORS"] = $arUserList;
            $arResult["COUNT"] = $iNewsCount;

            $this->SetResultCacheKeys(["COUNT"]);
            $this->IncludeComponentTemplate();
        }
    }

    // В компоненте устанавливать заголовок страницы: «Новостей [Количество]». Где Количество – количество выводимых уникальных новостей.
    // Заголовок должен устанавливаться в файле component.php. Этот функционал является логикой компонента и не должен «теряться» при смене шаблона.
    $APPLICATION->SetTitle(GetMessage("COUNT_NEWS", ['#COUNT#' => $arResult["COUNT"]]));
}