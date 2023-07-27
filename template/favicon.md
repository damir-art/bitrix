# favicon
Располагается в корне сайта `/`.

Чтобы задать иконку конкретному шаблону нужно её поместить по адресу:

    /bitrix/templates/имя_шаблона/favicon.ico

В header.php шаблона прописать:

    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
