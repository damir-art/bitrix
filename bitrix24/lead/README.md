# Лиды
Работаем с лидами (примеры подходят для лида, сделки, предложения).  
https://developer-consult.ru/articles/api-kak-poluchit-vse-dannye-sdelki/ - D7  
https://nikaverro.ru/blog/bitrix/crm-bitrix24-korobka-api/ - старое АПИ  

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

    \Bitrix\Main\Loader::includeModule('crm');
    \CCrmProductRow::Add(['OWNER_TYPE' => 'D', 'OWNER_ID' => 340, 'PRODUCT_ID' => 854, 'QUANTITY' => 1, 'PRICE' => 111, 'MEASURE_CODE' => 796, 'CURRENCY_ID' => 'RUB']);

    CCrmProductRow::Add($arProduct);

Еще один способ:

    $rows[] = [
        'PRODUCT_ID' => 2547, //id товара
        'QUANTITY' => 1, //количество
        'PRICE' => 1000, //цена
    ];
    $res = CCrmProductRow::SaveRows('L', 2613, $rows);

Еще способ:

    $res = CCrmProductRow::GetList(['ID'=>'DESC'], $arFilter,false,false,$arSelect);
    while($arProduct = $res->Fetch()){
      unset($arProduct["ID"]);
      $arProduct["OWNER_ID"] = $arQuote["LEAD_ID"];
      $arProduct["OWNER_TYPE"] = 'L';
      CCrmProductRow::Add($arProduct);            
    }

Полная функция:

    function MoveProductsFromCPtoLead($id,$deleteFromLead=false){
      if(!empty($id) && CModule::IncludeModule('crm')){
          $arFilter = array(
            "ID"=>$id, //выбираем определенную сделку по ID
            "CHECK_PERMISSIONS"=>"N", //не проверять права доступа текущего пользователя   
            "!LEAD_ID"=>false      
          );   
          $arSelect = ["LEAD_ID"];   
          $res = CCrmQuote::GetList(Array(), $arFilter,false,false, $arSelect);
          if($arQuote = $res->Fetch()){
            if($deleteFromLead){
                CCrmProductRow::DeleteByOwner('L',$arQuote["LEAD_ID"]);
            }         
            $arFilter = array(
                "OWNER_ID"=>$id, 
                "CHECK_PERMISSIONS"=>"N" //не проверять права доступа текущего пользователя
            );            
            $arSelect = array(
                "*"         
            );            
            $res = CCrmProductRow::GetList(['ID'=>'DESC'], $arFilter,false,false,$arSelect);         
            while($arProduct = $res->Fetch()){
                unset($arProduct["ID"]);
                $arProduct["OWNER_ID"] = $arQuote["LEAD_ID"];
                $arProduct["OWNER_TYPE"] = 'L';
                CCrmProductRow::Add($arProduct);            
            }
          }
      }
    }
