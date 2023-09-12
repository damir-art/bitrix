# Таблицы инфоблоков
Имена таблиц инфоблоков и названия полей: https://dev.1c-bitrix.ru/api_help/iblock/fields.php  
https://hmarketing.ru/blog/bitrix/tablitsy-baz-dannykh/  
https://cms-dev.ru/blog/bitrix/obshhie-svedeniya/razbiraemsya-s-tabliczami-v-bitriks.html

- b_iblock_type - таблица типов инфоблоков,
  - b_iblock_type_lang - языкозависимые параметры типов инфоблока,

- b_iblock - таблица инфоблоков `IblockTable`,
  - b_iblock_property - свойства инфоблоков,
  - b_iblock_property_enum - свойства инфоблоков enum,
  - b_iblock_site - к какому сайту принадлежит тот или иной инфоблок,

- b_iblock_section - разделы,
  - b_iblock_section_property - свойства разделов,
  - b_iblock_section_element - привязка элементов к разделам,
  - b_iblock_section_right - расширенный доступ к разделам,

- b_iblock_element - элементы,
  - b_iblock_fields - поля элементов,
  - b_iblock_element_property - значения cвойств элемента,
  - b_iblock_element_right - расширенный доступ к элементам,
  - b_iblock_element_lock - блокировка элементов (используется модулем бизнес-процессов),

- b_iblock_iproperty - данные SEO шаблонов разделов, элементов, изображений,
- b_iblock_sequence - генераторы последовательностей для свойства "счётчик",
- b_iblock_group - права доступа к инфоблокам, для груп пользователей (в стандартном режиме),
- b_iblock_right - права доступа к инфоблокам, для груп пользователей (в расширенном режиме).
