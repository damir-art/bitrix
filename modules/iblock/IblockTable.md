# Поля инфоблока
Класс IblockTable работает с таблицей `b_iblock`.  
Таблица `b_iblock` содержит инфоблоки сайта и поля, что и ниже в выводе.

Выводим список всех инфоблоков сайта:

    \Bitrix\Main\Loader::includeModule('iblock');

    $iblockObj = \Bitrix\Iblock\IblockTable::getList();

    $iblockArrs = $iblockObj->fetchAll();
    //$iblockArrs = $iblockObj->fetch(); // Выведется первый инфоблок

    echo '<pre>';
    print_r($iblockArrs);
    echo '</pre>';

Получаем инфоблок по его ID или CODE:

    \Bitrix\Main\Loader::includeModule('iblock');

    $iblockNewsObj = \Bitrix\Iblock\IblockTable::getList([
      'filter' => ['CODE' => 'news'],
    ]);

    $iblockNewsArr = $iblockNewsObj->fetch();

    echo '<pre>';
    print_r($iblockNewsArr);
    echo '</pre>';
