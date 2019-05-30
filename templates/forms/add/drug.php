<div class="card">
<form action="exec/add.php" method="post" <?=js('submit', ['drug&aid='.$api_id, 'drug_'.$api_id])?>>
<div class="block">
	<span class="float-left"><h3>Gyógyszer hozzáadása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="api_id" value="<?=$api_id?>"/>
	<input type="hidden" id="selector" value="drug"/>
	<div class="flex-container">
	<div class="pad-m">
		<table class="form">
		<caption>Azonosítás</caption>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" required /></td>
			</tr>
			<tr>
				<th>Egyéb név</th>
				<td><input type="text" id="name_alt"/></td>
			</tr>
			<tr>
				<th>Kiszerelés</th>
				<td><input type="text" id="dosage"/></td>
			</tr>
		</table>
		<table class="form">
			<caption>Fizikai-kémiai adatok</caption>
			<tr>
				<th>Kristályosság</th>
				<td><input type="text" id="crystall"/></td>
			</tr>
			<tr>
				<th>Részecske méret</th>
				<td><input type="text" id="particle"/></td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
		<caption>Dozírozás</caption>
			<tr>
				<th>Dózis egység (szabad formára)</th>
				<td><input type="text" id="dose_free"/></td>
			</tr>
			<tr>
				<th>Dózis egység naponta</th>
				<td><input type="text" id="dose_day"/></td>
			</tr>
			<tr>
				<th>Bevétel</th>
				<td>
					<select id="admin">
					<option></option>
					<option value="Étellel">Étellel</option>
					<option value="Éhgyomorra">Éhgyomorra</option>
					<option value="Étellel vagy éhgyomorra">Étellel vagy éhgyomorra</option>
					</select>
				</td>
			</tr>
		</table>
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td><textarea id="note"></textarea></td>
			</tr>
		</table>
</form>
</div>