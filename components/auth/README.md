# Авторизация
Компонент авторизации. Авторизацию на Битрикс можно сделать 2-мя способами, через HTML или через компонент Битрикс.

## HTML
Создать ссылку и разместить в href код `/auth.php?register=yes`

## Компонент
Выводит форму авторизации.

    "REGISTER_URL" => "/auth/",
    "PROFILE_URL" => "/personal/profile/",

Код компонента и описание его параметров: https://dev.1c-bitrix.ru/user_help/components/sluzhebnie/user/system_auth_form.php

Расположение шаблона компонента: `\bitrix\templates\test\components\bitrix\system.auth.form\`

## Кастомизация шаблона
Изменяем надписи Войти, Регистрация в языковом шаблоне (Редактировать как PHP):

    /bitrix/templates/test/components/bitrix/system.auth.form/auth/lang/ru/template.php
