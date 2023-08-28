<?php
/**
 * Скрипт однократно запускаем в Битриксе.
 * Проходится по всей базе Контактов, везде где поля Имя и Фамилия пустые, указывает значение Нет данных.
 */
$contacts = \Bitrix\Crm\ContactTable::getList([
  'filter' => [
    'LOGIC' => 'OR',
      [
        'NAME' => false
      ],
      [
        'LAST_NAME' => false
      ]
  ],
  'select' => [ 'ID', 'NAME', 'LAST_NAME' ]
]);

while ( $contact = $contacts->fetch() ) {
  if(!$contact['NAME']) {
    $contactID = $contact['ID'];
    $changeNameContact = \Bitrix\Crm\ContactTable::update($contactID, array(
      'NAME' => 'Нет данных'
    ));
  }

  if(!$contact['LAST_NAME']) {
    $contactID = $contact['ID'];
    $changeLastNameContact = \Bitrix\Crm\ContactTable::update($contactID, array(
      'LAST_NAME' => 'Нет данных'
    ));
  }
}
