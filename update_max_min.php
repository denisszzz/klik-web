Скрипт обновляет все элементы инфоблока. Для запуска добавить к адресу get-параметр: ?catalog_id=01, где 01 - id инфоблока. Нужно для того, чтобы после запуска обновились "MINIMUM_PRICE" и "MAXIMUM_PRICE" 
<?
define("NOT_CHECK_PERMISSIONS",true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$catalog_id=(int)$_GET["catalog_id"];
if($catalog_id){
	\Bitrix\Main\Loader::includeModule('iblock');
	$rsItems=CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$catalog_id, "ACTIVE"=>"Y"), false, false, array("ID", "ACTIVE"));
	$el = new CIBlockElement;	
	while($arItem=$rsItems->Fetch()){
		$res = $el->Update($arItem["ID"], array("ACTIVE"=>$arItem["ACTIVE"]));
	}
}else{
	echo "Select catalog";
}
// require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");
?>

Ниже скрипт, который добавляет в свойства "MINIMUM_PRICE" и "MAXIMUM_PRICE" - соответствующие значения. Нужно для правильной сортировки.
<?
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "DoIBlockAfterSave");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "DoIBlockAfterSave");
AddEventHandler("catalog", "OnPriceAdd", "DoIBlockAfterSave");
AddEventHandler("catalog", "OnPriceUpdate", "DoIBlockAfterSave");
function DoIBlockAfterSave($arg1, $arg2 = false){
		$ELEMENT_ID = false;
		$IBLOCK_ID = false;
		$OFFERS_IBLOCK_ID = false;
		$OFFERS_PROPERTY_ID = false;
		if (CModule::IncludeModule('currency'))
			$strDefaultCurrency = CCurrency::GetBaseCurrency();

		//Check for catalog event
		if(is_array($arg2) && $arg2["PRODUCT_ID"] > 0){
			//Get iblock element
			$rsPriceElement = CIBlockElement::GetList(
				array(),
				array(
					"ID" => $arg2["PRODUCT_ID"],
				),
				false,
				false,
				array("ID", "IBLOCK_ID")
			);
			if($arPriceElement = $rsPriceElement->Fetch()){
				$arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
				if(is_array($arCatalog)){
					//Check if it is offers iblock
					if($arCatalog["OFFERS"] == "Y"){
						//Find product element
						$rsElement = CIBlockElement::GetProperty(
							$arPriceElement["IBLOCK_ID"],
							$arPriceElement["ID"],
							"sort",
							"asc",
							array("ID" => $arCatalog["SKU_PROPERTY_ID"])
						);
						$arElement = $rsElement->Fetch();
						if($arElement && $arElement["VALUE"] > 0)
						{
							$ELEMENT_ID = $arElement["VALUE"];
							$IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
							$OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
							$OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
						}
					}
					//or iblock which has offers
					elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0){
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
						$OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
					}
					//or it's regular catalog
					else{
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = false;
						$OFFERS_PROPERTY_ID = false;
					}
				}
			}
		}
		//Check for iblock event
		elseif(is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0){
			//Check if iblock has offers
			$arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
			if(is_array($arOffers)){
				$ELEMENT_ID = $arg1["ID"];
				$IBLOCK_ID = $arg1["IBLOCK_ID"];
				$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
				$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
			}
		}

		if($ELEMENT_ID){
			static $arPropCache = array();
			static $arPropArray=array();

			if(!array_key_exists($IBLOCK_ID, $arPropCache)){
				//Check for MINIMAL_PRICE property
				$rsProperty = CIBlockProperty::GetByID("MINIMUM_PRICE", $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty){
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
					$arPropArray["MINIMUM_PRICE"]=$arProperty["ID"];
				}
				else
					$arPropCache[$IBLOCK_ID] = false;

				$rsProperty = CIBlockProperty::GetByID("IN_STOCK", $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty){
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
					$arPropArray["IN_STOCK"]=$arProperty["ID"];
				}else{
					if(!$arPropCache[$IBLOCK_ID])
						$arPropCache[$IBLOCK_ID] = false;
				}
			}
			// AddMessage2Log($arPropArray, "$arg1");

			if($arPropCache[$IBLOCK_ID]){
				//Compose elements filter
				if($OFFERS_IBLOCK_ID){
					$rsOffers = CIBlockElement::GetList(
						array(),
						array(
							"IBLOCK_ID" => $OFFERS_IBLOCK_ID,
							"PROPERTY_".$OFFERS_PROPERTY_ID => $ELEMENT_ID,
							"ACTIVE" => "Y"
						),
						false,
						false,
						array("ID")
					);
					while($arOffer = $rsOffers->Fetch())
						$arProductID[] = $arOffer["ID"];

					if (!is_array($arProductID))
						$arProductID = array($ELEMENT_ID);
				}
				else
					$arProductID = array($ELEMENT_ID);

				if($arPropArray["MINIMUM_PRICE"]){
					$minPrice = false;
					$maxPrice = false;
					//Get prices
					$rsPrices = CPrice::GetList(
						array(),
						array(
							"PRODUCT_ID" => $arProductID,
						)
					);
					while($arPrice = $rsPrices->Fetch()){
						if (CModule::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
							$arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

						$PRICE = $arPrice["PRICE"];

						if($minPrice === false || $minPrice > $PRICE)
							$minPrice = $PRICE;

						if($maxPrice === false || $maxPrice < $PRICE)
							$maxPrice = $PRICE;
					}

					//Save found minimal price into property
					if($minPrice !== false){
						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"MINIMUM_PRICE" => $minPrice,
								"MAXIMUM_PRICE" => $maxPrice,
							)
						);
					}
				}
				if($arPropArray["IN_STOCK"]){
					$quantity=0;
					$rsQuantity = CCatalogProduct::GetList(
				        array("QUANTITY" => "DESC"),
				        array("ID" => $arProductID),
				        false,
				        false,
				        array("QUANTITY")
				    );
					while($arQuantity = $rsQuantity->Fetch()){
						$quantity+=$arQuantity["QUANTITY"];
					}
					if($quantity>0){
						$rsPropStock = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>"IN_STOCK"));
						if($arPropStock=$rsPropStock->Fetch()){
							$idProp=$arPropStock["ID"];
						}

						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => $idProp,
							)
						);
					}else{
						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => "",
							)
						);
					}
					if(class_exists('\Bitrix\Iblock\PropertyIndex\Manager')){
						\Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($IBLOCK_ID, $ELEMENT_ID);
					}
				}
			}
		}
	}

}
?>

