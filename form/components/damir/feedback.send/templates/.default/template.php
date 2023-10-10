<div class="form-block">
  <h1>Форма связи</h1>
  <form action="<?php echo $componentPath . '/send.php'; ?>" method="POST">
		<input class="clean" type="text" name="name" placeholder="Имя">
		<input class="clean" type="email" name="email" placeholder="Email *">
		<textarea class="clean" rows="3" name="text" placeholder="Текст сообщения"></textarea>
		<button type="submit">Отправить</button>
  </form>
</div>
