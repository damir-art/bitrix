# Работаем с инфоблоками


WorkFlow:
- сначала получем ID или CODE инфоблока, через `IblockTable::getList()`
- за тем по полученному ID или CODE, выбираем элементы инфоблока, свойства, разделы и т.д.

Выберем все новости на сайте. Создаём страницу test.php, в ней размещаем код. Например перейдя в админку новосте у него в URL будет `type=news`, `IBLOCK_ID=1`.

    // Перед использованием модуля инфоблока подключаем его
    \Bitrix\Main\Loader::includeModule('iblock');

`getList([])` возвращает объект, у этого объекта есть следующие методы:
- `$obj->fetch()` - получение текущей записи, можно выполнять итерацию в цикле while,
- `$obj->fetchRaw()`- аналог `$obj->fetch()`,
- `$obj->fetchAll()`- получение всего списка записей,
- `$obj->getCount()` - количество записей, без учета limit (в запросе должно быть так же указано count_total = 1),
- `$obj->getSelectedRowsCount()`- кол-во записей, с учетом limit

Также вам понадобится:  
Пользовательские свойства хранятся в двух местах:
- `Магазин > Каталог > Свойства товаров`,
- `Настройки > Настройки продукта > Пользовательские поля`.

## Выборка инфоблоков:
TypeTable - `b_iblock_type` имя таблицы в БД.

    \Bitrix\Iblock\TypeTable::getList();                // Список типов инфоблоков
    \Bitrix\Iblock\IblockTable::getList();              // Список инфоблоков
    \Bitrix\Iblock\PropertyTable::getList();            // Список свойств инфоблоков
    \Bitrix\Iblock\PropertyEnumerationTable::getList(); // Список значений свойств (множественных), хранимых отдельно
    \Bitrix\Iblock\SectionTable::getList();             // Список разделы инфоблоков
    \Bitrix\Iblock\ElementTable::getList();             // Список элементов инфоблоков
    \Bitrix\Iblock\InheritedPropertyTable::getList();   // Список наследуемых свойств (SEO шаблоны)

- `->fetch()` - получаем первый элемент из выборки (массив), можно использовать в цикле для перебора,
- `->fetchAll()` - получаем все элементы из выборки (массив массивов),

### Все типы инфоблоков
Пример, получаем список всех типов инфоблоков на сайте:

    $iblockTypeList = \Bitrix\Iblock\TypeTable::getList()->fetchAll();

    echo '<pre>';
    print_r($iblockTypeList); // Получаем список всех типов инфоблоков на сайте
    echo '</pre>';

### Получаем инфоблок
Получаем инфоблок по его ID или CODE:

    // Получаем инфоблок по его CODE или ID
    $iblockNews = \Bitrix\Iblock\IblockTable::getList([
      'filter' => ['CODE' => 'news'],
    ])->fetch();

    echo '<pre>';
    print_r($iblockNews['CODE']); // news
    print_r($iblockNews['ID']);   // 1
    echo '</pre>';

### Свойства инфоблока
Выводим свойства полученного инфоблока. Чтобы получить имя поля `IBLOCK_ID` (не его значение), нужно вывести все свойства инфоблока:

    // Получение списка свойств информационного блока
    $iblockNewsProps = \Bitrix\Iblock\PropertyTable::getList([
      'filter' => ['IBLOCK_ID' => $iblockNews['ID']],
    ])->fetch();

    echo '<pre>';
    print_r($iblockNewsProps);
    echo '</pre>';

### Множественное свойство инфоблока
Свойства бывают как у инфоблоков так и у элементов инфоблока.  
Получаем множественные свойства инфоблоков.  
Сначала выводим список всех пользовательских свойств:  
  `\Bitrix\Iblock\PropertyEnumerationTable::getList()->fetchAll();`  
Затем получаем пользовательское свойство по его `PROPERTY_ID`.

    // Получение списка множественных свойств информационного блока
    $dbEnums = \Bitrix\Iblock\PropertyEnumerationTable::getList([
      'order' => array('SORT' => 'asc'),
      'filter' => ['PROPERTY_ID' => $arIblockProp[ID]],
    ]);

    while($arEnum = $dbEnums->fetch()) {
      // В существующем массиве создаём ключ ENUM_LIST и в него добавляем массивы свойств по ID
      $arIblockProp['ENUM_LIST'][$arEnum['ID']] = $arEnum;
    }

    echo '<pre>';
    print_r($arIblockProp);
    echo '</pre>';

## Выборка элементов
ElementTable - `b_iblock_element` имя таблицы в БД.

Получаем все значения полей элемента, чтобы отфильтровать по необходимому:

    $dbItems = \Bitrix\Iblock\ElementTable::getList([])->fetch();

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

## Методы классов инфоблока
Помимо getList можно также воспользоваться следующими методами:
- `getEntity()` - возвращает объект сущности,
- `getTableName()` - метод возвращает имя таблицы БД для сущности. 12.0.7
- `getById($id)` - получение элемента по его ID,
- `getList(array $parameters = array())` - получение элементов,
- `add(array $data)` - добавление элемента в инфоблок,
- `addMulti($rows, $ignoreEvents = false)` - множественное добавление элементов в инфоблок,
- `checkFields(Result $result, $primary, array $data)` - проверка поля перед добавлением в БД,
- `delete($primary)` - удаление элемента по его ID,
- `getByPrimary($primary, array $parameters = array())` - метод возвращает выборку по первичному ключу сущности и по опциональным параметрам \Bitrix\Main\Entity\DataManager::getList,
- `getConnectionName()` - метод возвращает имя соединения для сущности 12.0.9,
- `getCount($filter = array(), array $cache = array())`- метод выполняет COUNT запрос к сущности и возвращает результат 12.0.10,
- `getMap()` - метод возвращает описание карты сущностей 12.0.7,
- `getRow(array $parameters)` - возвращает один столбец (или null) по параметрам для \Bitrix\Main\Entity\DataManager::getList,
- `getRowById($id)` - возвращает один столбец (или null) по первичному ключу сущности 14.0.0,
- `query()` - создаёт и возвращает объект запроса для сущности,
- `update($primary, array $data)` - обновление элемента по ID,
- `updateMulti($primaries, $data, $ignoreEvents = false)`,
- `enableCrypto($field, $table = null, $mode = true)` - метод устанавливает флаг поддержки шифрования для поля 17.5.14,
- `cryptoEnabled($field, $table = null)` - метод возвращает true если шифрование разрешено для поля. 17.5.14

## Символьный код API
https://mrcappuccino.ru/blog/post/iblock-elements-bitrix-d7

## Разное
- https://href.kz/blog/bitrix/api-dlya-raboty-s-infoblokami-v-bitrix-d7 (чек)
