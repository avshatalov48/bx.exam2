<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
IncludeTemplateLangFile(__FILE__);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/common.css")?>
<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css")?>
<?$APPLICATION->ShowHead();?>
<meta property= "specialcount" content="<?$APPLICATION->ShowProperty("specialcount")?>">, 
	<title><?$APPLICATION->ShowTitle()?></title>
</head>
<body>
	<div id="page-wrapper">
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<div id="header">
			
			<table id="logo">
				<tr>
					<td><a href="<?=SITE_DIR?>" title="<?=GetMessage('CFT_MAIN')?>"></a></td>
				</tr>
			</table>
			
			<div id="top-menu">
				<div id="top-menu-inner">
<?$APPLICATION->IncludeComponent("bitrix:menu", "horizontal_multilevel", array(
	"ROOT_MENU_TYPE" => "top",
	"MAX_LEVEL" => "2",
	"CHILD_MENU_TYPE" => "left",
	"USE_EXT" => "Y",
	"MENU_CACHE_TYPE" => "A",
	"MENU_CACHE_TIME" => "36000000",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => ""
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
				</div>
			</div>
			
			<div id="top-icons">
				<a href="<?=SITE_DIR?>" class="home-icon" title="<?=GetMessage('CFT_MAIN')?>"></a>
				<a href="<?=SITE_DIR?>search/" class="search-icon" title="<?=GetMessage('CFT_SEARCH')?>"></a>
				<a href="<?=SITE_DIR?>contacts/" class="feedback-icon" title="<?=GetMessage('CFT_FEEDBACK')?>"></a>
			</div>
		
		</div>
		
		<div id="banner">		
			<table id="banner-layout" cellspacing="0">
				<tr>
					<td id="banner-image"><div><img src="<?=SITE_TEMPLATE_PATH?>/images/head.jpg" /></div></td>
                    <td id="banner-slogan">
                        <?
                        $APPLICATION->IncludeFile(
                            SITE_DIR."include/motto.php",
                            Array(),
                            Array("MODE"=>"html")
                        );
                        ?>
                    </td>
				</tr>
			</table>
			<div id="banner-overlay"></div>	
		</div>
		
		<div id="content">
		
			<div id="sidebar">						
			<div class="content-block">
				<div class="content-block-inner">
					<h3><?=GetMessage('CFT_LANG_CANGE')?></h3>
                    <? $APPLICATION->IncludeComponent("bitrix:main.site.selector", "dropdown_ex2", [
                        "CACHE_TIME"         => "3600",    // Время кеширования (сек.)
                        "CACHE_TYPE"         => "A",    // Тип кеширования
                        "SITE_LIST"          => [    // Список сайтов
                            0 => "s1",
                            1 => "s2",
                        ],
                        "COMPONENT_TEMPLATE" => "dropdown",
                    ],
                        false
                    ); ?>
				</div>
			</div>
			
	
			<div class="content-block">
				<div class="content-block-inner">
					<?
					$APPLICATION->IncludeComponent("bitrix:search.form", "flat", Array(
						"PAGE" => "#SITE_DIR#search/",
					),
						false
					);
					?>
				</div>
			</div>
				
							
			</div>
		
			<div id="workarea">
				<h1 id="pagetitle"><?$APPLICATION->ShowTitle(false);?></h1>