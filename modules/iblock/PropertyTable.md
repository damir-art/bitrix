# Свойства инфоблока
Выводим свойства инфоблока:
- свойства хранятся в таблице `b_iblock_property`,
- у каждого свойства есть поле `IBLOCK_ID` кторое указывает на `ID` инфоблока, которому принадлежит данное свойство,
- выведем все свойства инфоблока с `ID = 2`.

Выводим все войства инфоблока:

    \Bitrix\Main\Loader::includeModule('iblock');

    $iblockPropsObj = \Bitrix\Iblock\PropertyTable::getList([
      'filter' => ['IBLOCK_ID' => 2],
    ]);

    $iblockPropsArrs = $iblockPropsObj->fetchAll();

    echo '<pre>';
    print_r($iblockPropsArrs);
    echo '</pre>';

Пользовательские свойства хранятся в двух местах:
- `Настройки > Настройки продукта > Пользовательские поля`,
- `Магазин > Каталог* > Свойства товаров`.

Вместо `Каталог` может быть название каталога, например `Одежда`.

## Получаем свойство
Получаем свойство с названием `Бренд` из инфоблока с `ID = 2`:

    \Bitrix\Main\Loader::includeModule('iblock');

    $iblockPropsObj = \Bitrix\Iblock\PropertyTable::getList([
      'filter' => ['IBLOCK_ID' => 2, 'NAME' => 'Бренд']
    ]);

    $iblockPropsArrs = $iblockPropsObj->fetchAll();

    echo '<pre>';
    print_r($iblockPropsArrs);
    echo '</pre>';
