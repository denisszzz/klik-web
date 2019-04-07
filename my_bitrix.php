<h2>Битрикс</h2>
<p>Сортировка товара по "Популярности", "Цене", "Названию";
<pre>
<?
if (isset($_REQUEST['orderBy'])) {
    if ($_REQUEST['orderBy'] == 'asc') {
        $orderBy = 'desc';
    } else {
        $orderBy = 'asc';
    }
} else {
    $orderBy = 'asc';
}
?>
<?
if (isset($_REQUEST['sortBy'])) {
    $sortBy = $_REQUEST['sortBy'];
} else {
    $sortBy = 'sort';
}
if ($sortBy=='property_ATT_PRICE') {
    $sortBy = 'price';
}
if ($sortBy=='show_counter') {
    $sortBy = 'show_counter';
}
if (strpos($orderBy, 'asc') !== false) {
   $line_sort ='&darr;';
}
elseif (strpos($orderBy, 'desc') !== false) {
   $line_sort = '&uarr;';
}
?>
<span class="name-sort">Сортировать по</span>
	<a rel="nofollow" class="sort-item<?if($sortBy == 'show_counter'):?>sort-item-active <?endif;?>" href="<?= $APPLICATION->GetCurPageParam('sortBy=show_counter&orderBy='.$orderBy, array('sortBy', 'orderBy')) ?>">Популярности</a>
    <a rel="nofollow" class="sort-item<?if($sortBy == 'price'):?>sort-item-active<?endif;?>" href="<?= $APPLICATION->GetCurPageParam('sortBy=property_ATT_PRICE&orderBy='.$orderBy, array('sortBy', 'orderBy')) ?>">Цене</a>
    <a rel="nofollow" class="sort-item<?if($sortBy == 'name'):?>-active <?endif;?>" href="<?= $APPLICATION->GetCurPageParam('sortBy=name&orderBy='.$orderBy, array('sortBy', 'orderBy')) ?>">Названию</a>
</pre>
</p>

ПРИЧЕМ, В 
"ELEMENT_SORT_FIELD" => $sortBy,
"ELEMENT_SORT_ORDER" => $orderBy,

<p>Колличество отображаемых товаров:
<pre>
<?php
$pageElementCount = $arParams["PAGE_ELEMENT_COUNT"];
if (array_key_exists("showBy", $_REQUEST)) {
    if ( intVal($_REQUEST["showBy"]) && in_array(intVal($_REQUEST["showBy"]), array(15, 30, 45)) ) {
        $pageElementCount = intVal($_REQUEST["showBy"]); 
        $_SESSION["showBy"] = $pageElementCount;
    } elseif ($_SESSION["showBy"]) {
        $pageElementCount = intVal($_SESSION["showBy"]);
    }
}
?>
 <?$APPLICATION->IncludeComponent("bitrix:main.pagenavigation", "pagenav", Array(
	"COMPONENT_TEMPLATE" => "grid"
	),
	false
);?>
<div class="show_number">
    <span class="name-sort">Показать по</span>
    <span class="number_list">
        <? for( $i = 15; $i <= 45; $i+=15 ) : ?>
            <a rel="nofollow" class="sort-item<?if($i == $pageElementCount):?>-active<?endif;?>" href="<?= $APPLICATION->GetCurPageParam('showBy='.$i, array('showBy', 'mode')) ?>"><?= $i ?></a>
        <? endfor; ?>
    </span>
</div>
</pre>
</p>


<p>Получает значение Доп. полей раздела.</p>
<?
$aSection = CIBlockSection::GetList( array(), array(
'IBLOCK_ID'         => id инфоблока,
'ID//CODE'          => тут id/code категории,
), false, array('UF_KARNIZ_MP', 'UF_PRICE_MIN', 'UF_THREE_STEP') )->Fetch();
echo $aSection["UF_KARNIZ_MP"];
?>

<p>Получает значение Доп. полей раздела. типа список.</p>
<?
Cmodule::IncludeModule('iblock');
$rsEnum = CUserFieldEnum::GetList(array(), array("ID" =>$ID));//$ID - id значения пользовательского поля типа список
$arEnum = $rsEnum->GetNext();
echo $arEnum["XML_ID"];
?>


<p>Получает список разделов, к которым принадлежит элемент</p>
<?
$resSection = CIBlockSection::GetNavChain(false, $arResult['IBLOCK_SECTION_ID']);
while ($arSection = $resSection->GetNext()) {
$array_sections = $arSection;
echo $arSection["NAME"];
}
?>

<p>Добавляет на страницы пагинации title старница N. Этот код добавить в init.php</p>
<?
AddEventHandler("main", "OnEpilog", "OnEpilogHandler");
function OnEpilogHandler()
{
    global $APPLICATION;
    if (!defined('ERROR_404') && intval($_GET["PAGEN_2"]) > 0) {
        $APPLICATION->SetPageProperty("title", $APPLICATION->GetPageProperty("title") . " страница " . intval($_GET["PAGEN_2"]) );
    }
}
?>

<p>Вывод свойства с "поле для описания значения"</p>
<?
<?if (!empty($arItem["PROPERTIES"]["ELEM_PL"]["VALUE"])):?>

<?foreach(array_combine($arResult["DISPLAY_PROPERTIES"]["BLOCK_SOLUTIONS"]["VALUE"], $arResult["DISPLAY_PROPERTIES"]["BLOCK_SOLUTIONS"]["VALUE_XML_ID"]) as $text => $icon) {?>
<li><p class="icon_portfolio_full <?=$icon?>"></p><p class="text_portfolio_full"><?=$text?></p></li>
<?}?>

<?endif?>
?>

<p>Класс к пункту меню</p>
<?
Array(
		"Акции", 
		"/sale/", 
		Array(), 
		Array("CLASS"=>"icon sale_icon"), 
		"" 
	),
?>

<p>Перебор массива кромен некоторых</p>
<?
if($arResult["DISPLAY_PROPERTIES"]){
                foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
                    if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))){
                        if(!is_array($arProp["DISPLAY_VALUE"])){
                            $arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
                        }
                        if(is_array($arProp["DISPLAY_VALUE"])){
                            foreach($arProp["DISPLAY_VALUE"] as $value){
                                if(strlen($value)){
                                    $showProps = true;
                                    break 2;
                                }
                            }
                        }
                    }
                }

?>
