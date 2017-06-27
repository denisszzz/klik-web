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

