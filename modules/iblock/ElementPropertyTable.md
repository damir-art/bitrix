# Свойства элементов инфоблока
ElementPropertyTable - класс для работы с таблицей содержащей список свойств элементов `b_iblock_element_property`.

https://dev.1c-bitrix.ru/community/webdev/user/654351/blog/36908/ (здесь в примере в select пропущен IBLOCK_ID)

Получаем список свойств инфоблока по его ID:

    $res = \Bitrix\Iblock\ElementTable::getList(array(
        "select" => array("ID", "*"),
        "filter" => array("IBLOCK_ID" => 25, "ID" => 4584),
        "order"  => array("ID" => "ASC")
    ));

    while ($arItem = $res->fetch()) {
        $propRes = \Bitrix\Iblock\ElementPropertyTable::getList(array(
            "select" => array("ID", "*"),
            "filter" => array("IBLOCK_ELEMENT_ID" => $arItem["ID"],),
            "order"  => array("ID" => "ASC")
        ));
        while($prop = $propRes->Fetch())
        {
            $arItem["PROPERTIES"][$prop["IBLOCK_PROPERTY_ID"]] = $prop;
        }
        echo "<pre>".print_r($arItem, true)."</pre>";
    }