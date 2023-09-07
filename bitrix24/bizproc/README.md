# Бизнес-процессы
D7: https://dev.1c-bitrix.ru/api_d7/bitrix/bizproc/index.php
Курс: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=57
Старое ядро: https://dev.1c-bitrix.ru/api_help/bizproc/index.php

- Таблица элемента смарт-процесса: https://dev.1c-bitrix.ru/api_d7/bitrix/crm/dynamic/lib/prototypeitem.php
- Работаем с элементами смарт-процесса: https://bx24devbook.website.yandexcloud.net/Modul_CRM/Smart_processy/Elementy.html
- Методы элементов: https://dev.1c-bitrix.ru/api_d7/bitrix/crm/item/index.php
- Примеры работы со смарт процессами: https://habr.com/ru/companies/otus/articles/740784/
- Курсы: https://vc.ru/u/1377978-eddu-agregator-onlayn-kursov/782548-35-luchshih-kursov-1c-bitriks-2023-besplatnye
- Таблицы смарт-процессов: b_crm_dynamic_type

Задача:
- Переходим к бизнес процессам: `CRM > Ещё > Настройки > Список смарт-процессов`
- ID смарт-процесса тестовых ресурсов 139
- В элементах ищем поле `Платформа предоставления` его символьный код `UF_PLATPRED`
- Ищем значения данного поля у элемента

Разное:
- Имя таблицы смарт процессов `b_bp_workflow_instance`

## Разное
Старый метод работы со смарт-процессами:

    Bitrix\Main\Loader::includeModule('bizproc'); 

    $result=Bitrix\Bizproc\WorkflowInstanceTable::getList([
      "filter"=>["ID"=>139],
      "select"=>["ID","STATE"]]);

    $rows = array();
    while($row = $result->fetch()) {
      $rows[] = $row;
    }
