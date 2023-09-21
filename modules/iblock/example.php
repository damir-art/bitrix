<?php
/**
 * ЗАДАЧА: Получить эксель файл и на основании этой таблицы создать разделы в инфоблоке
 * ПОДЗАДАЧИ:
 * Получение инфоблока и удаление всех его разделов:
 * - подключаем модуль инфоблока
 * - получаем список всех инфоблоков на сайте
 * - получаем инфоблок с CODE = 'districts'
 * - получаем ID инфоблока с CODE = 'districts'
 * - получаем все разделы инфоблока
 * - удаляем все разделы инфоблока
 * Получение CSV файла и создание на его основе разделов инфоблока:
 * - получаем доступ к CSV файлу
 * - построчно считываем файл и добавляем строки таблицы в массив
 * - изучаем полученный массив массивов и его ключи
 * - прогоняем массив массивов по циклу
 * - внутри цикла создаём блок создания разделов
 * - при создании раздела помещаем в пользовательские свойства значения
 * СВОЙСТВА РАЗДЕЛОВ:
 * 'IBLOCK_ID' => int
 * 'GUID' => Внешний код: (GUID) string
 * 'NAME' => Название раздела (Наименование)
 * => Родительский раздел (Головной раздел) привязка к разделам инфоблока
 * 'UF_MANAGER' => Менеджер ()
 * 'UF_CLIENT_SPECIALIST' => Клиентский специалист: (Клиентский специалист) привязка к сотруднику
 * 'UF_COORD_SERVICE' => Координатор отдела сервиса: (Координатор отдела сервиса) привязка к сотруднику
 */

// Подключаем модуль инфоблока
\Bitrix\Main\Loader::includeModule('iblock');

// Получаем список всех инфоблоков на сайте
// Получаем инфоблок с CODE = 'districts' (Списки > Федеральные округа)
$iblockListObj = \Bitrix\Iblock\IblockTable::getList([
  'filter' => ['CODE' => 'districts']
]);

$iblockArr = $iblockListObj->fetch();

// echo '<pre>';
// print_r($iblockArr);
// echo '</pre>';

// Получаем ID инфоблока с CODE = 'districts'
$iblockId = $iblockArr['ID']; // 

// Получаем раздел инфоблока ID = 4, с NAME = 'Россия'
$iblockSectionObj = \Bitrix\Iblock\SectionTable::getList([
  //'select' => [ 'ID', 'NAME' ],
  'filter' => [ 'IBLOCK_ID' => $iblockId, 'NAME' => 'Россия' ],
]);

// Удаляем раздел инфоблока ID = 4, с NAME = 'Россия', если он есть
$iblockSection = $iblockSectionObj->fetchObject();

if ($iblockSection) {
  $iblockSection->delete();
} else {
  // echo 'Раздела не существует!';
}

// Получаем доступ к CSV файлу (можно также работать через класс CCSVData())
$pathCsv = $_SERVER['DOCUMENT_ROOT'] . '/crm_city_shorts.csv';
//echo $pathCsv; // C:/OSPanel/domains/bx.loc/crm_city_shorts.csv

$arrCsv = [];
// Проверяем CSV-файл на существование ($fileCsv - указатель на файл, r - файл открываем в режиме чтения)
if(($fileCsv = fopen($pathCsv, 'r')) !== false) {
  // echo 'Есть доступ к файлу';
  // Получив доступ, построчно считываем файл в массив
  while(($rowCsv = fgetcsv($fileCsv, 1000, ';')) !== false ) {
    $arrCsv[] = $rowCsv;
  }
  // Закрываем файл после работы с ним
  fclose($fileCsv);
}

// Вывод массива, изучаем ключи
// echo '<pre>';
// print_r( $arrCsv );
// echo '</pre>';

// Прогоняем массив $arrCsv по циклу,
// Каждый элемент $item этого массива является массивом,
// У элемента массива берем значение первого ключа $item[0],
// Помещаем это значение в имя раздела, который создаём в этом же цикле
// Также присваиваем значения пользовательским полям

// Сначала создаём родительские разделы: Россия (корневой), Федеральные округа (подразделы)
// Россия: 672957dd-f2f1-11db-81d2-0007e93f608d
// Федеральные округа:
// - Дальневосточный: faf140d9-d2c2-11db-ab5a-0007e93f608d
// - Приволжский:     faf140da-d2c2-11db-ab5a-0007e93f608d
// - Северо-Западный: faf140db-d2c2-11db-ab5a-0007e93f608d
// - Сибирский:       faf140dc-d2c2-11db-ab5a-0007e93f608d
// - Уральский:       faf140dd-d2c2-11db-ab5a-0007e93f608d
// - Центральный:     faf140ed-d2c2-11db-ab5a-0007e93f608d
// - Южный:           faf140e4-d2c2-11db-ab5a-0007e93f608d
// Затем уже добавляем элементы таблицы

$guidsIdList = [];
// Россия самый корневой раздел
$russiaSection = new CIBlockSection;
$arFields = Array(
  'IBLOCK_ID' => $iblockId, // Инфоблок с ID = 4
  'NAME' => 'Россия',
  'EXTERNAL_ID' => '672957dd-f2f1-11db-81d2-0007e93f608d'
);
if($elId = $russiaSection->Add($arFields)) {
  // $guidsIdList[$arFields['EXTERNAL_ID']] = $arFields['ID'];
  $guidsIdList[$arFields['EXTERNAL_ID']] = $elId;
}

$arrCsvNew = array_shift($arrCsv);

//echo '<pre>';
//print_r( $arrCsvNew );
//echo '</pre>';

// Создаём массив пользователей с ID и LOGIN
$userListObj = \Bitrix\Main\UserTable::getList([
  'select' => [ 'ID', 'LOGIN' ],
]);
$userArrs = $userListObj->fetchAll();

$userArrsNew = [];
foreach($userArrs as $userArr) {
  $userArrsNew[$userArr['LOGIN']] = $userArr['ID'];
}

foreach ($arrCsv as $item) {
  // echo '<pre>';
  // print_r( $item );
  // echo '</pre>';

  $bs = new CIBlockSection;
  $arFields = Array(
    'IBLOCK_ID' => $iblockId, // Инфоблок с ID = 4
    'NAME' => $item[0],
    'IBLOCK_SECTION_ID' => $guidsIdList[$item[6]],
    'EXTERNAL_ID' => $item[5],
    'UF_MANAGER' => $userArrsNew[$item[2]],
    'UF_CLIENT_SPECIALIST' => $userArrsNew[$item[3]],
    'UF_COORD_SERVICE' => $userArrsNew[$item[4]]
  );

  if($elId = $bs->Add($arFields)) {
    $guidsIdList[$arFields['EXTERNAL_ID']] = $elId;
    // // Пользовательские поля привязки
    // global $USER_FIELD_MANAGER;
    // $USER_FIELD_MANAGER->Update('IBLOCK_ '. $iblockId . '_SECTION', $elId, array("UF_MANAGER" => $managerId));
    // $USER_FIELD_MANAGER->Update('IBLOCK_ '. $iblockId . '_SECTION', $elId, array("UF_CLIENT_SPECIALIST" => $clientId));
    // $USER_FIELD_MANAGER->Update('IBLOCK_ '. $iblockId . '_SECTION', $elId, array("UF_COORD_SERVICE" => $coordId));
  }
}

// echo '<pre>';
// print_r($guidsIdList);
// echo '</pre>';
