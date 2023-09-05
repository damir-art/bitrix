# Локализация

    // Old school
    IncludeTemplateLangFile(__FILE__);
    echo GetMessage("INTERVOLGA_TIPS.TITLE");

    // D7
    use Bitrix\Main\Localization\Loc;

    Loc::loadMessages(__FILE__);
    echo Loc::getMessage("INTERVOLGA_TIPS.TITLE");
