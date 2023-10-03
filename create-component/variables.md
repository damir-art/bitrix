# Переменные в компоненте Битрикс
https://ut11-web.ru/faq-1c-bitrix/variables-available-in-the-component-2-0-bitrix/  
Методы класса CBitrixComponent: https://dev.1c-bitrix.ru/api_help/main/reference/cbitrixcomponent/index.php
Методы класса CBitrixComponentTemplate: https://dev.1c-bitrix.ru/api_help/main/reference/cbitrixcomponenttemplate/index.php

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

## result_modifier.php
- `$arParams` - параметры, чтение, изменение не затрагивает одноименный член компонента, но изменения тут отразятся на $arParams в файле template.php,
- `$arResult` - результат, чтение/изменение, затрагивает одноименный член класса компонента,
- `$APPLICATION`, `$USER`, `$DB` - как обычно, объявлять их как global избыточно,
- `$this` - ссылка на текущий шаблон (объект, описывающий шаблон, тип CBitrixComponentTemplate).

## template.php
- `$arParams` - параметры, чтение, изменение не затрагивает одноименный член компонента,
- `$arResult` - результат, чтение/изменение, затрагивает одноименный член класса компонента (ссылка на поле компонента),
- `$APPLICATION`, `$USER`, `$DB` - как обычно, объявлять их как global избыточно,
- `$this` - ссылка на текущий шаблон (объект, описывающий шаблон, тип CBitrixComponentTemplate),
- `$templateName` - имя шаблона компонента (например: .dеfault),
- `$templateFile` - путь к файлу шаблона от DOCUMENT_ROOT (напр. /bitrix/components/wexpert/iblock.list/templates/.default/template.php),
- `$templateFolder` - путь к папке с шаблоном от DOCUMENT_ROOT (напр. /bitrix/components/bitrix/iblock.list/templates/.default),
- `$componentPath` - путь к папке с компонентом от DOCUMENT_ROOT (напр. /bitrix/components/bitrix/iblock.list),
- `$component` - ссылка на текущий вызванный компонент (тип CBitrixComponent),
- `$templateData` - массив для записи, обратите внимание, таким образом можно передать данные из template.php в файл component_epilog.php, причем эти данные попадают в кеш, т.к. файл component_epilog.php исполняется на каждом хите.

## component_epilog.php
- `$arParams` - параметры, чтение, изменение не затрагивает одноименный член компонента,
- `$arResult` - результат, чтение/изменение не затрагивает одноименный член класса компонента,
- `$APPLICATION`, `$USER`, `$DB` - аналогично, эта троица доступна,
- `$componentPath` - путь к папке с компонентом от DOCUMENT_ROOT (напр. /bitrix/components/bitrix/iblock.list),
- `$component` - ссылка на $this, читай на строку ниже,
- `$this` - ссылка на текущий вызванный компонент (объект класса CBitrixComponent), можно использовать все методы класса CBitrixComponent,

Дополнительные, не явные в component_epilog.php:

- `$epilogFile` - путь к файлу component_epilog.php относительно DOCUMENT_ROOT,
- `$templateName` - имя шаблона компонента (например: .dеfault),
- `$templateFile` - путь к файлу шаблона от DOCUMENT_ROOT (напр. /bitrix/components/bitrix/iblock.list/templates/.default/template.php),
- `$templateFolder` - путь к папке с шаблоном от DOCUMENT_ROOT (напр. /bitrix/components/bitrix/iblock.list/templates/.default),
- `$templateData` - обратите внимание, таким образом можно передать данные из template.php в файл component_epilog.php, причем эти данные закешируются и будут доступны в component_epilog.php на каждом хите!

И в конце, небольшой пример: чтобы получить в result_modifier.php значение $templateFolder, необходимо воспользоваться методами текущего шаблона:

    $this->__component->__template->__folder;
    $this->GetFolder();
    $this->__folder;

Чтобы передать данные из вложенного в комплексный компонента, можно в компоненте-потомке обратиться к результирующему массиву родительского компонента:

    $this->__component->getParent()->arResult['CHILD_DATA'] = array(...);

## Как передать в script.js шаблона компонена путь к папке, шаблона
В файле шаблона, template.php, прописываем:

    <script>
    BX.message({
      TEMPLATE_PATH: '<?php echo $this->GetFolder(); ?>'
    });
    </script>

И в файле script.js можем этот путь получить:

    var folderPath = BX.message('TEMPLATE_PATH');

Так же информация в виде таблицы доступна в официальной документации Битрикса:
Курс Разработчик Bitrix Framework: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2499
