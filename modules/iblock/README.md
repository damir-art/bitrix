# Работаем с инфоблоками
https://dev.1c-bitrix.ru/api_help/iblock/index.php  

Модуль Информационные блоки - мощный и в то же время гибкий механизм для хранения и выборки информации различными способами. API модуля состоит из нескольких высокоуровневых функций для выборки данных в публичном разделе сайта и набора классов с низкоуровневыми методами для более специализированной работы.

Перед использованием модуля необходимо проверить установлен ли он и подключить его при помощи конструкции:

    // Старое API
    if( CModule::IncludeModule("iblock") ) {
      //здесь можно использовать функции и классы модуля
    }

    // D7
    if( \Bitrix\Main\Loader::includeModule('iblock') ) {
      //здесь можно использовать функции и классы модуля
    }

\Bitrix\Main\Loader::includeModule('iblock') - загружает модуль в ваш код.

## Сущности инфоблоков
Как работать с сущностями в коде:

    \Bitrix\Iblock\TypeTable::getList();                // Список типов инфоблоков
    \Bitrix\Iblock\IblockTable::getList();              // Список инфоблоков
    \Bitrix\Iblock\PropertyTable::getList();            // Список свойств инфоблоков
    \Bitrix\Iblock\PropertyEnumerationTable::getList(); // Список значений свойств (множественных), хранимых отдельно
    \Bitrix\Iblock\SectionTable::getList();             // Список разделы инфоблоков
    \Bitrix\Iblock\ElementTable::getList();             // Список элементов инфоблоков
    \Bitrix\Iblock\InheritedPropertyTable::getList();   // Список наследуемых свойств (SEO шаблоны)

Например TypeTable это класс, у этого класса есть таблица `b_iblock_type` в БД.  
Таблицы и их поля работающие с инфоблоками см. в `table.md`, с помощью таблиц можно изучить поля сущностей.

Список всех сущностей и таблицы к которым они относятся (таблицы можно смотреть по адресу `Админка > Настройки > Производительность > Таблицы`):
- `TypeTable` - класс для работы с таблицей типов информационных блоков `b_iblock_type`,
- `TypeLanguageTable` - класс для работы с таблицей языковых параметров типов инфоблоков `b_iblock_type_lang`,
- `IblockTable` - класс для работы с таблицей информационных блоков `b_iblock`,
- `PropertyTable` - класс для работы с таблицей свойств элементов инфоблоков `b_iblock_property`,
- `IblockFieldTable` - класс для работы таблицей, которая содержит поля инфоблоков элементов `b_iblock_fields`,
- `SectionTable` - класс для работы с таблицей содержащий список разделов инфоблока `b_iblock_section`,
- `SectionPropertyTable` - класс для работы с таблицей свойств разделов `b_iblock_section_property`,
- `SectionElementTable` - класс для работы с таблицей которая содержит ID элементов и разделов в которых находится этот элемент `b_iblock_section_element`,
- `ElementTable` - класс для работы с таблицей содержащей список элементов инфоблоков `b_iblock_element`,
- `ElementPropertyTable` - класс для работы с таблицей содержащей список свойств элементов `b_iblock_element_property`
- `PropertyEnumerationTable` - класс для работы с таблицей вариантов значений свойств типа Список,

- `IblockGroupTable` - класс для работы с таблицей прав доступа к инфоблокам,
- `IblockMessageTable` - класс для работы с таблицей подписей и заголовков объектов инфоблоков,
- `IblockSiteTable` - класс для работы с таблицей привязки инфоблоков к сайтам `b_iblock_site`,
- `IblockRssTable` - класс для работы с таблицей привязок полей для выгрузки в RSS,

- `InheritedPropertyTable` - класс для работы с таблицей шаблонов вычисляемых наследуемых свойств,
- `SenderEventHandler` - технический класс для сообщения с модулем Email-маркетинг,
- `SequenceTable` - класс для работы с таблицей счетчиков,

- `Template` - пространство содержит классы для работы с шаблонами SEO свойств,
- `BizprocType` - пространство содержит классы для работы со значениями полей инфоблоков пользовательских типов в бизнес-процессах,
- `Component` - пространство содержит классы поддержки компонентов,
- `Model` - пространство имен содержит классы для механизма единого управления свойствами,
- `Helpers` - пространство содержит вспомогательные классы,
- `PropertyIndex` - пространство содержит классы для работы с индексами инфоблоков,
- `InheritedProperty` - пространство содержит классы для работы с наследуемыми вычисляемыми свойствами.

## Начало работы
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

## Методы инфоблока
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
- https://blog.budagov.ru/bitrix-d7-dlya-infoblokov/ (читать)
