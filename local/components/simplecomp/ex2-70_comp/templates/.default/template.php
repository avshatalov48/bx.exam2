<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<p>
    <b><?= GetMessage("EX2_70_CATALOG") ?></b>
</p>

<ul>
    <? foreach ($arResult['NEWS'] as $arNews): ?>
        <li>
            <b><?= $arNews['NAME'] ?></b> - <?= $arNews['ACTIVE_FROM'] ?> (<?= implode(', ', $arNews['SECTIONS']) ?>)
            <ul>
                <? foreach ($arNews['PRODUCTS'] as $arProduct): ?>
                    <li>
                        <?= $arProduct['NAME'] ?> - <?= $arProduct['PROPERTY_PRICE_VALUE'] ?>
                        - <?= $arProduct['PROPERTY_MATERIAL_VALUE'] ?> - <?= $arProduct['PROPERTY_ARTNUMBER_VALUE'] ?>
                    </li>
                <? endforeach; ?>
            </ul>
        </li>
    <? endforeach; ?>
</ul>
