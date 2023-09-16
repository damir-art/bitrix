# UserFieldTable (пользователские поля)
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3483

Выборка пользовательских полей
- https://estrin.pw/bitrix-d7-snippets/s/user-fields/

Работа с пользовательскими полями при помощи класса `\Bitrix\Main\UserFieldTable`.

Выборка всех пользовательских полей:

    $userFieldsObj = \Bitrix\Main\UserFieldTable::getList();

    while($userFieldArr = $userFieldsObj->fetch()) {
      echo '<pre>';
      print_r($userFieldArr);
      echo '<pre>';
    }

Можно еще так:

    $userFieldObj = \Bitrix\Main\UserFieldTable::getList();
    $userFieldArrs = $userFieldObj->fetchAll();

    echo '<pre>';
    print_r($userFieldArrs);
    echo '</pre>';

## Получаем значение
Получаем значение пользовательского поля UF_MANAGER для раздела с ID 16, инфоблока 2

    global $USER_FIELD_MANAGER;
    $res = $USER_FIELD_MANAGER->GetUserFieldValue('IBLOCK_2_SECTION', 'UF_MANAGER', 16 );
    echo $res;

- `[ENTITY_ID] => IBLOCK_2_SECTION` - ID сущности,
- `[FIELD_NAME] => UF_MANAGER` - имя пользовательского поля,
- `16` - ID элемента инфоблока, в нашем случае раздела,

## Обновляем значение

    global $USER_FIELD_MANAGER;

    // При успехе в $update запишится 1
    $update = $USER_FIELD_MANAGER->Update('IBLOCK_2_SECTION', 16, array("UF_MANAGER" => 'МАНАГЕР 3'));

## Пользовательское поле типа список
Выборка пользовательских полей типа список:

    $rsUserFields = \Bitrix\Main\UserFieldTable::getList(array(
      'filter'=>array('USER_TYPE_ID'=>'enumeration'),
    ));

    while($arUserField=$rsUserFields->fetch()) {
      print_r($arUserField);
    }

Получаем значения пользовательских полей (список):

    $dbUserFields = \Bitrix\Main\UserFieldTable::getList(array(
      'filter' => array('ENTITY_ID' => 'IBLOCK_2_SECTION')
    ));
    while ($arUserField = $dbUserFields->fetch()) {
      if ($arUserField["USER_TYPE_ID"] == 'enumeration') {
        $dbEnums = CUserFieldEnum::GetList(
          array(),
          array('USER_FIELD_ID' => $arUserField['ID'])
        );
        while ($arEnum = $dbEnums->GetNext()) {
          $arUserField['ENUMS'][$arEnum['XML_ID']] = $arEnum;
        }
      }
      $arResult['USER_FIELDS'][$arUserField["FIELD_NAME"]] = $arUserField;
    }

    echo '<pre>';
    print_r($arResult);
    echo '</pre>';

## Разное
Работа с пользовательскими полями в битриксе реализована очень плохо, разработчики рекомендуют использовать пользовательские свойства, а не поля. Обновить пользовательское поле (скорее всего любое) можно так:

    $GLOBALS["USER_FIELD_MANAGER"]->Update("ORDER", $id, Array("UF_CRM_123123"=>$value)); 

    ORDER - имя сущности, в данном случае заказ
    $id - айди элемента в котором нужно изменить поле
    UF_CRM_123123 - айди поля
    $value - значение

## Ссылка
Работаем с пользовательскими полями на старом API: https://www.acrit-studio.ru/pantry-programmer/knowledge-base/polzovatelskie-polya/primery-raboty-s-polzovatelskimi-polyami/
Работаем с пользовательскими полями на старом API: https://quantum-lab.ru/insight/1c/2016/08/27/bitriks.-rabotaem-s-polzovatelskimi-polyami-v-crm/
