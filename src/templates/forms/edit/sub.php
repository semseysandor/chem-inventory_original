<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['location', 'index'])?>>
	<div class="block">
		<span class="float-left"><h3>Alhely módosítása</h3></span>
		<span class="float-right"><?=button('erase_popup')?></span>
		<span class="float-right"><?=button('submit')?></span>
	</div>
	<input type="hidden" id="selector" value="sub"/>
	<input type="hidden" id="sub_id" value="<?=$sub_id?>"/>
	<div class="block">
		<table class="form">
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" value="<?=$name?>" required /></td>
			</tr>
		</table>
	</div>
</form>
</div>