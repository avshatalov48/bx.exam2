<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<b>
    <?= GetMessage("EX2_70_CATALOG") ?>
</b>
<ul>
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <li>
            <b><?= $arItem["NAME"] ?></b> - <?= $arItem["DATE_ACTIVE_FROM"] ?>
            <!-- Вывод секций -->
            <? if (!empty($arItem["ITEMS"])): ?>
                (
                <? foreach ($arItem["ITEMS"] as $iKey => $arSection): ?>
                    <? $sComma = ""; ?>
                    <? if ($iKey != array_pop(array_keys($arItem["ITEMS"]))): ?>
                        <? $sComma = ","; ?>
                    <? endif; ?>
                    <?= $arSection["NAME"] . $sComma ?>
                <? endforeach; ?>
                )
            <? endif; ?>
            <!-- Вывод товаров -->
            <ul>
                <? foreach ($arItem["ITEMS"] as $iKey => $arSection): ?>
                    <? foreach ($arSection["ITEMS"] as $arElement): ?>
                        <li>
                            <?= $arElement["NAME"] ?> -
                            <?= $arElement["PROPERTY_PRICE_VALUE"] ?> -
                            <?= $arElement["PROPERTY_MATERIAL_VALUE"] ?> -
                            <?= $arElement["PROPERTY_ARTNUMBER_VALUE"] ?>
                        </li>
                    <? endforeach; ?>
                <? endforeach; ?>
            </ul>
        </li>
    <? endforeach; ?>
</ul>