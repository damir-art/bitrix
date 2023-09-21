# SectionTable
Выборка разделов инфоблока: https://estrin.pw/bitrix-d7-snippets/s/iblock-sectiontable/  
Работаем с разделами: https://tokmakov.msk.ru/blog/item/217

- выбираем все разделы конкретного инфоблока,
- выбираем все свойства раздела конкретного инфоблока,

## Выбираем разделы

    \Bitrix\Main\Loader::includeModule('iblock');

    // Получаем список инфоблоков, фильтруем по CODE
    $iblockObj = \Bitrix\Iblock\IblockTable::getList([
      'filter' => [ 'CODE' => 'clothes' ],
    ]);

    // Получаем инфоблок
    $iblockArr = $iblockObj->fetch();

    // Получаем ID инфоблока
    $iblockId = $iblockArr['ID'];

    // Получаем все каталоги инфоблока с ID = $iblockId, в том числе и вложенные
    $iblockSectionObj = \Bitrix\Iblock\SectionTable::getList([
      'select' => [ 'ID', 'NAME' ],
      'filter' => [ 'IBLOCK_ID' => $iblockId ],
    ]);

    $iblockSectionArrs = $iblockSectionObj->fetchAll();

    echo '<pre>';
    print_r($iblockSectionArrs);
    echo '</pre>';

## Выбираем свойства раздела
Свойства раздела хранятся в `Пользовательских полях` для доступа к ним, нужно воспользоваться классом: \Bitrix\Main\UserFieldTable::getList()

    // Получаем пользовательские свойства
    $iblockSectionPropertyObj = \Bitrix\Main\UserFieldTable::getList([
      //'filter' => [ 'FIELD_NAME' => 'UF_MANAGER' ]
    ]);

    //$iblockSectionPropertyArrs = $iblockSectionPropertyObj->fetch();
    $iblockSectionPropertyArrs = $iblockSectionPropertyObj->fetchAll();

    echo '<pre>';
    print_r($iblockSectionPropertyArrs);
    echo '</pre>';

Выборка пользовательских полей типа список:

    [USER_TYPE_ID] => string

## Удаляем разделы инфоблока
Первый способ, через `fetchObject()`:

    // Получаем все разделы инфоблока с ID = 4
    $iblockSectionObj = \Bitrix\Iblock\SectionTable::getList([
      'select' => [ 'ID', 'NAME' ],
      'filter' => [ 'IBLOCK_ID' => 4 ],
    ]);

    // Удаляем все разделы инфоблока с ID = 4
    while($iblockSection = $iblockSectionObj->fetchObject()) {
      $iblockSection->delete();
    }

Второй способ:

    Bitrix\Iblock\SectionTable::delete(1);

Третий способ через старое АПИ:

    $arSelect = array("ID");
    $arFilter = array("IBLOCK_ID" => $block_id);

    $res = CIBlockElement::GetList(array(), $arFilter, false, Array("nPageSize"=>$count), $arSelect);

    $i = 0;
    while ($r = $res->GetNext()) {
      $id = $r['ID'];
      CIBlockElement::Delete($id); // удаляем элемент
      CCatalogProduct::Delete($id); // удаляем торговое предложение
      deleteImage($id);  //  удаляем картинку
      $i++;
    }

    return $i;

Четвертый способ "Символьный":

Пятый способ запросы к БД и таблице разделов:

https://qna.habr.com/q/590618

При большом количестве разделов (30к+), Bitrix начинает вставлять палки в колеса как разработчику, так и пользователю, который будет работать с такими ИБ.
- Списки элементов ИБ будут безбожно тормозить в интерфейсе. На каждом хите будет добавляться фильтр по разделу.
- Редактировать их становится очень затруднительно, опять же из за долгой загрузки интерфейса и большого времени изменения.

Всё это из за того что разделы это NESTED SETS деревья. При добавлении/удалении раздела происходит вычисление LEFT_MARGIN и RIGHT_MARGIN для каждого раздела во всем инфоблоке.

Для добавления и обновления (CIBlockSection::Add, CIBlockSection::Update) есть параметр метода $bResort, который позволяет отключить этот перерасчёт в момент их выполнения.
Только после этого ОБЯЗАТЕЛЬНО надо выполнять CIBlockSection::Resort.

