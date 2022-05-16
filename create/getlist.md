// CIBlockProperty::GetList - пользовательские свойства

CModule::IncludeModule("iblock"); // Подключаем модуль инфоблоков

$list_doctors = CIBlockElement::GetList (
    Array("NAME" => "ASC"),
    Array("IBLOCK_ID" => 20, "ACTIVE" => Y),
    false,
    false, // Array ("nTopCount" => 1), // false,
    Array()
);

while($list_doctors_fields = $list_doctors->GetNext()) {
    $arImagesPath[$list_doctors_fields["PREVIEW_PICTURE"]] = CFile::GetPath($list_doctors_fields["PREVIEW_PICTURE"]);

    $db_props = CIBlockElement::GetProperty(20, $list_doctors_fields["ID"], "sort", "asc", array());
    $PROPS = array();
    while($ar_props = $db_props->GetNext()) {
        $PROPS[$ar_props["CODE"]] = $ar_props["VALUE"];
    }

    // Вычисляем код специализации врача
    $code_specialization = CIBlockElement::GetProperty(
        20,
        $list_doctors_fields["ID"], // ID врача
        Array(),
        Array("CODE" => "SPECIALIZATION", )
    );

    if ($ob_code_specialization = $code_specialization->GetNext()) {
        $code_specialization_value = $ob_code_specialization['VALUE'];
    }

    // По коду специализации вычисляем название специализации
    $name_specialization = CIBlockElement::GetList(
        Array(),
        Array("IBLOCK_ID" => 21, "ID" => $code_specialization_value),
        false,
        false,
        Array("NAME")
    );

    $name_specialization_item = $name_specialization->GetNext();

    // print_r($list_doctors_fields);

    echo "<offer id=\"" . $list_doctors_fields["ID"] . "\">" . "\n";
    echo "\t" . "<name>" . $list_doctors_fields["NAME"] . "</name>" . "\n";
    echo "\t" . "<url>" . "https://www.fdoctor.ru/doctors/specialization/" . $list_doctors_fields["CODE"] . "/" . "</url>" . "\n";
    echo "\t" . "<oldprice>" . $PROPS["PRICE"] . "</oldprice>" . "\n";
    echo "\t" . "<currencyId>" . "RUR" . "</currencyId>" . "\n";
    echo "\t" . "<sales_notes>" . "Первичный прием" . "</sales_notes>" . "\n";
    echo "\t" . "<set-ids>" . $name_specialization_item["NAME"] . "</set-ids>" . "\n";
    echo "\t" . "<picture>" . "https://www.fdoctor.ru/" . $arImagesPath[$list_doctors_fields["PREVIEW_PICTURE"]] . "</picture>" . "\n";
    echo "</offer>" . "\n";
}
