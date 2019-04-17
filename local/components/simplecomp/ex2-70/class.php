<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

/**
 * Class Simplecomp
 */
class Simplecomp extends CBitrixComponent
{
    /**
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        // Приводим значения к числу
        $arParams["IBLOCK_CATALOG_ID"] = (int)$arParams["IBLOCK_CATALOG_ID"];
        $arParams["IBLOCK_NEWS_ID"] = (int)$arParams["IBLOCK_NEWS_ID"];

        // Значение по умолчанию
        if (!$arParams["CACHE_TIME"]) {
            $arParams["CACHE_TIME"] = 3600;
        }

        return parent::onPrepareComponentParams($arParams);
    }

    public function setArResult()
    {
        // <Выборка разделов из ИБ "Продукция">
        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_CATALOG_ID"],
            "ACTIVE" => "Y",
            // Фильтрация только разделов с новостями
            "!" . $this->arParams["USER_PROPERTY"] => false,
        ];

        $arSelect = [
            "ID",
            "IBLOCK_ID",
            "NAME",
            $this->arParams["USER_PROPERTY"],
        ];

        $res = CIBlockSection::GetList([], $arFilter, false, $arSelect);

        $arTotal = [];
        while ($arRes = $res->Fetch()) {
            $arTotal[] = $arRes;
        }

        // array_column — Возвращает массив из значений одного столбца входного массива
        $arSectionId = array_column($arTotal, "ID");
        // </Выборка разделов из ИБ "Продукция">


        // <Выборка элементов из ИБ "Продукция">
        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_CATALOG_ID"],
            "ACTIVE" => "Y",
            // Выбирем элементы только по необходимым разделам
            $this->arParams["USER_PROPERTY"] => $arSectionId,
        ];

        $arSelect = [
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "PROPERTY_PRICE",
            "PROPERTY_ARTNUMBER",
            "PROPERTY_MATERIAL",
        ];

        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

        $iCount = 0;
        while ($arRes = $res->Fetch()) {
            foreach ($arTotal as &$arSection) {
                if ($arRes["IBLOCK_SECTION_ID"] == $arSection["ID"]) {
                    // Добавляем элементы к секциям
                    $arSection["ITEMS"][] = $arRes;
                }
            }
            $iCount++;
        }

        // </Выборка элементов из ИБ "Продукция">


        // Количество элементов
        $this->arResult["COUNT"] = $iCount;

        // <Выборка элементов из ИБ "Новости">
        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_NEWS_ID"],
            "ACTIVE" => "Y",
        ];

        $arSelect = [
            "ID",
            "IBLOCK_ID",
            "NAME",
            "DATE_ACTIVE_FROM",
        ];

        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

        $i = 0;
        while ($arRes = $res->Fetch()) {
            $this->arResult["ITEMS"][$i] = $arRes;
            foreach ($arTotal as &$arSection2) {
                foreach ($arSection2[$this->arParams["USER_PROPERTY"]] as $item) {
                    if ($item == $arRes["ID"]) {
                        $this->arResult["ITEMS"][$i]["ITEMS"][] = $arSection2;
                    }
                }
            }
            $i++;
        }
        // </Выборка элементов из ИБ "Новости">
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\LoaderException
     */
    public function executeComponent()
    {
        if (!Loader::includeModule("iblock")) {
            return;
        }

        if ($this->StartResultCache()) {
            $this->setArResult();
            // Список ключей массива $arResult, которые должны кэшироваться при использовании встроенного кэширования компонентов, иначе закеширует весь массив arResult, кэш сильно разростается
            $this->setResultCacheKeys(["COUNT"]);
            $this->includeComponentTemplate();
        }

        global $APPLICATION;
        $APPLICATION->SetTitle(GetMessage("EX2_70_ELEMENTS_COUNT") . $this->arResult["COUNT"]);
    }
}