# $_GET, $_POST, $_REQUEST
https://blog.budagov.ru/poluchenie-get-post-parametrov-na-d7/  
https://dev.1c-bitrix.ru/api_d7/bitrix/main/context/index.php  
https://dev.1c-bitrix.ru/api_d7/bitrix/main/httprequest/index.php  
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3511  

    // Old school
    $name = $_POST["name"];
    $email = htmlspecialchars($_GET["email"]);

    // D7
    use Bitrix\Main\Application;
    $request = Application::getInstance()->getContext()->getRequest();
    $requestPostList = $request -> getPostList() -> toArray(); // Список

    $name = $request->getPost("name"); // POST
    $email = htmlspecialchars($request->getQuery("email")); // GET

    // переменная класса для работы
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();

    $request->getQueryList()->toArray(); // массив get параметров
    $request->getPostList()->toArray(); // массив post параметров
    $request->get("param"); // один параметр get или post

    // файлы и куки
    $request->getFileList();
    $request->getCookieList();

    $server = \Bitrix\Main\Context::getCurrent()->getServer();
    $server->toArray(); // массив параметров $_SERVER
