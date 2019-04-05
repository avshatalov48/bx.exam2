<?php

namespace Exam2\Handlers;

use Bitrix\Main\Localization\Loc;

Loc::LoadMessages(__FILE__);

/**
 * [ex2-95] Упростить меню в адмистративном разделе для контент-менеджера
 *
 * Class ContentMenuEx2Task95
 * @package Exam2\Handlers
 */
class ContentMenuEx2Task95
{
    public static function onBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        // Если пользователь принадлежит "Контент-редакторы" (5) и не принадлежит "Администраторы" (1)
        // тогда, убираем пункты меню
        if (in_array(5, $USER->GetUserGroupArray()) && !in_array(1, $USER->GetUserGroupArray())) {
            // Пункт меню "Рабочий стол"
            unset($aGlobalMenu['global_menu_desktop']);
            // Убираем подпункты, если есть следующие родительские пункты меню
            foreach ($aModuleMenu as $iKey => $sValue) {
                // Можно удалить лишние пункты
                // Либо оставить только нужный
                // + "parent_menu" => "global_menu_content"
                // + "items_id" => "menu_iblock_/news"
                if ($aModuleMenu[$iKey]['parent_menu'] == 'global_menu_settings' // Настройки
                    || $aModuleMenu[$iKey]['parent_menu'] == 'global_menu_services' // Сервисы
                    || $aModuleMenu[$iKey]['parent_menu'] == 'global_menu_marketplace' // Маркетплейс
                    || $aModuleMenu[$iKey]['items_id'] == 'menu_iblock' // Инфоблоки (Импорт, экспорт и т.п.)
                ) {
                    unset($aModuleMenu[$iKey]);
                }
            }
        }
    }
}