# Operation

## Рабочий код по операциям CRM
Пройтись по всей базе `Компаний`, везде где поле `Страна` пустое, указать значение `Россия`:

    $companies = \Bitrix\Crm\CompanyTable::getList([
      'filter' => [ 'UF_CRM_1660115730' => false ],
      'select' => [ 'ID', 'TITLE', 'UF_CRM_1660115730' ],
      'count_total' => 1
    ]);

    echo 'companies: '. $companies -> getCount() . '<br />'; // Всего элементов

    $i = 0;
    while ( $company = $companies->fetch() ) {
      echo '<pre>';
      print_r($company);
      echo '</pre>';

      // $compID = $company['ID'];
      // $changeCountryCompany = \Bitrix\Crm\CompanyTable::update($compID, array(
      //   'UF_CRM_1660115730' => 681
      // ));

      if ( $i == 5 ) {
        break;
      }
      $i++;
    }

Пройтись по всей базе `Контактов`, везде где поля `Имя` или `Фамилия` пустое, указать значение `Нет данных`:

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
      'select' => [ 'ID', 'NAME', 'LAST_NAME' ],
      'count_total' => 1
    ]);

    echo 'contacts: '. $contacts -> getCount() . '<br />'; // Всего элементов

    $i = 0;
    while ( $contact = $contacts->fetch() ) {
      echo '<pre>';
      print_r($contact);
      echo '</pre>';

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

      if ( $i == 5 ) {
        break;
      }
      $i++;
    }