<?php

namespace Exam2\Handlers;

use Bitrix\Main\Localization\Loc;

Loc::LoadMessages(__FILE__);

/**
 * [ex2-50] Проверка при деактивации товара
 *
 * Class DeactivationProductEx2Task50
 * @package Exam2\Handlers
 */
class DeactivationProductEx2Task50
{
    public static function onBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == PRODUCT_IBLOCK_ID && $arFields['ACTIVE'] !== 'Y') {
            $resElement = \CIBlockElement::GetByID($arFields['ID']);

            if ($arElement = $resElement->Fetch()) {
                $iShowCounter = $arElement['SHOW_COUNTER'];
            }

            if ($iShowCounter > 2) {
                global $APPLICATION;
                $APPLICATION->throwException(Loc::getMessage('SHOW_COUNTER_ERROR_1') . $iShowCounter . Loc::getMessage('SHOW_COUNTER_ERROR_2'));
                return false;
            }
        }
    }
}