# .parameters.php
- Назначение и логика работы,
- Массив PARAMETERS,
- Наименование, группы и типы полей,

Выводит параметры настроек компонента.  
Может отсутствовать, если логикой компонента не предусматрены входные параметры.  
Данный файл может также присутствовать и у шаблона компонента.  
Если файл у комопнента отсутствует, то параметры можно передаватьв коде вызова компонента.  
Параметры передаются в component.php.  
В файл .parameters.php `комплексного` комопонента входят параметры простых компонентов, которые входят в состав комплексного. Также устанавливаются настройки ЧПУ, постраничной навигации, страницы 404.  

Рассмотрим код файла .parameters.php, комплексного компонента `news`:

    // Получаем весь список инфоблоков который есть в системе
    $arIBlockType = CIBlockParameters::GetIBlockTypes();

    // Получаем список инфоблоков заданного типа
    $arIBlock = [];
    $iblockFilter = [
      'ACTIVE' => 'Y',
    ];
    if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
      $iblockFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
    }
    $rsIBlock = CIBlock::GetList(["SORT" => "ASC"], $iblockFilter);
    while($arr=$rsIBlock->Fetch()) {
      $arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
    }

    // Сортировка по фильтру (Источник данных)
    $arSorts = [
      'ASC' => GetMessage('T_IBLOCK_DESC_ASC'),
      'DESC' => GetMessage('T_IBLOCK_DESC_DESC'),
    ];
    $arSortFields = [
      'ID' => GetMessage('T_IBLOCK_DESC_FID'),
      'NAME' => GetMessage('T_IBLOCK_DESC_FNAME'),
      'ACTIVE_FROM' => GetMessage('T_IBLOCK_DESC_FACT'),
      'SORT' => GetMessage('T_IBLOCK_DESC_FSORT'),
      'TIMESTAMP_X' => GetMessage('T_IBLOCK_DESC_FTSAMP'),
    ];

    // Получаем список свойств выбранного инфоблока
    $arProperty_LNS = array();
    $arProperty = [];
    if ($iblockExists) {
      $rsProp = CIBlockProperty::GetList(
        [
          "SORT" => "ASC",
          "NAME" => "ASC",
        ],
        [
          "ACTIVE" => "Y",
          "IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"],
        ]
      );
      while ($arr = $rsProp->Fetch()) {
        $arProperty[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
        if (in_array($arr["PROPERTY_TYPE"], ["L", "N", "S", "E"])) {
          $arProperty_LNS[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
        }
      }
    }

    // Устанавливаем права доступа к настройкам компонента
    $arUGroupsEx = [];
    $dbUGroups = CGroup::GetList();
    while($arUGroups = $dbUGroups -> Fetch()) {
      $arUGroupsEx[$arUGroups["ID"]] = $arUGroups["NAME"];
    }

    // Установка параметров, отображаемых в настройке компонента
    $arComponentParameters = [
      `GROUPS` - массив групп, настройки компонента разделены по группам (слева в настройках компонента)
      `PARAMETERS` - массив всех параметров настроек компонента
    ]

По-умолчанию в любом компоненте присутствуют следующие группы, одну из которых заносим в `PARENT`:
- `BASE` - основные параметры
- `ADDITIONAL_SETTINGS` - появляется при указании SET_TITLE,
- `CACHE_SETTINGS` - настройки кеша, появляется при указании CACHE_TIME,
- `SEF_MODE` - настройки ЧПУ,
- `URL_TEMPLATES` - шаблоны ссылок,
- `VISUAL` - настройки внешнего вида,
- `DATA_SOURCE` - источник данных, ID и TYPE инфоблока,
- `AJAX_SETTINGS` - настройки AJAX.

Создать свою собственную группу можно в массиве `GROUPS`, например:

    'DAMIR_SETTINGS' => [
      'SORT' => 100,
      'NAME' => 'Damir настройки'
    ],

Код далее:

    // Вызов настроек для постраничной навигации
    CIBlockParameters::AddPagerSettings(
      $arComponentParameters,
      GetMessage("T_IBLOCK_DESC_PAGER_NEWS"), //$pager_title
      true, //$bDescNumbering
      true, //$bShowAllParam
      true, //$bBaseLink
      ($arCurrentValues['PAGER_BASE_LINK_ENABLE'] ?? 'N') === 'Y' //$bBaseLinkEnabled
    );

    // Вызов настроек для установки 404 страницы
    CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);

    // Дополнительные условия которые нужны для отображения определённых параметров
    if (($arCurrentValues['USE_FILTER'] ?? 'N') === 'Y') {
      ...
    }

