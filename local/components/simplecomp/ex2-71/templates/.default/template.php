<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<p>
    <? $sCurPage = $APPLICATION->GetCurPage() . "?F=Y"; ?>
    <?= GetMessage("EX2_49_FILTER") ?><a href="<?= $sCurPage ?>"><?= $sCurPage ?></a>
    <br>
    ---
</p>

<p>
    <?= GetMessage("EX2_107_TIME_STAMP") ?><? echo time(); ?>
</p>

<p>
    <b><?= GetMessage("EX2_71_CATALOG_TITLE") ?></b>
</p>

<ul>
    <? foreach ($arResult["CLASS"] as $arClass): ?>
        <li><b><?= $arClass["NAME"] ?></b></li>
        <ul>
            <? foreach ($arClass["ELEMENTS_ID"] as $iID): ?>
                <li>
                    <? $arEl = $arResult["ELEMENTS"][$iID]; ?>
                    <a href="<?= $arEl["DETAIL_PAGE_URL"] ?>">
                        <?= $arEl["NAME"] ?>
                        - <?= $arEl["PROPS"]["PRICE"]["VALUE"] ?>
                        - <?= $arEl["PROPS"]["MATERIAL"]["VALUE"] ?>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    <? endforeach; ?>
</ul>

<br>---
<p><b><?= GetMessage("EX2_60_NAVIGATION") ?></b></p>
<? echo $arResult["NAV_STRING"] ?>