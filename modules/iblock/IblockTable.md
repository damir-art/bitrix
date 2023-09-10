## Поля инфоблока
Открыв таблицу инфоблоков `b_iblock` там будут теже поля что и ниже в выводе.

Получаем инфоблок по его ID или CODE:

    \Bitrix\Main\Loader::includeModule('iblock');

    $iblockNewsObj = \Bitrix\Iblock\IblockTable::getList([
      'filter' => ['CODE' => 'news'],
    ]);

    $iblockNewsArr = $iblockNewsObj->fetch();
    //$iblockNewsArrs = $iblockNewsObj->fetch(); // Появится индекс [0]

    echo '<pre>';
    print_r($iblockNewsArr);
    echo '</pre>';
