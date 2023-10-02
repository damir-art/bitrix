# Свойства элементов инфоблока
ElementPropertyTable - класс для работы с таблицей содержащей список свойств элементов `b_iblock_element_property`.

https://dev.1c-bitrix.ru/community/webdev/user/654351/blog/36908/ (здесь в примере в select пропущен IBLOCK_ID)

Получаем список свойств инфоблока по его ID:

    $res = \Bitrix\Iblock\ElementTable::getList(array(
        "select" => array("ID", "*"),
        "filter" => array("IBLOCK_ID" => 25, "ID" => 4584),
        "order"  => array("ID" => "ASC")
    ));

    while ($arItem = $res->fetch()) {
        $propRes = \Bitrix\Iblock\ElementPropertyTable::getList(array(
            "select" => array("ID", "*"),
            "filter" => array("IBLOCK_ELEMENT_ID" => $arItem["ID"],),
            "order"  => array("ID" => "ASC")
        ));
        while($prop = $propRes->Fetch())
        {
            $arItem["PROPERTIES"][$prop["IBLOCK_PROPERTY_ID"]] = $prop;
        }
        echo "<pre>".print_r($arItem, true)."</pre>";
    }

## Рабочий код
Добавляем свойства в массив элемента:

    \Bitrix\Main\Loader::includeModule('iblock');

    $itemsObj = \Bitrix\Iblock\ElementTable::getList([
      'filter' => [ 'IBLOCK_ID' => 14 ],
      'limit' => 10,
    ]);


    while( $itemArr = $itemsObj->fetch() ){
      $propsObj = \Bitrix\Iblock\ElementPropertyTable::getList([
        'filter' => [ 'IBLOCK_ELEMENT_ID' => $itemArr['ID'] ],
      ]);

      while($prop = $propsObj->fetch()) {
        $itemArr["PROPERTIES"][$prop["IBLOCK_PROPERTY_ID"]] = $prop;
      }

      echo '<pre>';
      print_r($itemArr);
      echo '</pre>';
    }

## Рабочий код с именами свойств и текущими значениями

    // Получаем элемнты инфоблока 14, лимит элементов 10
    $res = \Bitrix\Iblock\ElementTable::getList(array(
      "filter" => array("IBLOCK_ID" => 14),
      'limit' => 10,
    ));

    while ($arItem = $res->fetch()) {
      $dbProperty = \CIBlockElement::getProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID'],
        array("sort", "asc"),
        array()
      );

      while ($arProperty = $dbProperty->GetNext()) {
        $arItem["PROPERTIES"][$arProperty['CODE']] = $arProperty;
      }

      echo "<pre>";
      print_r($arItem);
      echo "</pre>";
    }

## Получаем массив ID элементов у которых значение свойства список равно Оптимум

    \Bitrix\Main\Loader::includeModule('iblock');

    // Получаем элемнты инфоблока 14, лимит элементов 10
    $res = \Bitrix\Iblock\ElementTable::getList(array(
      "filter" => ["IBLOCK_ID" => 14],
      //'limit' => 10,
    ));

    $goods = [];

    while ($arItem = $res->fetch()) {
      $dbProperty = \CIBlockElement::getProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID'],
        array("sort", "asc"),
        array()
      );

      while ($arProperty = $dbProperty->GetNext()) {
        if ($arProperty['CODE'] === 'PACKAGE_OFFER') {
          if($arProperty['VALUE_ENUM'] === 'Оптимум') {
            $arItem['PROPERTIES'][$arProperty['CODE']] = $arProperty;
            $goods[] = $arItem['ID'];
          }
        }
      }
    }

    echo "<pre>";
    print_r($goods);
    echo "</pre>";