Вот этот еще. Обновление min и max цен
<?
AddEventHandler("iblock", "OnAfterIBlockElementUpdate",  Array("MyElement", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("MyElement", "OnAfterIBlockElementAddHandler"));
class MyElement
{
function OnBeforeIBlockElementUpdateHandler(&$arFields){
$intIBlockID = $arFields["IBLOCK_ID"];
$mxResult = CCatalogSKU::GetInfoByProductIBlock($intIBlockID);
AddMessage2Log('$dump12 = '.print_r($mxResult, true),'');
if (is_array($mxResult)) 
{ 

$rsOffers = CIBlockElement::GetList(array("PRICE"=>"ASC"),array('ACTIVE' => 'Y', 'IBLOCK_ID' => $mxResult['IBLOCK_ID'], 'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $arFields['ID']));
while ($arOffer = $rsOffers->GetNext()) 
{
AddMessage2Log('$dump121 = '.print_r($arOffer, true),'');
$ar_price = GetCatalogProductPrice($arOffer["ID"], 1); 
CIBlockElement::SetPropertyValuesEx($arFields['ID'], $intIBlockID, array('MINIMUM_PRICE' => $ar_price["PRICE"]));
break; 
} 
}
}

function OnAfterIBlockElementAddHandler(&$arFields){
$intIBlockID = $arFields["IBLOCK_ID"]; 
$mxResult = CCatalogSKU::GetInfoByProductIBlock( 
$intIBlockID 
); 
if (is_array($mxResult)) 
{

$rsOffers = CIBlockElement::GetList(array("PRICE"=>"ASC"),array('ACTIVE' => 'Y', 'IBLOCK_ID' => $mxResult['IBLOCK_ID'], 'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $arFields['ID']));
while ($arOffer = $rsOffers->GetNext()) 
{ 
$ar_price = GetCatalogProductPrice($arOffer["ID"], 1); 
CIBlockElement::SetPropertyValuesEx($arFields['ID'], $intIBlockID, array('MINIMUM_PRICE' => $ar_price["PRICE"]));
break; 
} 
}
}
 
}
?>
