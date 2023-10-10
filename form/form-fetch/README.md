# form fetch
https://webmasterow.ru/blog/javascript/prostaya-forma-otpravki-dannykh-na-js/

Отправка данных из формы на сервер и получение результатов отправки. Вместо jQuery и AJAX будем использовать fetch().

    // схема
    let url = 0;
    let options = 1;
    let promise = fetch(url, [options]);
    promise;

    url - путь для отправки запроса,
    options - дополнительные параметры: метод, заголовки и так далее,

В опциях укажем method равный POST и body тело запроса:

    const response = await fetch(url, {
      method: "POST",
      body: data
    });

## test.php

    <div class="form-block">
      <h1>Форма связи</h1>
      <form id="form">
        <input class="clean" type="text" name="name" placeholder="Имя">
        <input class="clean" type="email" name="email" placeholder="Email *">
        <textarea class="clean" rows="3" name="text" placeholder="Текст сообщения"></textarea>
        <button name="send" type="submit">Отправить</button>
      </form>
    </div>

## style.css

    h1 {
      font-size: 50px;
      line-height: 150%;
      margin-bottom: 20px;
      text-align: center;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    input, textarea {
      border: 1px black solid;
      margin-bottom: 20px;
      padding: 5px 15px;
      font-size: 18px;
      line-height: 150%;
      border-radius: 5px;
      max-width: 100%;
    }
    button {
      width: 150px;
      height: 45px;
      border-radius: 5px;
      font-size: 18px;
    }

## script.js
Функция очистки формы:

    // Очистка формы
    function cleanForm() {
      // загоняем все поля в псевдомассив
      let clean = document.querySelectorAll('.clean');
      // циклом проходимся по нему и обнуляем значение
      for (let item of clean) {
        item.value = '';
      }
    }

Функция JS, которую также можно применить для других обращений к серверу:

    // Функция fetch
    async function postData(url= '', data = {}) {
      const response = await fetch(url, {
        method: "POST",
        body: data
      });
      return await response.json();
    }

Отправка данных и обработка ответа на JS:

    // Отправка данных
    let form = document.getElementById('form'); // переменная с формой

    // При отправке формы любым способом
    form.addEventListener('submit', function (event) {
      // запрещаем стандартное действие
      event.preventDefault();
      // создаем объект новый
      let data = new FormData(form);
      // передаем в фукцию fetch данные и получаем результат
      postData('send.php', data).then((data) => {
        // обработка ответа от сервера
        console.log(data);
        if (data.error == '') {
          alert(data.success);
          cleanForm();
        } else if (data.email !== '') {
          alert(data.email);
        } else {
          alert(data.error);
        }
      })
    });

## send.php
Ссылка на send.php находится в файле script.js

Обработка данных на стороне сервера и возвращение ответа в формате JSON. Реализовали проверку на наличие информации в передаваемых значениях и стандартную валидацию адреса электронной почты (все проверки лучше осуществлять на сервере а не с помощью JS).

    // Получаем данные из формы отправленные скриптом
    // перед присвоением в переменную, проверяем есть ли данные
    if (!empty($_POST["name"]))  $name = $_POST['name'];
    if (!empty($_POST["email"])) $email = $_POST['email'];
    if (!empty($_POST["text"]))  $text = $_POST['text'];

    // Проверка данных
    // валидация почты
    $OK = false;
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $OK = true;
    } else {
      $OK = false;
      $result['email'] = 'Неверный адрес электронной почты';
    }

    // Отправка данных
    if ($OK) {
      // отправка
      // если отправка успешна
      $result['error'] = "";
      $result['success'] = 'Сообщение отправлено';
    } else {
      $result['error'] = 'Сообщение не отправлено';
    }

    // Возврат результата отправки
    header('Content-Type: application/json');
    echo json_encode($result);
