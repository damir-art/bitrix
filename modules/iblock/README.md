# Работаем с инфоблоками
Таблицы и их поля работающие с инфоблоками см в `table.md`.

Перед началом работы с инфоблоками, нужно сначала его подключить:

    \Bitrix\Main\Loader::includeModule('iblock');
    $iblockListObj = \Bitrix\Iblock\IblockTable::getList(); // Получаем объект списка инфоблоков (не принтить)
    $iblockListArr = $iblockListObj->fetchAll(); // Получаем массив списка инфоблоков (можно принтить)

    echo '<pre>';
    print_r($iblockListArr);
    echo '</pre>';

## Выборка через getList()

  $dbItems = \Bitrix\Iblock\ElementTable::getList(array(
    'order'  => array('SORT' => 'ASC'),                           // сортировка
    'select' => array('ID', 'NAME', 'IBLOCK_ID', 'SORT', 'TAGS'), // выбираемые поля, без свойств. Свойства можно получать на старом ядре \CIBlockElement::getProperty
    'filter' => array('IBLOCK_ID' => 4),                          // фильтр только по полям элемента, свойства (PROPERTY) использовать нельзя
    'group' => array('TAGS'), // группировка по полю, order должен быть пустой
    'limit' => 1000,          // целое число, ограничение выбираемого кол-ва
    'offset' => 0,            // целое число, указывающее номер первого столбца в результате
    'count_total' => 1,       // дает возможность получить кол-во элементов через метод getCount()
    'runtime' => array(),     // массив полей сущности, создающихся динамически
    'data_doubling' => false, // разрешает получение нескольких одинаковых записей
    'cache' => array(         // Кеш запроса. Сброс можно сделать методом \Bitrix\Iblock\ElementTable::getEntity()->cleanCache();
      'ttl' => 3600,          // Время жизни кеша
      'cache_joins' => true   // Кешировать ли выборки с JOIN
    ),
  ));

Пример выведем `ID`, `NAME`, `CODE` и `IBLOCK_TYPE_ID` инфоблока, используя два подхода, первый проходимся циклом while по объекту, второй это проходимся циклом foreach по массиву полученном через fetchAll():

    \Bitrix\Main\Loader::includeModule('iblock');
    $iblockListObj = \Bitrix\Iblock\IblockTable::getList([
      'select' => array( 'ID', 'NAME', 'CODE', 'IBLOCK_TYPE_ID' ),
    ]);

    // Работаем в цикле while с объектом (чтобы проверить код ниже, этот нужно закоментить)
    while($iblockListElement = $iblockListObj->fetch()) {
      echo '<pre>';
      print_r($iblockListElement);
      echo '</pre>';
    }

    // Работаем в цикле foreach с массивом
    $iblockListArr = $iblockListObj->fetchAll(); // Получаем массив списка инфоблоков (можно принтить)

    // Выводим список инфоблоков в массиве
    echo '<pre>';
    print_r($iblockListArr);
    echo '</pre>';

    // Используем цикл foreach
    foreach($iblockListArr as $iblockListItem) {
      echo 'ID: ' . $iblockListItem['ID'] . '<br />';
      echo 'NAME: ' . $iblockListItem['NAME'] . '<br />';
      echo 'CODE: ' . $iblockListItem['CODE'] . '<br />';
      echo 'IBLOCK_TYPE_ID: ' . $iblockListItem['IBLOCK_TYPE_ID'];
      echo '<hr />';
    }

`getList([])` возвращает объект, у этого объекта есть следующие методы:
- `$obj->fetch()` - получение одной записи, первой в списке, можно выполнять итерацию в цикле while,
- `$obj->fetchRaw()`- аналог `$obj->fetch()`,
- `$obj->fetchAll()`- получение всего списка записей,
- `$obj->getCount()` - количество записей, без учета limit (в запросе должно быть так же указано count_total = 1),
- `$obj->getSelectedRowsCount()`- кол-во записей, с учетом limit

WorkFlow:
- сначала получем ID или CODE инфоблока, через `IblockTable::getList()`
- затем по полученному ID или CODE, выбираем элементы инфоблока, свойства, разделы и т.д.

Выберем все новости на сайте. Создаём страницу test.php, в ней размещаем код. Проверить ID (IBLOCK_ID) или IBLOCK_TYPE_ID инфоблока можно перейдя в админку новостей, у него в URL будет `type=news`, `IBLOCK_ID=1`.

Также вам может понадобится, пользовательские свойства хранятся в двух местах:
- `Магазин > Каталог > Свойства товаров`,
- `Настройки > Настройки продукта > Пользовательские поля`.

## Сущности инфоблоков
TypeTable - `b_iblock_type` имя таблицы в БД.

    \Bitrix\Iblock\TypeTable::getList();                // Список типов инфоблоков
    \Bitrix\Iblock\IblockTable::getList();              // Список инфоблоков
    \Bitrix\Iblock\PropertyTable::getList();            // Список свойств инфоблоков
    \Bitrix\Iblock\PropertyEnumerationTable::getList(); // Список значений свойств (множественных), хранимых отдельно
    \Bitrix\Iblock\SectionTable::getList();             // Список разделы инфоблоков
    \Bitrix\Iblock\ElementTable::getList();             // Список элементов инфоблоков
    \Bitrix\Iblock\InheritedPropertyTable::getList();   // Список наследуемых свойств (SEO шаблоны)

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

## Разное
- https://href.kz/blog/bitrix/api-dlya-raboty-s-infoblokami-v-bitrix-d7 (чек)
