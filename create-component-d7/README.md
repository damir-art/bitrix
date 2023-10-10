# Создание компонента на D7
- https://know-online.com/post/bitrix-component
- Context: https://www.intervolga.ru/blog/projects/d7-analogi-lyubimykh-funktsiy-v-1s-bitriks/#section11
- https://hmarketing.ru/blog/bitrix/sozdanie-prostogo-komponenta-v-d7/

Путь создания компонента:
- `/local/components/namespace/complex.simple/`
  - namespace - простарнство имён
  - complex - имя комплексного компоеннта
  - simple - имя простого компонента
- `/local/components/simple/` - можно итак, хоть и не рекомендуется, чтобы не конфликтовать с именами других разработчиков

Обязательные папки и файлы компонента:
- /namespace/complex.simple/class.php - код комопнента
- /namespace/complex.simple/templates/.default/template.php - шаблон компонента

## class.php

    class ComplexSimple extends CBitrixComponent {
      public function executeComponent() {
        $this->IncludeComponentTemplate();
      }
    }

Разбор кода:
- `ComplexSimple` - имя класса должно совпадать с именем комопнента,
- `CBitrixComponent` - класс комопнента, методы доступны через $this: https://dev.1c-bitrix.ru/api_help/main/reference/cbitrixcomponent/index.php,
- `$this->IncludeComponentTemplate()` - метод компонента, инициализирует и подключает шаблон компонента,

Чтобы передать переменные из файла `class.php` в шаблон компонента `template.php`, надо в файле class.php добавить в массив `$arResult`, новые элементы:

    class ComplexSimple extends CBitrixComponent {
      public function executeComponent() {
        // Создание переменной в компоненте
        $this->arResult['VAR'] = 'Переменная'; // class.php
        $this->IncludeComponentTemplate();
      }
    }

# template.php
В шаблоне можно вывести значение переданной переменной или весь массив $arResult:

    // Использование переменной в шаблоне компонента
    echo $arResult['VAR']; // /templates/.default/template.php

    echo '<pre>';
    print_r($arResult);
    echo '</pre>';

## Вызов компонента
Вызов компонента компонента на странице:

    $APPLICATION->IncludeComponent( "namespace:complex.simple", "", Array( false ));
    $APPLICATION->IncludeComponent( "namespace:complex.simple" ); // Сокращенная запись
    $APPLICATION->IncludeComponent( "simple" );