При помощи массива $arCurrentValues мы можем уже доставать значения которые установлены в основном массиве параметров.

## arComponentParameters
`arComponentParameters` - основной массив параметров, рассмотрим принципы его составления.  
`PARAMETERS` - содержит массивы в качестве элементов.  
Эти массивы и есть параметры, которые могут иметь ключи:
- `PARENT` - имя группы,
- `NAME` - имя параметра,
- `TYPE` - тип элемента управления,
- `VALUES` - массив значений для списка ('TYPE' = 'LIST'),
- `MULTIPLE` - одиночное/множественное значение (N/Y),
- `REFRESH` - перезагрузка настроек,
- `ADDITIONAL_VALUES` - показывать поле для значений, вводимых вручную (Y/N),
- `SIZE` - число строк для списка,
- `DEFAULT` - значение по-умолчанию,
- `COLS` - ширина поля в символах.

Имя массива может быть любым, главное чтобы они не пересекались в файле `.parameters.php`:

    'ARRAY_NAME' => array(
      'PARENT' => ... ,
      ...
    )

Для примера создадим свою настройку к текущему комплексному компоненту `news`, в котором будем задавать цвет компоненту. В массиве `PARAMETERS` создаём массив:

    'DAMIR_COLOR' => [
      // без ключей, поле появится в группе "Дополнительные настройки"
    ],

## Создаём с ключами
Ключ `TYPE` может иметь следующие значения:
- `STRING` - текстовое поле (можно задавать DEFAULT)
- `LIST` - список
- `CHECKBOX` - чекбокс
- `CUSTOM` - свой тип
- `FILE` - выбор файла
- `COLORPICKER` - выбор цвета

### STRING

    'DAMIR_COLOR' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Цвет компонента',  // Название настройки
      'TYPE' => 'STRING', // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
      'DEFAULT' => '#333', // Заполняем текстовое поле по-умолчанию
      // 'COLS' => '10', // Ширина поля (работает только с CUSTOM)
    ],

### LIST

    'DAMIR_COLOR_LIST' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Выбор цвета',  // Название настройки
      'TYPE' => 'LIST', // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
      'VALUES' => [ // Массив значений списка
        '#e00' => 'RED',
        '#090' => 'GREEN',
        '#00e' => 'BLUE'
      ],
    ]

Обычно в ключе `VALUES` массив не описывают, а получают/создают его выше в коде:

    // До arComponentParameters создаём массив
    $damirColor = [
      '#e00' => 'RED',
      '#090' => 'GREEN',
      '#00e' => 'BLUE'
    ];

    'VALUES' => $damirColor,

Устанавливаем значение которое в списке нет `'ADDITIONAL_VALUES' => 'Y'`, появится поле куда можно вписать своё значение.

    'DAMIR_COLOR_LIST' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Выбор цвета',  // Название настройки
      'TYPE' => 'LIST', // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
      'VALUES' => $damirColor,
      // 'ADDITIONAL_VALUES' => 'Y', // Написать своё значение
      // 'MULTIPLE' => 'Y', // Сделать множественный выбор
      // 'SIZE' => 2, // Высота поля списка
    ],

