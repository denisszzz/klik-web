<h2>Штуки</h2>
<p>http://fancyapps.com/ и http://fontawesome.io/icons/</p>
<p>Генератор иконок для сайта: http://perfecticons.com/ </p>
<p>Генератор grid-сетки: http://www.griddy.io/</p>
<p>Мобильное меню для сайта: http://slicknav.com/</p>
<p>Кастомизация google карт: https://snazzymaps.com/editor и https://mapkit.io/</p>
<p>Генератор css-анимаций: http://waitanimate.wstone.io/</p>
<p>Сортировка, фильтры и поиск в html документе: http://listjs.com/</p>
<p>Табы: https://gopalraju.github.io/gridtab/</p>
<p>Слайдер: http://kenwheeler.github.io/slick/</p>
<p>Валидация: https://jqueryvalidation.org/</p>
<p>Генератор градиентов: http://colinkeany.com/blend/</p>
<p>overlay меню: https://tympanus.net/codrops/2014/02/06/fullscreen-overlay-effects/</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>

<h2>Чтение, учеба</h2>
<p>Пополняемые переводы, тексты и тд для всех: https://github.com/melnik909/frontend-whitelist</p>
<p>Изучение регулярных выражний: https://regexone.com/</p>

<h2>Перед продакшеном</h2>
<p>Чек-лист по верстке: https://habrahabr.ru/post/319664/</p>

<h2>Битрикс</h2>
<p>Чистый шаблон на bitrix: https://github.com/denisszzz/klik-web/ папка clear_templ</p>
<p>Вывод элементов из инфоблока, через API bitrix: https://camouf.ru/blog-note/1487/</p>
<p>Основные функции в шаблонах: https://camouf.ru/blog-note/488/</p>
<p>Очистка корзины одной кнопкой: https://camouf.ru/blog-note/1412/</p>
<p>Вывод товаров из того-же раздела: https://camouf.ru/blog-note/1045/</p>
<p>Пометка лейблом товаров: https://camouf.ru/blog-note/865/</p>
<p>Resize картинок: https://camouf.ru/blog-note/833/</p>
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
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
