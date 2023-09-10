# Типы инфоблоков
Пример, получаем список всех типов инфоблоков на сайте:

    \Bitrix\Main\Loader::includeModule('iblock');
    $iblockListType = \Bitrix\Iblock\TypeTable::getList();
    $iblockListTypeArr = \Bitrix\Iblock\TypeTable::getList()->fetchAll();

    echo '<pre>';
    print_r($iblockListTypeArr); // Получаем список всех типов инфоблоков на сайте
    echo '</pre>';

`b_iblock_type` - имя таблицы типов инфоблоков.
