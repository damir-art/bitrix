# REST API получение данных
- REST API на JS
- внешние ресурсы
- получение данных портала

Подключение стандартной библиотеки, реализующей обёртку вокруг REST API, на языке JavaScript:

    <script src="//api.bitrix24.com/api/v1/"></script>

- упрощает вызов REST API в приложениях
- имеет доступ к интерфейсу портала
- вызов системных диалогов (выбор пользователей, выбор прав доступа)
- специальные функции (изменение размера окна, изменение заголовка страницы и т.п.)

`index.html`:
- можем в `index.html` вставлять ссылки на внешние ресурсы CDN: jQuery, bootstrap, гугл шрифты и т.п
- можем в `index.html` вставлять ссылки на внешние ресурсы физических файлов: css/style.css, js/custom.js, img/picture.png и т.п.

## Получение данных
Получение данных из портала Б24.
- подключили jQuery
- подключили Bootstrap
- подключили свой скрипт (файл кода приложения)
- подключили библиотеку REST API Битрикса
- подготовили HTML и JS-код болванку
- при создании приложения в Б24 отмечаем доступ к пользователям, а не CRM

### Болванка для HTML и JS

`index.html`

    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Первое приложение Б24</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
    </head>
    <body>
      <div id="app" class="container-fluid">
        <!-- Контейнер для вывода результата -->
        <div class="alert alert-success" role="alert" id="user-name"></div>
      </div>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
      <script src="js/application.js"></script> <!-- Файл кода приложения -->
      <script src="//api.bitrix24.com/api/v1/"></script>

      <script>
        $(document).ready(function() {
          // обработчик инициализации библиотеки битрикс
          BX24.init(function() {
            // обращаемся к методам нашего приложения, для взаимодествия с Б24
            // получение данных текущего пользователя
            app.displayCurrentUser("#user-name");
          });
        });
      </script>
    </body>
    </html>

`js/application.js`

    // our application constructor
    function application () {
    }

    // метод displayCurrentUser использует функцию callMethod в стандартной JS-обертке над REST API
    application.prototype.displayCurrentUser = function(selector) {
      BX24.callMethod(
        "user.current",    // название метода REST API
        {},                // массив параметров для этого метода (метод user.current не нуждается в параметрах)
        function(result) { // колбэк функция которая получит результат выполнения вызываемого метода REST API
          // при получении результата выводим имя и фамилию текущего пользователя
          $(selector).html("Hello " + result.data().NAME + " " + result.data().LAST_NAME + "!");
        }
      );
    }

    app = new application();

https://dev.1c-bitrix.ru/rest_help/users/index.php - пример работы с пользователями, там же NAME и LAST_NAME

## Получение полной выборки
Приведенный выше пример `BX24.callMethod()` возвращает простой набор данных. Рассмотрим примеры которые возвращают массивы данных (список пользователей, список сделок и т.д.).

Списочные методы REST API:
- crm.deal.list      - получение списка сделок
- crm.lead.list      - получение списка лидов
- entity.item.get    - получение списка элементов хранилища
- task.items.getlist - получение списка задач

Все они строятся по одному принципу, хотя в силу исторических причин, могут несколько отличатся по формату входных параметров.

Рассмотрим работу списочных методов, на примере `crm.deal.list`, который получает список сделок отобранных по некоторым условиям.

Важным нюансом является получение полной выборки, дело в том что все списочные методы возвращают максимум по 50 записей, с целью снижения нагрузки на портал. Чтобы получить всю выборку мы должны последовательно запрашивать по 50 записей `result.more()`.

