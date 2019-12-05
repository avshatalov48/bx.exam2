<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class Ex71 extends \CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {

        return parent::onPrepareComponentParams($arParams);
    }

    protected function checkModules()
    {
        if (!Loader::includeModule("iblock")) {
            ShowError(GetMessage("EX2_71_IB_CHECK"));
            return false;
        }

        return true;
    }

    protected function prepareData()
    {
    }

    public function executeComponent()
    {
        if (!$this->checkModules()) {
            return;
        }
    }
}