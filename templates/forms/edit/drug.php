<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['drug&aid='.$api_id, 'drug_'.$api_id])?>>
<div class="block">
	<span class="float-left"><h3>Gyógyszer módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<h4><?=$name?></h4>
	<input type="hidden" id="drug_id" value="<?=$drug_id?>"/>
	<input type="hidden" id="selector" value="drug"/>
	<div class="flex-container">
	<div class="pad-m">
		<table class="form">
		<caption>Azonosítás</caption>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" value="<?=$name?>" required /></td>
			</tr>
			<tr>
				<th>Egyéb név</th>
				<td><input type="text" id="name_alt" value="<?=$name_alt?>"/></td>
			</tr>
			<tr>
				<th>Kiszerelés</th>
				<td><input type="text" id="dosage" value="<?=$dosage?>"/></td>
			</tr>
		</table>
		<table class="form">
			<caption>Fizikai-kémiai adatok</caption>
			<tr>
				<th>Kristályosság</th>
				<td><input type="text" id="crystall" value="<?=$crystall?>"/></td>
			</tr>
			<tr>
				<th>Részecske méret</th>
				<td><input type="text" id="particle" value="<?=$particle?>"/></td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
		<caption>Dozírozás</caption>
			<tr>
				<th>Dózis egység<br/>(szabad formára)</th>
				<td><input type="text" id="dose_free" value="<?=$dose_free?>"/></td>
			</tr>
			<tr>
				<th>Dózis egység<br/>naponta</th>
				<td><input type="text" id="dose_day" value="<?=$dose_day?>"/></td>
			</tr>
			<tr>
				<th>Bevétel</th>
				<td><select id="admin">
					<option></option>
					<option value="Étellel" <?=($admin == 'Étellel') ? 'selected ':''?>>
						Étellel
					</option>
					<option value="Éhgyomorra" <?=($admin == 'Éhgyomorra') ? 'selected ':''?>>
						Éhgyomorra
					</option>
					<option value="Étellel vagy éhgyomorra" <?=($admin == 'Étellel vagy éhgyomorra') ? 'selected ':''?>>
						Étellel vagy éhgyomorra
					</option>
					</select></td>
			</tr>
		</table>
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