### CHECKBOX

    'DAMIR_COLOR_CHECKBOX' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Чекбокс цвета',  // Название настройки
      'TYPE' => 'CHECKBOX', // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
    ],

### CUSTOM
Создаём свои элементы управления, например гугл карту.

### FILE
Выбор файла из медиабиблиотеки или из структуры сайта, получаем путь к файлу от корня сайта.

    'DAMIR_COLOR_FILE' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Путь к файлу',     // Название настройки
      'TYPE' => 'FILE',             // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
      'FD_TARGET' => 'F',           // Цель которую ищем
      'FD_EXT' => 'jpeg,gif,png',   // С каким расширением должны быть файлы
      'FD_UPLOAD' => true,          // Откуда доставать, из медиабиблиотеки или структуры сайта (см ниже)
      'FD_USE_MEDIALIB' => true,
      'FD_MEDIALIB_TYPES' => [ 'video', 'sound' ],
    ],

### COLORPICKER

    'DAMIR_COLOR_COLORPICKER' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Палитра цветов',   // Название настройки
      'TYPE' => 'COLORPICKER',      // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
    ],

## REFRESH
Перезагружаем окно настроек после установки значений, для получения дополнительных данных.

    'DAMIR_COLOR_CHECKBOX' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Чекбокс цвета',    // Название настройки
      'TYPE' => 'CHECKBOX',         // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
      'REFRESH' => 'Y' // Обновить страницу настроек после изменения чекбокса
    ],

После рефреша с помощью текщего массива `$arCurrentValues` можно получить текущие значения настроек параметров компонента, которые были установлены до перезагрузки формы. Таким образом можно реализовать сложную логику зависимости поле между собой. Например это происходит при выборе шаблона комопонента, могут появиться дополнительные группы или при выборе типа инфоблока, подгружаются инфоблоки данного типа.

Пример рефреша, если наш чек бокс активен, то покажем настройки `DAMIR_COLOR_FILE` и `DAMIR_COLOR_COLORPICKER`. В массивы `DAMIR_COLOR_COLORPICKER` и `COLORPICKER` добавим ключ `'HIDDEN' => 'Y'` который скрывает параметр:

    'DAMIR_COLOR_FILE' => [
      'PARENT' => 'DAMIR_SETTINGS', // Название группы
      'NAME' => 'Путь к файлу',     // Название настройки
      'TYPE' => 'FILE',             // Тип настройки (текстовое поле, выпадающий список, чекбокс и т.п.)
      'FD_TARGET' => 'F',           // Цель которую ищем
      'FD_EXT' => 'jpeg,gif,png',   // С каким расширением должны быть файлы
      'FD_UPLOAD' => true,          // Откуда доставать, из медиабиблиотеки или структуры сайта (см ниже)
      'FD_USE_MEDIALIB' => true,
      'FD_MEDIALIB_TYPES' => [ 'video', 'sound' ],
      'HIDDEN' => 'Y', // Скрытый параметр
    ],

Чтобы параметр скрывался в зависимости от чекбокса `DAMIR_COLOR_CHECKBOX` то пропишем:

    'DAMIR_COLOR_FILE' => [
      ...

      'HIDDEN' => (isset($arCurrentValues['DAMIR_COLOR_CHECKBOX']) && $arCurrentValues['DAMIR_COLOR_CHECKBOX'] == 'N' ? 'Y' : 'N' ),

      ...
    ]

## Подсказки для параметров настроек
Раньше добавляли подсказки в файл: `/local/components/damir/news/lang/ru/help/.tooltips.php`:

    $MESS["SEF_MODE_TIP"] = "Опция включает режим поддержки ЧПУ.";

Сейчас подсказки можно добавлять в языковой файл текущего параметра `/local/components/damir/news/lang/ru/.parameters.php`

    $MESS["DAMIR_COLOR_CHECKBOX_TIP"] = "Переключайте чекбокс, чтобы появились дополнительные настройки.";
