# API template.php

## Переменные доступные в `template.php`
- `$arParams` - параметры, чтение, изменение не затрагивает одноименный член компонента
- `$arResult` - результат, чтение/изменение, затрагивает одноименный член класса компонента (ссылка на поле компонента)
- `$APPLICATION, $USER, $DB` - как global можно не объявлять
- `$this` - ссылка на текущий шаблон (объект, описывающий шаблон, тип CBitrixComponentTemplate)
- `$templateName` - имя шаблона компонента (например: **.dеfault**)
- `$templateFile` - путь к файлу шаблона от корня (например: `/bitrix/components/bitrix/iblock.list/templates/.default/template.php`)
- `$templateFolder` - путь к папке с шаблоном от корня (например `/bitrix/components/bitrix/iblock.list/templates/.default`)
- `$componentPath` - путь к папке с компонентом от корня (например `/bitrix/components/bitrix/iblock.list`)
- `$component` - ссылка на текущий вызванный компонент (тип CBitrixComponent)
- `$templateData` - массив для записи, можно передавать данные из **template.php** в **component_epilog.php**, эти данные попадают в кеш, т.к. **component_epilog.php** исполняется на каждом хите.

`$arItem`, `$arFiles`
