# Form AJAX
Форма обратной связи с AJAX.

- Код:
  - форма обратной связи,
  - отправка данных с AJAX,
  - стандартные модальные окна Битрикс,
  - проверка капча,
- Стили CSS
- Создание почтового события
- Создание почтового шаблона

Форма выводится через включаемую область (разместите её на тестовой странице):

    $APPLICATION->IncludeFile(
      SITE_DIR."include/feedback_form.php",
      Array(),
      Array("MODE"=>"html")
    );

## feedback_form.php

    <?php
      if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
      $code = $APPLICATION->CaptchaGetCode();
    ?>
    <form action="" method="post" class="feedback_form">
      <div class="item">
        <label>Имя:</label>
        <input type="text" name="name" value="" required />
      </div>
      <div class="item">
        <label>E-mail:</label>
        <input type="text" name="email" value="" required />
      </div>
      <div class="item">
        <label>Текст сообщения:</label>
        <textarea name="message" required></textarea>
      </div>
      <div class="item">
        <label>Введите символы с картинки:</label>
        <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$code;?>" alt="CAPTCHA" width="120" class="captcha_img" /><br />
        <a href="#" rel="nofollow" class="update">обновить картинку</a><br />
        <input type="hidden" name="captcha_sid" value="<?=$code;?>" />
        <input type="text" name="captcha_word" value="" required/>
      </div><!-- captcha-holder -->
      <input type="submit" value="Отправить" />
    </form>
    <div class="schema"></div>
    <?php
      // Инициализируем jQuery и библиотеку для работы с модальными окнами
      CJSCore::Init(array("jquery", "popup"));
    ?>
    <script>
    $(document).ready(function() {
      // Обработчик клика, обновить изображение капчи
      $('.update').on('click', function() {
        $.ajax({
          url: '/include/send.php',
          type: 'post',
          data: 'get=captcha',
          success: function(data){
            $('.feedback_form .captcha_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
            $('.feedback_form input[name="captcha_sid"]').val(data);
          }
        });
        return false;
      });

      $('.feedback_form').on('submit', function() {
        var name = $('input[name="name"]').val();
        var email = $('input[name="email"]').val();
        var message = $('textarea[name="message"]').val();
        var captcha_word = $('input[name="captcha_word"]').val();
        var captcha_sid = $('input[name="captcha_sid"]').val();
        $.ajax({
          url: '/include/send.php',
          type: 'post',
          data: 'get=send&name='+name+'&email='+email+'&message='+message+'&captcha_word='+captcha_word+'&captcha_sid='+captcha_sid,
          success: function(data) {
            // При успешном получении данных через ajax появляется стандартное модальное окно
            var popup = new BX.CDialog({
              'title': 'Отправка формы',
              'content': data,
              'draggable': true,
              'resizable': false,
              'width':250,
              'height':200,
              'buttons': [BX.CDialog.btnClose]
            });
            popup.Show();
          }
        });
        return false;
      });
    });
    </script>

## send.php

    require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
    if( $_REQUEST['get'] == 'captcha' ) {
      echo $code = $APPLICATION->CaptchaGetCode();
    }
    if( $_REQUEST['get'] == 'send' ) {
      $error = '';
      if(!$APPLICATION->CaptchaCheckCode($_REQUEST['captcha_word'], $_REQUEST['captcha_sid'])) {
        $error .= "Рисунок с картинки введен неверно<br />";
      }
      if( $_REQUEST['name'] == '' ) {
        $error .= "Заполните поле \"имя\"<br />";
      }
      if( $_REQUEST['email']=='' ) {
        $error .= "Заполните поле \"email\"<br />";
      }
      if( $_REQUEST['message']=='' ) {
        $error .="Заполните текст сообщения<br />";
      }
      if( $error ) {
        echo "<span style='color:#ff0000;'>" . $error . "</span>";
      } else {
        $arEventFields = array(
          "NAME"    => $_REQUEST['name'],
          "EMAIL"   => $_REQUEST['email'],
          "MESSAGE" => $_REQUEST['message'],
        );
        if(CEvent::Send("FEEDBACK_SEND", array(SITE_ID), $arEventFields)) {
          echo "<span style='color:#0fcf00;'>Сообщение успешно отправлено!</span>";
        }
        else {
          echo "<span style='color:#ff0000;'>Ошибка почтового сервера!</span>";
        }
      }
    }

## style.css
Стили шаблона обычно помещают в /local/templates/имя_шиблона/template_styles.css

    .feedback_form{
      display:block;
      width:400px;
      border:solid 1px #ccc;
      border-radius:5px;
      padding:10px;
    }

    .feedback_form .item{
      margin-bottom:10px;
      width:100%;
      float:left;
    }

    .feedback_form .item label{
      display:block;
      width:150px;
      float:left;
    }

    .feedback_form .item input[type='text'], .feedback_form .item textarea{
      width:200px;
      float:right;
    }

    .feedback_form .item textarea{
      height:200px;
    }

## Почтовое событие
Создание почтового события.
`Админка > Настройки > Настройки продукта > Почтовые и СМС события > Типы событий > Добавить тип`:

    Тип события: FEEDBACK_SEND
    Вид события: Почтовое событие
    Название: Сообщение с формы обратной связи
    Сохранить

## Почтовый шаблон
Создание почтового шаблона.
`Админка > Настройки > Настройки продукта > Почтовые и СМС события > Почтовые шаблоны > Добавить шаблон`:

    Тип почтового события: Сообщение с формы обратной связи [FEEDBACK_SEND]
    Активен: Галочка
    Сайт: Галочка
    Язык: Russian
    От кого: #DEFAULT_EMAIL_FROM#
    Кому: mail@mail.ru
    Тема: Новое сообщение с сайта #SITE_NAME#
    Сообщение:
      Вам пришло новое сообщение из формы обратной связи:
      Имя: #NAME#
      E-mail: #EMAIL#
      Текст сообщения: #MESSAGE#
    Сохранить

Форма отправки сообщения готова.
