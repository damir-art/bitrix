# CRM
- CRM в D7:            https://dev.1c-bitrix.ru/api_d7/bitrix/crm/index.php
- Новое API CRM:       https://dev.1c-bitrix.ru/api_d7/bitrix/crm/crm_new_api.php
- Модуль CRM:          https://bx24devbook.website.yandexcloud.net/Modul_CRM/O_module.html
- События CRM:         https://dev.1c-bitrix.ru/api_help/crm/crm_events.php
- Работа с элементами: https://dev.1c-bitrix.ru/api_d7/bitrix/crm/elements.php
- Операции:            https://dev.1c-bitrix.ru/api_d7/bitrix/crm/service/operation/index.php
- Action:              https://dev.1c-bitrix.ru/api_d7/bitrix/crm/service/operation/action.php
- Элементы CRM:        https://dev.1c-bitrix.ru/api_d7/bitrix/crm/item/index.php

## События

    $eventManager = \Bitrix\Main\EventManager::getInstance();

    $eventManager->addEventHandlerCompatible(
      'crm',
      'OnBeforeCrmCompanyUpdate',
      function( &$arFields )
      {
          // ...
      }
    );

## Получение данных
- Получение адреса компании: https://almat.su/kak-poluchit-rekvizity-adres-v-crm-v-bitriks24/
