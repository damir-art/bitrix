# События
События в 1С-Битрикс.

- События размещают в файле `/bitrix/php_interface/init.php` или `/local/php_interface/init.php`
- Событие помещяют в функцию `AddEventHandler()` которая принимает следующие параметры:
    - ID модуля (например `ibloc`)
    - Имя события (например `OnAfterIBlockElementAdd`)
    - Обработчки события, массив в качестве элментов которых выступает класс и метод (например: `Array("ClassName", "MethodName")`)

## Пример
Событие сработает при добавлении элемента в инфоблок:

    AddEventHandler("ibloc", "OnAfterIBlockElementAdd", Array("ClassName", "MethodName"));

    class ClassName {
        function MethodName() {
            // Данный код сработает при добавлении элемента в инфоблок
        }
    }
