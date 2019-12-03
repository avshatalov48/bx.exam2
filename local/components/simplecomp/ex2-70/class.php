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

        // Значение по умолчанию, не обязательно, но желательно
        if (!$arParams["CACHE_TIME"]) {
            $arParams["CACHE_TIME"] = 3600;
        }

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Производим выборку
     */
    public function setArResult()
    {
        global $APPLICATION;

        // <Выборка разделов из ИБ "Продукция">
        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_CATALOG_ID"],
            // Должны выбираться только активные разделы
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

        $arProductSectionsTotal = [];
        while ($arRes = $res->Fetch()) {
            $arProductSectionsTotal[] = $arRes;
        }

        // array_column — Возвращает массив из значений одного столбца входного массива
        $arProductSectionsId = array_column($arProductSectionsTotal, "ID");
        // </Выборка разделов из ИБ "Продукция">


        // <Выборка товаров из ИБ "Продукция" по выбранным разделам>
        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_CATALOG_ID"],
            "ACTIVE" => "Y",
            // Выбирем элементы только по необходимым разделам
            $this->arParams["USER_PROPERTY"] => $arProductSectionsId,
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
        $arAllPrice = [];
        while ($arRes = $res->Fetch()) {
            // Массив всех цен товаров
            $arAllPrice[] = $arRes["PROPERTY_PRICE_VALUE"];
            // Все ID товаров
            $arAllId[] = $arRes["ID"];
            foreach ($arProductSectionsTotal as &$arSection) {
                if ($arRes["IBLOCK_SECTION_ID"] == $arSection["ID"]) {
                    // <ex2-58>
                    $arButtons = CIBlock::GetPanelButtons(
                        $arRes["IBLOCK_ID"],
                        $arRes["ID"],
                        0,
                        array("SECTION_BUTTONS" => false, "SESSID" => false)
                    );
                    $arRes["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
                    $arRes["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
                    // </ex2-58>

                    // Добавляем элементы товаров к секциям
                    $arSection["ITEMS"][] = $arRes;
                }
            }
            $iCount++;
        }

        // <ex2-58>
        // Возвращает "true", если кнопка "Показать включаемые области" на панели управления нажата, в противном случае - "false".
        if ($APPLICATION->GetShowIncludeAreas()) {
            // Метод возвращает массив, описывающий набор кнопок для управления элементами инфоблока
            $arButtons = CIBlock::GetPanelButtons(
                $this->arParams["IBLOCK_CATALOG_ID"],
                0
            );
            // Добавляет массив новых кнопок к тем кнопкам компонента, которые отображаются в области компонента в режиме редактирования сайта.
            $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
        }
        // </ex2-58>

        // </Выборка товаров из ИБ "Продукция" по выбранным разделам>

        // <ex2-82>
        // Минимальная цена
        $this->arResult["MIN_PRICE"] = min($arAllPrice);
        // Максимальная цена
        $this->arResult["MAX_PRICE"] = max($arAllPrice);
        // </ex2-82>

        // Максимальный ID элемента товаров
        $this->arResult["MAX_ELEMENT_ID"] = max($arAllId);

        // Количество товаров
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
            // <ex2-58>
            /*
             * Отключаем управление разделами, по заданию
             *
            $arButtons = CIBlock::GetPanelButtons(
                $arRes["IBLOCK_ID"],
                $arRes["ID"],
                0,
                array("SECTION_BUTTONS" => false, "SESSID" => false)
            );
            */
            $arRes["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
            $arRes["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
            // </ex2-58>

            $this->arResult["ITEMS"][$i] = $arRes;
            foreach ($arProductSectionsTotal as &$arSection2) {
                foreach ($arSection2[$this->arParams["USER_PROPERTY"]] as $item) {
                    if ($item == $arRes["ID"]) {
                        $this->arResult["ITEMS"][$i]["ITEMS"][] = $arSection2;
                    }
                }
            }
            $i++;
        }

        // <ex2-58>
        // Возвращает "true", если кнопка "Показать включаемые области" на панели управления нажата, в противном случае - "false".
        /*
         * Отключаем управление разделами, по заданию
         *
        if ($APPLICATION->GetShowIncludeAreas()) {
            // Метод возвращает массив, описывающий набор кнопок для управления элементами инфоблока
            $arButtons = CIBlock::GetPanelButtons(
                $this->arParams["IBLOCK_NEWS_ID"],
                0
            );
            // Добавляет массив новых кнопок к тем кнопкам компонента, которые отображаются в области компонента в режиме редактирования сайта.
            $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
        }
        */
        // </ex2-58>

        // </Выборка элементов из ИБ "Новости">
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\LoaderException
     */
    public function executeComponent()
    {
        if (!Loader::includeModule("iblock")) {
            ShowError(GetMessage("EX2_70_IB_CHECK"));
            return;
        }

        // <ex2-100>
        // Метод возвращает массив, описывающий набор кнопок для управления элементами инфоблока
        $arButtons = CIBlock::GetPanelButtons($this->arParams["IBLOCK_CATALOG_ID"]);

        // Добавляет новую кнопку к тем кнопкам компонента, которые отображаются в области компонента в режиме редактирования сайта
        $this->AddIncludeAreaIcon(
            [
                "TITLE"          => GetMessage("EX2_100_SUBMENU_TITLE"),
                "URL"            => $arButtons['submenu']['element_list']['ACTION_URL'],
                // Показать в контекстном меню
                "IN_PARAMS_MENU" => true,
            ]
        );
        // </ex2-100>

        if ($this->StartResultCache()) {
            $this->setArResult();
            // Список ключей массива $arResult, которые должны кэшироваться при использовании встроенного кэширования компонентов, иначе закеширует весь массив arResult, кэш сильно разростается
            $this->setResultCacheKeys(
                [
                    "COUNT",
                    "MIN_PRICE",
                    "MAX_PRICE",
                ]
            );
            $this->includeComponentTemplate();
        }

        global $APPLICATION;
        $APPLICATION->SetTitle(GetMessage("EX2_70_ELEMENTS_COUNT") . $this->arResult["COUNT"]);

        // ex2-82
        // AddViewContent - позволяет указать место вывода контента, создаваемого ниже по коду с помощью метода ShowViewContent.
        $APPLICATION->AddViewContent(
            "min_price",
            GetMessage("EX2_82_MIN_PRICE") . $this->arResult["MIN_PRICE"]
        );
        $APPLICATION->AddViewContent(
            "max_price",
            GetMessage("EX2_82_MAX_PRICE") . $this->arResult["MAX_PRICE"]
        );
    }
}