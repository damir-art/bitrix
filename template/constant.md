# Константы шаблона
Функции и константы шаблона:

HEADER:
- <?php $APPLICATION->ShowTitle(); ?> - вывод заголовка страницы `title`,
- <?php $APPLICATION->ShowTitle(false); ?> - вывод заголовка статьи `h1`,
- <?$APPLICATION->ShowHead()?> - подключение стилей, скриптов и мета-тегов. Заменяет три следующие функции:
  - <? $APPLICATION->ShowCSS(); ?> - подключение CSS стилей сайта.
  - <? $APPLICATION->ShowHeadStrings() ?> - подключает JS скрипты и специальные стили.
  - <? $APPLICATION->ShowHeadScripts() ?> - подключение служебных
- <?$APPLICATION->ShowPanel();?> - подключение панели администратора для публичной части сайта.
- <? $APPLICATION->ShowMeta("keywords") ?> - вывод метатега keywords (не нужно если есть в структуре)
- <? $APPLICATION->ShowMeta("description") ?> - вывод метатега description (не нужно если есть в структуре)

CONSTANT:
- <?=SITE_SERVER_NAME?> - URL сервера,
- <?=LANGUAGE_ID?> - константу идентификатора языка из настроек сайта,
- <?= SITE_TEMPLATE_PATH?> - путь до шаблона сайта,
- `<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">` - подставляет константу кодировки сайта в мета.

STRING:
- `<?php $APPLICATION->AddHeadString("<meta name='viewport' content='width=device-width, initial-scale=1'>");` - произвольные мета теги, файлы, шрифты гугл и т.п.

CSS JS:
- <?php $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/style.css" ); - подключение стилей,
- <?php $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/script.js" ); - подключение скриптов,
