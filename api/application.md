# APPLICATION
https://dev.1c-bitrix.ru/api_help/main/reference/cmain/index.php

CMain - главный класс страницы. При создании каждой страницы создаётся глобальный объект этого класса с именем $APPLICATION.

Application - главный класс приложения Битрикс.

Пример использования:

    // Сначала прописываем пространство имён
    use Bitrix\Main\Application;

    // Далее обращаемся к свойствам и методам класса
    echo Application::getDocumentRoot() //  D:/OpenServer/domains/ural.loc

## Разное
Можно также еще записывать с обратным слешем в начале `use \Bitrix\Main\Application;`

    $APPLICATION->GetDirProperty("phone") == "Y"
    $APPLICATION->GetCurDir() == "/catalog/phone/"
