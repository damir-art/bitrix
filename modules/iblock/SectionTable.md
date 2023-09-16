# SectionTable
Выборка разделов инфоблока: https://estrin.pw/bitrix-d7-snippets/s/iblock-sectiontable/  
Работаем с разделами: https://tokmakov.msk.ru/blog/item/217

- выбираем все разделы конкретного инфоблока,
- выбираем все свойства раздела конкретного инфоблока,

## Выбираем разделы

    \Bitrix\Main\Loader::includeModule('iblock');

    // Получаем список инфоблоков, фильтруем по CODE
    $iblockObj = \Bitrix\Iblock\IblockTable::getList([
      'filter' => [ 'CODE' => 'clothes' ],
    ]);

    // Получаем инфоблок
    $iblockArr = $iblockObj->fetch();

    // Получаем ID инфоблока
    $iblockId = $iblockArr['ID'];

    // Получаем все каталоги инфоблока с ID = $iblockId, в том числе и вложенные
    $iblockSectionObj = \Bitrix\Iblock\SectionTable::getList([
      'select' => [ 'ID', 'NAME' ],
      'filter' => [ 'IBLOCK_ID' => $iblockId ],
    ]);

    $iblockSectionArrs = $iblockSectionObj->fetchAll();

    echo '<pre>';
    print_r($iblockSectionArrs);
    echo '</pre>';

## Выбираем свойства раздела
Свойства раздела хранятся в `Пользовательских полях` для доступа к ним, нужно воспользоваться классом: \Bitrix\Main\UserFieldTable::getList()

    // Получаем пользовательские свойства
    $iblockSectionPropertyObj = \Bitrix\Main\UserFieldTable::getList([
      //'filter' => [ 'FIELD_NAME' => 'UF_MANAGER' ]
    ]);

    //$iblockSectionPropertyArrs = $iblockSectionPropertyObj->fetch();
    $iblockSectionPropertyArrs = $iblockSectionPropertyObj->fetchAll();

    echo '<pre>';
    print_r($iblockSectionPropertyArrs);
    echo '</pre>';

Выборка пользовательских полей типа список:

    [USER_TYPE_ID] => string
