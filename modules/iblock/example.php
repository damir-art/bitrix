<?php
// Получаем CSV-файл
// Получаем инфоблок 2
// Получаем разделы инфоблока 2
// Получаем раздел с ID 16
// Получаем пользовательское поле UF_MANAGER для раздела с ID 16, инфоблока 2
// Получаем значение пользовательского поля UF_MANAGER для раздела с ID 16, инфоблока 2

// Получаем CSV файл
// Получаем доступ к файлу (можно также работать через класс CCSVData())
$fileCsv = $_SERVER['DOCUMENT_ROOT'] . '/crm_city_shorts.csv';
//echo $fileCsv;

$res = [];
// Проверяем CSV-файл на существование ($file - указатель на файл, r - файл открываем в режиме чтения)
if(($file = fopen($fileCsv, 'r')) !== false) {
  // echo 'Есть доступ к файлу';
  // Получив доступ, построчно считываем файл
  while(($data = fgetcsv($file, 1000, ';')) !== false ) {
    $res[] = $data;
  }
  // Закрываем файл после работы с ним
  fclose($file);
};

echo '<pre>';
print_r( $res );
echo '</pre>';

// Получаем инфоблок 2
\Bitrix\Main\Loader::includeModule('iblock');
$iblockObj = \Bitrix\Iblock\IblockTable::getList([
  'filter' => [ 'ID' => 2 ]
]);
$iblockArr = $iblockObj->fetch();

echo '<pre>';
//print_r($iblockArr);
echo '</pre>';

// Получаем раздел с ID 16
$iblockSectionObj = \Bitrix\Iblock\SectionTable::getList([
   //'select' => [ 'ID', 'NAME' ],
  'filter' => [ 'IBLOCK_ID' => 2, 'ID' => 16 ],
]);

$iblockSectionArrs = $iblockSectionObj->fetchAll();

echo '<pre>';
//print_r($iblockSectionArrs);
echo '</pre>';

// Получаем пользовательское поле UF_MANAGER для раздела с ID 16, инфоблока 2
$userFieldsObj = \Bitrix\Main\UserFieldTable::getList([
  'filter' => [ 'ENTITY_ID' => 'IBLOCK_2_SECTION', 'FIELD_NAME' => 'UF_MANAGER' ]
]);

while($userFieldArr = $userFieldsObj->fetch()) {
  echo '<pre>';
  print_r($userFieldArr);
  echo '<pre>';
}

// Получаем значение пользовательского поля UF_MANAGER для раздела с ID 16, инфоблока 2
global $USER_FIELD_MANAGER;
$res = $USER_FIELD_MANAGER->GetUserFieldValue('IBLOCK_2_SECTION', 'UF_MANAGER', 16 );
echo $res;

// Обновляем значение пользовательского поля UF_MANAGER для раздела с ID 16, инфоблока 2
$update = $USER_FIELD_MANAGER->Update('IBLOCK_2_SECTION', 16, array("UF_MANAGER" => 'МАНАГЕР 3'));

// Добавляем разделы и заполняем пользовательские свойства
$bs = new CIBlockSection;
$arFields = Array(
  'IBLOCK_ID' => 4,
  'NAME' => 'Питер2',
  'UF_MANAGER' => 'Менеджер Питер2',
);
$ID = $bs->Add($arFields);
