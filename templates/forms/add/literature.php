<div class="card">
<div class="block">
	<span class="float-left"><h3>Irodalom hozzáadása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<form action="exec/upload.php" method="post" <?=js('upload', ['drug&aid='.$api_id, 'drug_'.$api_id])?> enctype="multipart/form-data">
	<input type="hidden" id="api_id" value="<?=$api_id?>"/>
	<input type="hidden" id="selector" value="literature"/>
	<div class="block">
	<div class="float-left pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Cím</th>
				<td><input type="text" id="title" required /></td>
			</tr>
			<tr>
				<th>Szerző</th>
				<td><input type="text" id="author" required /></td>
			</tr>
			<tr>
				<th>Év</th>
				<td><input type="text" id="year" required /></td>
			</tr>
			<tr>
				<th>Folyóirat</th>
				<td><input type="text" id="journal"/></td>
			</tr>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td><textarea id="note" cols="30"></textarea></td>
			</tr>
		</table>
		<table class="form">
			<caption>Fájl</caption>
			<tr>
			<td>.pdf, .doc vagy .docx és max. 5MB</td>
			</tr>
			<tr>
				<td><input type="file" id="literature" required /></td>
			</tr>
		</table>
		<?=button('submit')?>
	</div>
	</div>
</form>
</div>