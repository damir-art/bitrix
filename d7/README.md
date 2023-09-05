# Битрикс D7
D7 это в первую очередь ядро, в котором есть новый способ написания кода и ORM.  
Имена таблиц инфоблоков и названия полей: https://dev.1c-bitrix.ru/api_help/iblock/fields.php

# Работаем с инфоблоками
- сначала получем ID или CODE инфоблока, через `IblockTable::getList()`
- за тем по полученному ID или CODE, выбираем элементы инфоблока, свойства, разделы и т.д.

Выберем все новости на сайте. Создаём страницу test.php, в ней размещаем код. Например перейдя в админку новосте у него в URL будет `type=news`, `IBLOCK_ID=1`.

    // Перед использованием модуля инфоблока подключаем его
    \Bitrix\Main\Loader::includeModule('iblock');

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

### Множественное свойство
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

Фильтруем по `IBLOCK_ID`:

    $dbItems = \Bitrix\Iblock\ElementTable::getList(array(
      'select' => array('ID', 'NAME', 'IBLOCK_ID'),
      'filter' => array('IBLOCK_ID' => 1)
    ))->fetchAll();

    echo '<pre>';
    print_r($dbItems); // Выводим все элементы инфоблока 1
    echo '</pre>';

Пользовательские свойства хранятся в двух местах:
- `Магазин > Каталог > Свойства товаров`,
- `Настройки > Настройки продукта > Пользовательские поля`.


## Символьный код API
https://mrcappuccino.ru/blog/post/iblock-elements-bitrix-d7

## Разное
- https://href.kz/blog/bitrix/api-dlya-raboty-s-infoblokami-v-bitrix-d7
