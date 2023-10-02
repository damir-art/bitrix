# Переменные в компоненте Битрикс
https://ut11-web.ru/faq-1c-bitrix/variables-available-in-the-component-2-0-bitrix/  
Методы класса CBitrixComponent: https://dev.1c-bitrix.ru/api_help/main/reference/cbitrixcomponent/index.php

Переменные Битрикс доступные в комопненте.

## component.php
Основные:
- `$arParams` - параметры, чтение/изменение, затрагивает одноименный член класса компонента,
- `$arResult` - результат, чтение/изменение, затрагивает одноименный член класса компонента,
- `$APPLICATION`, `$USER`, `$DB` - все доступны, можно не объявлять их как global в файле component.php,
- `$this` - ссылка на текущий вызванный компонент (объект класса `CBitrixComponent`), можно использовать все методы класса CBitrixComponent.

Дополнительные:
- `$componentPath` - путь к вызванному компоненту от DOCUMENT_ROOT (пример: /bitrix/components/bitrix/iblock.list),
- `$componentName` - имя вызванного компонента (например: bitrix:iblock.list),
- `$componentTemplate` - шаблон вызванного компонента (например: my_template).

Аналогичные значения, если компонент вызван в составе другого компонента, идут отсылки на родительский компонент:
- `$parentComponentPath`,
- `$parentComponentName`,
- `$parentComponentTemplate`.
