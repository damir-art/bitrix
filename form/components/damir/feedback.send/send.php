<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Mail\Event;
$request = Application::getInstance()->getContext()->getRequest();

// Получаем данные из формы отправленные скриптом
// перед присвоением в переменную, проверяем есть ли данные
if (!empty($request->getPost('name')))  $name  = $request->getPost('name');
if (!empty($request->getPost('email'))) $email = $request->getPost('email');
if (!empty($request->getPost('text')))  $text  = $request->getPost('text');

// Проверка данных
// валидация почты
$OK = false;
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $OK = true;
} else {
  $OK = false;
  $result['email'] = 'Неверный адрес электронной почты';
}

// Отправка, сообщения отправки
if ($OK) {
  // Отправка почты (создай почтове событие SEND и его почтовый шаблон в Битрикс)
  Event::send(array(
    "EVENT_NAME" => "SEND",
    "LID" => "s1",
    "C_FIELDS" => array(
      "AUTHOR" => $name,
      "AUTHOR_EMAIL" => $email,
      "TEXT" => $text,
    ),
  ));
  // если отправка успешна
  $result['error'] = "";
  $result['success'] = 'Сообщение отправлено';
} else {
  $result['error'] = 'Сообщение не отправлено';
}

// echo '<pre>';
// print_r( $result );
// echo '</pre>';
