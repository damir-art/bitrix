# Пользовательские поля
В админке, пользовательские поля смотрим тут: http://127.0.0.1/bitrix/admin/userfield_admin.php

Получение пользовательских полей в D7.
https://dev.1c-bitrix.ru/api_help/main/reference/cuserfieldenum/getlist.php

Получаем пользовательские поля `CRM_COMPANY` и где `FIELD_NAME` равен `UF_CRM_1660115730`:

    $fields = \Bitrix\Main\UserFieldTable::getList(array(
      'filter' => array('ENTITY_ID' => 'CRM_COMPANY', 'FIELD_NAME' => 'UF_CRM_1660115730')
    ));

    while ($field = $fields->fetch()) {
      echo '<pre>';
      print_r($field);
      echo '</pre>';
      //break;
    }

Работаем с пользовательским полем список (enumeration), обновляем значение:

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
      break;
    }
