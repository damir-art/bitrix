# form iblock
Код как у README.md, с дополнениями:

https://webmasterow.ru/blog/1s-bitriks/sokhranenie-dannykh-iz-formy-v-infoblok-bitriks/

Сохранение данных из формы в инфоблок Битрикс.  
Создаём инфоблок куда будем сохранять данные из формы.  

Свойства:
- Имя               - Строка     - NAME
- Электронная почта - Строка     - EMAIL
- Комментарий       - HTML/текст - TEXT
- Файлы             - Файл       - FILES (галочка множественный)

## test.php
В HTML разметке формы добавляем поле для файлов с атрибутом `multiple` (иначе не получиться добавить несколько файлов). Название поля в разметке идет с квадратными скобками `[]`, а в файле обработчике, без.

    <div class="form-block">
      <h1>Форма связи</h1>
      <form id="form" enctype="multipart/form-data">
        <input class="clean" type="text" name="name" placeholder="Имя">
        <input class="clean" type="email" name="email" placeholder="Email *">
        <textarea class="clean" rows="3" name="text" placeholder="Текст сообщения"></textarea>
        <input class="clean" type="file" name="file[]" multiple="multiple">
        <button name="send" type="submit">Отправить</button>
      </form>
    </div>

## send.php
- В самом верху страницы добавляем служебную часть пролога:
  - `require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");`
- значения полей после проверки присваиваем соответствующим свойствам инфоблока,
- прописываем обработку полученного массива файлов,
- сохраняем файлы и полученные id файла присваиваем свойству инфоблока.

Код который добавляем:

    <?php
    // Сохранение данных в инфоблок

    // подключаем модуль инфоблоков
    CModule::IncludeModule('iblock');
    // инициализируем
    $elem = new CIBlockElement;
    // создаем пустой массив и собираем в него поля
    $PROP = array();
    $PROP['NAME'] = $name;
    $PROP['EMAIL'] = $email;
    $PROP['TEXT'] = $text;

    $arF = array(); // пустой массив для сбора ид файлов
    $count = count($_FILES['file']['name']); // количество файлов
    // соханяем файлы и получаем ид
    for($i = 0; $i < $count; $i++) {
      $arIMAGE["name"] = $_FILES['file']['name'][$i];
      $arIMAGE["size"] = $_FILES['file']['size'][$i];
      $arIMAGE["tmp_name"] = $_FILES['file']['tmp_name'][$i];
      $arIMAGE["type"] = $_FILES['file']['type'][$i];
      $arIMAGE["MODULE_ID"] = "vote";
      $fid = CFile::SaveFile($arIMAGE, "vote");
      $arF[] = $fid;
    }
    // все ид файлов присваиваем свойству
    $PROP['FILES'] = $arF;

    // настройки
    $arLoadProductArray = Array(
      "MODIFIED_BY"    => 1,
      "IBLOCK_SECTION_ID" => false,    // элемент лежит в корне раздела
      "IBLOCK_ID"      => 8,           // Ид инфоблока
      "PROPERTY_VALUES"=> $PROP,       // массив со свойствами
      "NAME"           => $email,      // имя записи
      "ACTIVE"         => "Y",
    );

    // сохраняем
    $PRODUCT_ID = $elem->Add($arLoadProductArray);

Полный код:

    <?php
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

    // Получаем данные из формы отправленные скриптом
    // перед присвоением в переменную, проверяем есть ли данные
    if (!empty($_POST["name"]))  $name  = $_POST['name'];
    if (!empty($_POST["email"])) $email = $_POST['email'];
    if (!empty($_POST["text"]))  $text  = $_POST['text'];

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
      $OK = false;
      // Сохранение данных в инфоблок
      // подключаем модуль инфоблоков
      CModule::IncludeModule('iblock');
      // инициализируем
      $elem = new CIBlockElement;
      // создаем пустой массив и собираем в него поля
      $PROP = array();
      $PROP['NAME'] = $name;
      $PROP['EMAIL'] = $email;
      $PROP['TEXT'] = $text;

      $arF = array(); // пустой массив для сбора ид файлов
      $count = count($_FILES['file']['name']); // количество файлов
      // соханяем файлы и получаем ид
      for($i = 0; $i < $count; $i++) {
        $arIMAGE["name"] = $_FILES['file']['name'][$i];
        $arIMAGE["size"] = $_FILES['file']['size'][$i];
        $arIMAGE["tmp_name"] = $_FILES['file']['tmp_name'][$i];
        $arIMAGE["type"] = $_FILES['file']['type'][$i];
        $arIMAGE["MODULE_ID"] = "vote";
        $fid = CFile::SaveFile($arIMAGE, "vote");
        $arF[] = $fid;
      }
      // все ид файлов присваиваем свойству
      $PROP['FILES'] = $arF;

      // настройки
      $arLoadProductArray = Array(
        "MODIFIED_BY"    => 1,
        "IBLOCK_SECTION_ID" => false,    // элемент лежит в корне раздела
        "IBLOCK_ID"      => 8,           // Ид инфоблока
        "PROPERTY_VALUES"=> $PROP,       // массив со свойствами
        "NAME"           => $email,      // имя записи
        "ACTIVE"         => "Y",
      );
      // сохраняем
      $PRODUCT_ID = $elem->Add($arLoadProductArray);

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
