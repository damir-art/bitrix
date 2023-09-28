# Лиды
Работаем с лидами (примеры подходят для лида, сделки, предложения).

## Получаем поля лида
Работаем с лидами через фабрику:

    $leadFactory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(\CCrmOwnerType::Lead);
    $fieldsCollection = $leadFactory->getFieldsCollection();

    foreach ($fieldsCollection as $field) {
      echo $field->getName();
      echo ': ';
      echo $field->getTitle();
      echo '<br />';
    }

## Получаем товары лида
https://developer-consult.ru/articles/api-kak-poluchit-vse-dannye-sdelki/ - D7  
https://nikaverro.ru/blog/bitrix/crm-bitrix24-korobka-api/ - старое АПИ

    $arFilter = array(
      "OWNER_ID"=> $id, //ID сделки, лида, предложения
      "CHECK_PERMISSIONS"=>"N" //не проверять права доступа текущего пользователя
    );
    $arSelect = array(
      "*"
    );

    $res = CCrmProductRow::GetList(['ID'=>'DESC'], $arFilter, false, false, $arSelect);
    while($arProduct = $res->Fetch()) {
      echo '<pre>';
      print_r($arProduct);
      echo '</pre>';
    }

D7:

    /**
      $entity_type - Тип сущности ('D' - сделкa, 'L' - лид) 
      $entity_id - ID сущности
      $products - массив товаров
    */
    $products = CAllCrmProductRow::LoadRows($entity_type, $entity_id);

## Удаляем товары из лида

    // $entityType: 'D' - сделка, 'L' - лид, 'Q' - предложение
    // $id: ID лида, сделки, предложения
    CCrmProductRow::DeleteByOwner($entityType, $id);

    CCrmProductRow::DeleteByOwner('L', 2627);

## Добавляем товар в лид