Пример:

    BX24.callMethod(
      "crm.deal.list", // название метода REST API (получаем список сделок отобранных по некоторым условиям)
      { // массив параметров для этого метода
        order:  { "STAGE_ID": "ASC" }, // сортировка
        filter: { "ASSIGNED_BY_ID": 3 }, // выборка
        select: [ "ID", "TITLE", "STAGE_ID", "OPPORTUNITY" ] // перечень полей
      },
      function(result) { // колбэк функция которая получит результат выполнения вызываемого метода REST API
        // получение порции данных
        if(result.error()) {
          // обработка ошибки
          console.error(result.error());
        } else {
          // вывод порции данных
          console.log(result.data());
          // есть ещё?
          if(result.more()) {
            result.next();
          } else {
            // делаем что-то в итоге
          }
        }
      }
    );

Практическая задача: создадим методы для получения и вывода суммы закрытых сделок текущего пользователя, за текущий календарный месяц (доступы приложения "Пользователи", "CRM").

`index.html`

    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Первое приложение Б24</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
    </head>
    <body>

      <div id="app" class="container-fluid">

        <div class="alert alert-success" role="alert" id="user-name"></div>

        <div class="row">
          <div class="col">
            <table class="table table-responsive">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Название</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody id="deal-list">
                <tr>
                  <td colspan="3"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col">
            <div class="deal-sum"></div>
          </div>
        </div>
      </div>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
      <script src="js/application.js"></script> <!-- Файл кода приложения -->
      <script src="//api.bitrix24.com/api/v1/"></script>

      <script>
        $(document).ready(function() {
          // обработчик инициализации библиотеки битрикс
          BX24.init(function() {
            // обращаемся к методам нашего приложения, для взаимодествия с Б24
            // получение данных текущего пользователя
            app.displayCurrentUser("#user-name");
            // получение данных закрытых сделок пользователя (1 - его ID)
            app.displayUserClosedDeals(1);
          });
        });
      </script>

    </body>
    </html>


`js/application.js`

    // our application constructor
    function application () {
    }

    // метод displayCurrentUser использует функцию callMethod в стандартной JS-обертке над REST API
    application.prototype.displayCurrentUser = function(selector) {
      BX24.callMethod(
        "user.current",    // название метода REST API
        {},                // массив параметров для этого метода (метод user.current не нуждается в параметрах)
        function(result) { // колбэк функция которая получит результат выполнения вызываемого метода REST API
          // при получении результата выводим имя и фамилию текущего пользователя
          $(selector).html("Hello " + result.data().NAME + " " + result.data().LAST_NAME + "!");
        }
      );
    }

    // получение и вывод суммы закрытых сделок текущего пользователя, за текущий календарный месяц
    application.prototype.displayUserClosedDeals = function(idUser) {
      var dealSum = 0;
      var dealHTML = "";
      var curapp = this; // текущее приложение

      BX24.callMethod(
        "crm.deal.list", // название метода REST API (получаем список сделок)
        { // массив параметров для метода crm.deal.list
          order:  { "DATE_CREATE": "ASC" }, // сортировка
          filter: { "ASSIGNED_BY_ID": idUser, "CLOSED": "Y" }, // выборка
          select: [ "ID", "TITLE", "OPPORTUNITY" ] // перечень полей
        },
        function(result) { // колбэк функция которая получит результат выполнения вызываемого метода REST API
          // получение порции данных
          if(result.error()) {
            // обработка ошибки
            curapp.displayErrorMessage("Ошибка получения сделок. Повторите получение отчета позже.");
            console.error(result.error());
          } else {
            // вывод порции данных
            var data = result.data();
            // console.log(data());

            for (const indexDeal in data) {
              dealSum += parseFloat( data[indexDeal].OPPORTUNITY );
              dealHTML += '<tr><th scope="row">' + data[indexDeal].ID + '</th></td>' + data[indexDeal].TITLE + '</td><td>' + data[indexDeal].OPPORTUNITY + '</td></tr>';
            }

            // есть ещё?
            if(result.more()) {
              result.next();
            } else {
              // делаем что-то в итоге
              $('#deal-list').html(dealHTML);
              $('#deal-sum').html('<span class="volume">' + dealSum + '</span><br /> Общая сумма');
            }
          }
        }
      );
    }

    app = new application();
