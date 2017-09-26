<?php echo '
	<div style="display: inline-block; margin-bottom: 1rem;">
		<form method="POST" class="form-edit">
			<input type="hidden" name="id" value="' . $descriptionId . '">
			<input type="text" name="edit_task" value="' . $descriptionEdit . '">
			<input type="submit" name="do_task" value="Сохранить">
		</form>
	</div>
';