# Добавление раздела
- https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblocksection/add.php
- Поля раздела: https://dev.1c-bitrix.ru/api_help/iblock/fields.php#fsection

    int
    CIBlockSection::Add(
      array arFields, 
      bool bResort = true,
      bool bUpdateSearch = true,
      bool bResizePictures = false
    );

Обычно используется только `arFields`: массив вида `Array( "поле" => "значение", ...)`, содержащий значения полей раздела инфоблоков. Пользовательские свойства `UF_XXX` можно тоже занести в массив и они будут добавляться.

Метод добавляет новый раздел в информационный блок. Перед добавлением раздела вызываются обработчики события OnBeforeIBlockSectionAdd из которых можно изменить значения полей или отменить добавление раздела вернув сообщение об ошибке. После добавления раздела вызывается событие OnAfterIBlockSectionAdd. Нестатический метод.

Метод возвращает идентификационный код добавленного раздела блока, если добавление прошло успешно. При возникновении ошибки метод вернет false, а в свойстве объекта LAST_ERROR будет содержаться текст ошибки.

Пример:

    <?php
    $bs = new CIBlockSection;
    $arFields = Array(
      "ACTIVE" => $ACTIVE,
      "IBLOCK_SECTION_ID" => $IBLOCK_SECTION_ID,
      "IBLOCK_ID" => $IBLOCK_ID,
      "NAME" => $NAME,
      "SORT" => $SORT,
      "PICTURE" => $_FILES["PICTURE"],
      "DESCRIPTION" => $DESCRIPTION,
      "DESCRIPTION_TYPE" => $DESCRIPTION_TYPE
    );
    if($ID > 0) {
      $res = $bs->Update($ID, $arFields);
    } else {
      $ID = $bs->Add($arFields);
      $res = ($ID>0);
    }
    if(!$res) {
      echo $bs->LAST_ERROR;
    }

## Рабочий код
Рабочий код добавления раздела в инфоблок с ID = 4:

    \Bitrix\Main\Loader::includeModule('iblock');
    $bs = new CIBlockSection;
    $arFields = Array(
      'IBLOCK_ID' => 4,
      'NAME' => 'Питер2',
      'UF_MANAGER' => 'Менеджер Питер2',
    );
    $ID = $bs->Add($arFields);

## D7 пока не поддерживается

    $result = SectionTable::add([
      "IBLOCK_ID" => $iblockId,
      "IBLOCK_SECTION_ID" => $sectionId,
      "NAME" => $name,
      "CODE" => $code,
    ]);

## Ссылки
- Создание разделов: https://bazarow.ru/blog-note/16643/
