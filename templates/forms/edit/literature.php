<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['drug&aid='.$api_id, 'drug_'.$api_id])?>>
<div class="block">
	<span class="float-left"><h3>Irodalom módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="literature_id" value="<?=$literature_id?>"/>
	<input type="hidden" id="selector" value="literature"/>
	<div class="flex-container">
	<div class="pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Cím</th>
				<td><input type="text" id="title" value="<?=$title?>" required /></td>
			</tr>
			<tr>
				<th>Szerző</th>
				<td><input type="text" id="author" value="<?=$author?>" required /></td>
			</tr>
			<tr>
				<th>Év</th>
				<td><input type="text" id="year" value="<?=$year?>" required /></td>
			</tr>
			<tr>
				<th>Folyóirat</th>
				<td><input type="text" id="journal" value="<?=$journal?>" /></td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td><textarea id="note"><?=$note?></textarea></td>
			</tr>
		</table>
	</div>
	</div>
</form>
</div>