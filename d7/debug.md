# Отладка

    // Old school
    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/bitrix/log-intervolga.txt");

    AddMessage2Log($_SERVER);
    echo "<pre>" . mydump($_SERVER) . "</pre>";

    // D7
    use Bitrix\Main\Diag\Debug;

    Debug::dumpToFile($_SERVER);
    // or
    Debug::writeToFile($_SERVER);

    Debug::dump($_SERVER);
