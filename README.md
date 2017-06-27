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

<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>

<h2>Перед продакшеном</h2>
<p>Чек-лист по верстке: https://habrahabr.ru/post/319664/</p>

<h2>Битрикс</h2>
<p>Чистый шаблон на bitrix: https://github.com/denisszzz/klik-web/ папка clear_templ</p>
<p>Вывод элементов из инфоблока, через API bitrix:<br>
<pre>
<?
if (CModule::IncludeModule("iblock")):
// ID инфоблока из которого выводим элементы
$iblock_id = 11;
$my_slider = CIBlockElement::GetList (
// Сортировка элементов
Array("ID" => "ASC"),
Array("IBLOCK_ID" => $iblock_id),
false,
false,
// Перечисляесм все свойства элементов, которые планируем выводить
Array(
'ID', 
'NAME', 
'PREVIEW_PICTURE', 
'PREVIEW_TEXT', 
'PROPERTY_LIN_PR'
)
);
while($ar_fields = $my_slider->GetNext())
{
//Выводим элемент со всеми свойствами + верстка
$img_path = CFile::GetPath($ar_fields["PREVIEW_PICTURE"]);
echo '<li><a href="'.$ar_fields['PROPERTY_LIN_PR_VALUE'].'">';
echo '<h4>'.$ar_fields['NAME']."</h4>";
echo "<img src='".$img_path."'/>";
echo "<p>".$ar_fields['PREVIEW_TEXT']."'</p>";
echo '</a></li>';
}
endif;
?>
</pre>
</p>
<p></p>
<p></p>
<p></p>
<p></p>
