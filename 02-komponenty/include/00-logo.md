# Логотип
Создаём включаемую область для изменения логотипа.

- Переходим на тестовую страницу
- Включаем режим правки
- Жмем по кнопке `Изменить страницу`
- Открываем визуальный редактор
- `Компоненты > Служебные > Включаемые области`
- Перетаскиваем или дважды кликаем по `Вставка включаемой области`
- Откроется настройка компонента
    - `Показывать включаемую область: из файла`
    - `Путь к файлу области: /include/logo.php`
- Сохранить
- Открываем редактор кода
- Копируем получившийся код

Код:

    <?
    $APPLICATION->IncludeComponent(
        "bitrix:main.include",            // имя компонента
        "",                               // имя шаблона компонента
        Array(                            // массив настроек
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/logo.php"
        ));
    ?>

- Жмём по кнопке `Отменить`, окна **Редактирование страницы**
- Открываем `header.php`, вместо кода логотипа вставляем код компонента
- Переходим на главную, `Добавить область`
- Изображение вставляется через визуальный редактор: `Изображение > Выбрать из структуры сайта`
- `Сохранить`
- В корне сайта появится файл с папкой: `/include/logo.php`
