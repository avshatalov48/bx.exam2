<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
// Подключение ядра Javascript-библиотеки Bitrix Framework и расширения Ajax
// Иначе у не авторизованного пользователя ничего не заработает
CJSCore::Init(array("ajax"));
?>

<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div class="news-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?endif;?>
    <? if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]): ?>
        <h3><?= $arResult["NAME"] ?>
            <? // <ex2-104> ?>
            <span style="font-style: italic;">
            <? if ($arParams['REPORT_AJAX'] == 'Y'): ?>
                <? //<Работа в режиме Ajax> ?>
                <a id="ajax-report" href="#" onclick="return false;">Пожаловаться!</a>
                <script>
                    BX.ready(function () {
                        var ajaxReportBtn = document.getElementById('ajax-report');
                        var textElem = document.getElementById('ajax-report-text');
                        ajaxReportBtn.onclick = function () {
                            // Функция загружает json-объект из заданного url и передает его обработчику callback
                            BX.ajax.loadJSON(
                                '<?=$APPLICATION->GetCurPage()?>',
                                {'TYPE': 'REPORT_AJAX', 'ID': <?=$arResult['ID']?>},
                                function (data) {
                                    textElem.innerText = "Ваше мнение учтено, №" + data['ID'];
                                },
                                function (data) {
                                    // Обработчик ошибочной ситуации
                                    textElem.innerText = "Ошибка!";
                                }
                            );
                        };
                    });
                </script>
            <? else: ?>
                <? //<Работа в режиме GET> ?>
				<a href="<?= $APPLICATION->GetCurPage() ?>?TYPE=REPORT_GET&ID=<?= $arResult['ID'] ?>">Пожаловаться!</a>
            <? endif; ?>
                <span id="ajax-report-text"></span>
			</span>
            <? // </ex2-104> ?>
        </h3>
    <? endif; ?>
	<div class="news-detail">
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
 	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
 	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
	<?endforeach;?>
	<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;?>
	</div>
</div>
