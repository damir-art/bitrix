<?php
/**
 * Файл разместить в: /local/php_interface/include
 */

/**
 * Задача T1 Клауд ТП #11367
 * При создании и обновлении компании в CRM, сделать так чтобы ИНН в реквизитах был заполнен
 * Если ИНН компании в реквизитах отсутствует, то выбрасывать ошибку и запрещать сохранение компании
 * Решение: при обновление также работать с API взаимодействующей c POST запросами AJAX:
 *   $request = Context::getCurrent()->getRequest();
 *   $requisites = $request->get('REQUISITES');
 */

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandlerCompatible(
  'crm',
  'OnBeforeCrmCompanyUpdate',
  'checkInnUpdate'
);

$eventManager->addEventHandlerCompatible(
  'crm',
  'OnBeforeCrmCompanyAdd',
  'checkInnAdd'
);

function checkInnAdd( &$arFields ) {

  $request = Context::getCurrent()->getRequest();
  $requisites = $request->get('REQUISITES');
  $idReq = array_key_first($requisites); // ID Реквизита
  $fields = $requisites[$idReq]["DATA"]; // JSON-data
  $arrFields = json_decode($fields, true);
  $innRQ = $arrFields["fields"]["RQ_INN"]; // ИНН компании

  // \Bitrix\Main\Diag\Debug::writeToFile(
  //   $innRQ,
  //   "-----\nИНН\n-----",
  //   'local/log.php'
  // );

  global $APPLICATION;
  $oException = new CAdminException([]);
  $bErr = false;

  if(empty($innRQ)) {
    $oException->AddMessage(
      [
        "text" => "Ошибка создания компании. Не заполнен реквизит: ИНН.",
        "id" => false
      ]
    );
    $bErr = true;
  }

  if ($bErr) {
    $arFields['RESULT_MESSAGE'] = $oException->GetString();
    $APPLICATION->throwException($oException);
    return false;
  }

  return true;
}

function checkInnUpdate( &$arFields ) {

  $request = Context::getCurrent()->getRequest();
  $requisites = $request->get('REQUISITES');
  $idReq = array_key_first($requisites); // ID Реквизита
  $fields = $requisites[$idReq]["DATA"]; // JSON-data
  $arrFields = json_decode($fields, true);
  $innRQ = $arrFields["fields"]["RQ_INN"]; // ИНН компании

  $requisite = new \Bitrix\Crm\EntityRequisite();
  $rs = $requisite->getList([
    "filter" => [ "ENTITY_ID" => $arFields["ID"], "ENTITY_TYPE_ID" => 4 ]
  ]);
  $reqData = $rs->fetchAll();

  $inn = $reqData[0][RQ_INN];

  // \Bitrix\Main\Diag\Debug::writeToFile(
  //   $fields,
  //   "-----\nFIELDS\n-----",
  //   'local/log.php'
  // );

  // \Bitrix\Main\Diag\Debug::writeToFile(
  //   $innRQ,
  //   "-----\ninnRQ\n-----",
  //   'local/log.php'
  // );

  // \Bitrix\Main\Diag\Debug::writeToFile(
  //   $inn,
  //   "-----\nINN\n-----",
  //   'local/log.php'
  // );

  global $APPLICATION;
  $oException = new CAdminException([]);

  if ( !$innRQ ) {
    if( empty($inn) ) {
      $oException->AddMessage(
        [
          "text" => "Ошибка обновления компании. Не заполнен реквизит: ИНН.",
          "id" => false
        ]
      );
      $arFields['RESULT_MESSAGE'] = $oException->GetString();
      $APPLICATION->throwException($oException);
      return false;
    }
  }

  return true;
}
