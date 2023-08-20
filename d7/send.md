# Почтовые события

    // Old school
    CEvent::Send(
      "NEW_USER",
      "s1",
      array(
        "EMAIL" => "info@intervolga.ru",
        "USER_ID" => 42
      )
    );

    // D7
    use Bitrix\Main\Mail\Event;
    Event::send(array(
      "EVENT_NAME" => "NEW_USER",
      "LID" => "s1",
      "C_FIELDS" => array(
        "EMAIL" => "info@intervolga.ru",
        "USER_ID" => 42
      ),
    ));
