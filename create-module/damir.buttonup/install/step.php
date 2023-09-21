<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if( !check_bitrix_sessid() ) {
  return;
}

// Код отвечающий за вывод сообщения об ошибке версии
if( $errorException = $APPLICATION->GetException() ) {
  echo(CAdminMessage::ShowMessage($errorException->GetString()));
} else {
  echo(CAdminMessage::ShowNote(Loc::getMessage("DAMIR_BUTTONUP_STEP_BEFORE") . " " . Loc::getMessage("DAMIR_BUTTONUP_STEP_AFTER")));
}
?>

<!-- Код кнопки, позволяюща вернуться в список установленных решений -->
<form action="<? echo($APPLICATION->GetCurPage()); ?>">
  <input type="hidden" name="lang" value="<? echo(LANG); ?>" />
  <input type="submit" value="<? echo(Loc::getMessage("DAMIR_BUTTONUP_STEP_SUBMIT_BACK")); ?>">
</form>
