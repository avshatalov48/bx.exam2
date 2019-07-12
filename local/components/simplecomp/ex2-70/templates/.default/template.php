<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<?
// ex2-58
// Костылик
// ID, у элемента в DOM-дереве должен быть уникальным, но они дублируются в разных разделах,
// поэтому у некоторых редактирование не работает. Получаем максимальный ID, далее формируем уникальные по порядку,
// чтобы не пересекались.
$iElementId = $arResult["MAX_ELEMENT_ID"];
?>

<b>
    <?= GetMessage("EX2_70_CATALOG") ?>
</b>
<ul>
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <? // ex2-58
        /* * Отключаем управление разделами, по заданию

        $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem["ID"], $arItem["EDIT_LINK"],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
            array("CONFIRM" => GetMessage("EX2_58_ELEMENT_DELETE_CONFIRM")));

        <div id="<?= $this->GetEditAreaId($arItem["ID"]); ?>">
        */
        ?>

        <div>
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
                            <? // ex2-58
                            $iElementId++;
                            $this->AddEditAction($iElementId, $arElement["EDIT_LINK"],
                                CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($iElementId, $arElement["EDIT_LINK"],
                                CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"),
                                array("CONFIRM" => GetMessage("EX2_58_ELEMENT_DELETE_CONFIRM")));
                            ?>

                            <div id="<?= $this->GetEditAreaId($iElementId); ?>"><!-- End ex2-58 -->
                                <li>
                                    <?= $arElement["NAME"] ?> -
                                    <?= $arElement["PROPERTY_PRICE_VALUE"] ?> -
                                    <?= $arElement["PROPERTY_MATERIAL_VALUE"] ?> -
                                    <?= $arElement["PROPERTY_ARTNUMBER_VALUE"] ?>
                                </li>
                            </div>
                        <? endforeach; ?>
                    <? endforeach; ?>
                </ul>
            </li>
        </div>
    <? endforeach; ?>
</ul>