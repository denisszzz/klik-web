<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!doctype html>
<html>
<head>
	<?$APPLICATION->ShowHead()?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<link href='http://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
	<?
		CJSCore::Init(array("jquery"));
		// Пример подключения js $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.sudoSlider.min.js" );
	?>
	<? 
	//Пример подключения css $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/type.css");
	?>
	<link rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH?>/images/favicon.ico" />
</head>
<body>
<?$APPLICATION->ShowPanel();?>
