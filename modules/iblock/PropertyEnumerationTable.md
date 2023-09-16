# Множественные свойства инфоблока
https://estrin.pw/bitrix-d7-snippets/s/iblock-enum-variants/

Выводим множественные (перечисление, enumeration) свойства инфоблока:
- множественные свойства хранятся в таблице `b_iblock_property_enum`,
  - если в этой таблице навести курсором мыши на ссылку `PROPERTY_ID`, то покажет свойство к которому принадлежит это множественное,
- у каждого свойства есть поле `PROPERTY_ID` кторое указывает на `ID` свойства в таблице `b_iblock_property`, которому принадлежит данное множественное свойство.

Выведем список всех множественных свойств:

    \Bitrix\Main\Loader::includeModule('iblock');

    $iblockPropsEnumObj = \Bitrix\Iblock\PropertyEnumerationTable::getList();
    $iblockPropsEnumArrs = $iblockPropsEnumObj->fetchAll();

    echo '<pre>';
    print_r($iblockPropsEnumArrs);
    echo '</pre>';

Получаем множественное свойство по `PROPERTY_ID` его свойства:

    \Bitrix\Main\Loader::includeModule('iblock');

    // Получение списка множественных свойств информационного блока
    $iblockPropsEnumObj = \Bitrix\Iblock\PropertyEnumerationTable::getList([
      'order' => array('SORT' => 'asc'),
      'filter' => ['PROPERTY_ID' => 23],
    ]);

    $iblockPropsEnumArrs = $iblockPropsEnumObj->fetchAll();

    echo '<pre>';
    print_r($iblockPropsEnumArrs);
    echo '</pre>';

Добавляем все множественные свойства в массив ENUM_LIST, этот массив потом можно внедрить в массив свойства и работать уже с массивом где есть значения свойства и значения множественного свойства:

    \Bitrix\Main\Loader::includeModule('iblock');

    // Получение списка множественных свойств информационного блока
    $iblockPropsEnumObj = \Bitrix\Iblock\PropertyEnumerationTable::getList([
      'order' => array('SORT' => 'asc'),
      'filter' => ['PROPERTY_ID' => 23],
    ]);

    while($iblockPropsEnumArr = $iblockPropsEnumObj->fetch()) {
      // В существующем массиве создаём ключ ENUM_LIST и в него добавляем массивы свойств по ID
      $iblockPropsEnumArrs['ENUM_LIST'][$iblockPropsEnumArr['ID']] = $iblockPropsEnumArr;
    }

    echo '<pre>';
    print_r($iblockPropsEnumArrs);
    echo '</pre>';