Это можно использовать при большом количестве операций Update и Add. Сначала выполняете все операции Update и Add с $bResort=false, а после них CIBlockSection::Resort

НО!! К сожалению, такой параметр для CIBlockSection::Delete не доступен. И вот тут начинается жесть.
Каждый вызов CIBlockSection::Delete, это:
пересчет границ NESTED SETS,
CIblockElement::GetList (поиск вложенных элементов)
CIblockElement::Delete (если в разделе были элементы),
Запрос к таблице которая хранит множественные привязки элемента к разделам (поиск значений и их удаление)
CIBlockSection::GetList (поиск вложенных разделов)
CIBlockSection::Delete (удаление вложенных разделов)
Переиндексирование поиска
Запросы к UF_* полям раздела (поиск значений и их удаление)
При заполненных SEO полях, запросы к таблицам которые их хранят (поиск значений и их удаление)
При расширенном управлении правами это еще запросы к таблицам которые хранят эти настройки (поиск значений и их удаление)

и это скорее всего не полный список, а только то что могу назвать по памяти....

Нельзя быстро удалить разделы стандартным API без написания своих запросов к БД которые проделают всё что описано выше. В идеале надо взять код стандартного CIBlockSection::Delete, внимательно его изучить и написать свой метод который будет с помощью прямых запросов делать тоже самое, но оптимально.

Если же вопрос стоит в том чтобы удалить эти разделы за ЛЮБОЕ количество времени, тогда можно написать страничку с пошаговым удалением разделов, через ajax запросы. Причем надо учитывать, что вначале разделы будут удаляться ОЧЕНЬ медленно и ajax запрос может отвалиться по таймауту, поэтому надо будет за один шаг удалять пару разделов. А ближе к границе в 10к разделов, за один шаг можно будет удалять уже большее количество.

## Примеры работы с разделами
Примеры работы с разделами и их пользовательскими свойствами:

    // Получаем раздел с ID 16
    $iblockSectionObj = \Bitrix\Iblock\SectionTable::getList([
      //'select' => [ 'ID', 'NAME' ],
      'filter' => [ 'IBLOCK_ID' => 2, 'ID' => 16 ],
    ]);

    // Получаем пользовательское поле UF_MANAGER для раздела с ID 16, инфоблока 2
    $userFieldsObj = \Bitrix\Main\UserFieldTable::getList([
      'filter' => [ 'ENTITY_ID' => 'IBLOCK_2_SECTION', 'FIELD_NAME' => 'UF_MANAGER' ]
    ]);

    while($userFieldArr = $userFieldsObj->fetch()) {
      echo '<pre>';
      print_r($userFieldArr);
      echo '<pre>';
    }

    // Получаем значение пользовательского поля UF_MANAGER для раздела с ID 16, инфоблока 2
    global $USER_FIELD_MANAGER;
    $res = $USER_FIELD_MANAGER->GetUserFieldValue('IBLOCK_2_SECTION', 'UF_MANAGER', 16 );
    echo $res;

## Обновляем пользовательское поле раздела

    // Обновляем значение пользовательского поля UF_MANAGER для раздела с ID 16, инфоблока 2
    $update = $USER_FIELD_MANAGER->Update('IBLOCK_2_SECTION', 16, array("UF_MANAGER" => 'МАНАГЕР 3'));

    // Добавляем разделы и заполняем пользовательские свойства
    $bs = new CIBlockSection;
    $arFields = Array(
      'IBLOCK_ID' => 4,
      'NAME' => 'Питер2',
      'UF_MANAGER' => 'Менеджер Питер2', // ID привязки тоже запишется
    );
    $ID = $bs->Add($arFields);

Обновляем на D7:

    SectionTable::update($arSect['ID'], array(
      'ACTIVE'      => 'N',
      'TIMESTAMP_X' => new DateTime
    ));

Обновляем привязку к сотрудникам (возможно и остальные привязки также можно обновить):

    $USER_FIELD_MANAGER->Update('IBLOCK_43_SECTION', 4614, array("UF_MANAGER" => 464));
