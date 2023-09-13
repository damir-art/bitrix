# $_GET, $_POST, $_REQUEST

    // Old school
    $name = $_POST["name"];
    $email = htmlspecialchars($_GET["email"]);

    // D7
    use Bitrix\Main\Application;
    $request = Application::getInstance()->getContext()->getRequest();

    $name = $request->getPost("name");
    $email = htmlspecialchars($request->getQuery("email"));
