# Различный код

Проверяем является ли свойство раздела `phone` равным `Y`:

    $APPLICATION->GetDirProperty("phone")=="Y"

Является ли текущий раздел равным `/ru/catalog/phone/`

    $APPLICATION->GetCurDir()=="/ru/catalog/phone/"

Является ли текущий пользователь администратором:

    $USER->IsAdmin()

Является ли, для динамической страницы (например товарной старницы):

    preg_match("#/catalog/\?SECTION_ID=\d+&ELEMENT_ID=\d+#i",$_SERVER['REQUEST_URI'])

Для файлов оканчивающихся на `.php`

    substr($APPLICATION->GetCurPage(true), -4) == ".php"

## Как вывести произвольный контент в шаблоне сайта и компонента
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3855

## Шаблоны страниц
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3235

## Bootstrap в Битрикс
В Bootstrap рекомендуют кастомизировать стили в отдельном файле, обычно он называется `bootstrap-theme.css`. В Bitrix Framework он называется `template_style.css` - для основного шаблона и `style.css` в каждом отдельном компоненте.

## Верстка для мобилок
- `<meta name="viewport" content="width=device-width, initial-scale=1.0">`
- для ретины
    - иконки SVG
    - растр 2х и background-size
    - border: .5px solid #000

Подробнее об изображениях для ретины, с кодом: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2525

## Разное
- используйте кодировку UTF-8
- кодировка страниц и кодировка БД должны совпадать
