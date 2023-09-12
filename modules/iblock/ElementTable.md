# Элементы инфоблока
ElementTable - `b_iblock_element` имя таблицы в БД.

Получаем все значения полей элемента, чтобы отфильтровать по необходимому:

    $items = \Bitrix\Iblock\ElementTable::getList([])->fetch();

    echo '<pre>';
    print_r($dbItems); // Выводим все элементы инфоблока 1
    echo '</pre>';


Получаем все элементы инфоблока, фильтруем по `IBLOCK_ID`:

    $dbItems = \Bitrix\Iblock\ElementTable::getList(array(
      'select' => array('ID', 'NAME', 'IBLOCK_ID'),
      'filter' => array('IBLOCK_ID' => 1)
    ))->fetchAll();

    echo '<pre>';
    print_r($dbItems); // Выводим все элементы инфоблока 1
    echo '</pre>';

### Получаем свойства элементов инфоблока
Получаем все элементы инфоблока со свойствами:

    // Выборка элементов инфоблока
    $dbItems = \Bitrix\Iblock\ElementTable::getList(array(
      'select' => array('ID', 'NAME', 'IBLOCK_ID'),
      'filter' => array('IBLOCK_ID' => 1)
    ));

    while ($arItem = $dbItems->fetch()){
      // Собираем свойства элемента
      $dbProperty = \CIBlockElement::getProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID']
      );
      // Добавляем эти свойства по ключю PROPERTIES
      while($arProperty = $dbProperty->Fetch()) {
        $arItem['PROPERTIES'][] = $arProperty; // Наверно можно обойтись и без []
      }
      echo '<pre>';
      print_r($arItem); // Выводим все элементы инфоблока 1
      echo '</pre>';
    }

Получаем элемент со свойствами:  
https://dev.1c-bitrix.ru/community/webdev/user/654351/blog/36908/ (здесь в примере в select пропущен IBLOCK_ID):  

    $res = \Bitrix\Iblock\ElementTable::getList(array(
      "select" => array("ID", "NAME", 'IBLOCK_ID'),
      "filter" => array("IBLOCK_ID" => 1),
      "order"  => array("ID" => "ASC")
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

Вместо `CIBlockElement` используем `ElementPropertyTable` с версии 19:

    $res = \Bitrix\Iblock\ElementTable::getList(array(
      "select" => array("ID", "*"),
      "filter" => array("IBLOCK_ID" => 1, "ID" => 1),
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

Не выводятся NAME, CODE и т.д. их можно получить через \Bitrix\Iblock\PropertyTable
