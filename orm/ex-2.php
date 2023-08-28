<?php
/**
 * Скрипт однократно запускаем в Битриксе.
 * Проходится по всей базе Компаний, везде где поле Страна пустое, указывает значение Россия
 */
$companies = \Bitrix\Crm\CompanyTable::getList([
  'filter' => [ 'UF_CRM_1660115730' => false ],
  'select' => [ 'ID', 'TITLE', 'UF_CRM_1660115730' ]
]);

$fields = \Bitrix\Main\UserFieldTable::getList(array(
  'filter' => array('ENTITY_ID' => 'CRM_COMPANY', 'FIELD_NAME' => 'UF_CRM_1660115730')
));

while ($field = $fields->fetch()) {
  echo '<pre>';
  print_r($field);
  echo '</pre>';

  if ($field["USER_TYPE_ID"] == 'enumeration') {
    $uField = CUserFieldEnum::GetList(
      array(),
      array('USER_FIELD_ID' => $field['ID'], 'VALUE' => 'Россия' )
    )->Fetch();

    $countryRussiaEnumId = $uField['ID'];
    echo '<pre>';
    print_r($uField['ID']);
    echo '</pre>';
  }
}

while ( $company = $companies->fetch() ) {
  echo '<pre>';
  print_r($company);
  echo '</pre>';

  $changeCountryCompany = \Bitrix\Crm\CompanyTable::update($company['ID'], array(
    'UF_CRM_1660115730' => $countryRussiaEnumId
  ));
}
