<div class="card">
<form action="exec/add.php" method="post" <?=js('submit', ['api', 'index'])?>>
<div class="block">
	<span class="float-left"><h3>API hozzáadása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="selector" value="api"/>
	<div class="block">
		<div class="float-left pad-m">
			<table class="form">
				<caption>Azonosítás</caption>
				<tr>
					<th>Név</th>
					<td><input type="text" id="name" required /></td>
				</tr>
				<tr>
					<th>Forma</th>
					<td><input type="text" id="form"/></td>
				</tr>
			</table>
		</div>
		<div class="float-left pad-m">
			<table class="form">
				<caption>Státusz</caption>
				<tr>
					<th>Értékelés állapota</th>
					<td><select id="eval">
						<option></option>
						<option value="Értékelni">Értékelni</option>
						<option value="Értékelni - Orphan">Értékelni - Orphan</option>
						<option class="green" value="Jelölt">Jelölt</option>
						<option class="red" value="Elutasítva - PK">Elutasítva - PK</option>
						<option class="red" value="Elutasítva - FizKém">Elutasítva - FizKém</option>
						<option value="Nincs potenciál">Nincs potenciál</option>
						<option value="Nincs elég adat">Nincs elég adat</option>
					</select></td>
				</tr>
				<tr>
					<th>Fejlesztési fázis</th>
					<td><select id="dev">
						<option></option>
						<option class="green" value="Pipeline-ban">Pipeline-ban</option>
						<option class="red" value="Formulázás nem sikerült">Formulázás nem sikerült</option>
						<option class="red" value="Project lezárva">Project lezárva</option>
					</select></td>
				</tr>
				<tr>
					<th>Piaci helyzet</th>
					<td><select id="market">
						<option></option>
						<option value="Piacon">Piacon</option>
						<option value="Preklinika">Preklinika</option>
						<option value="Fázis 1">Fázis 1</option>
						<option value="Fázis 2">Fázis 2</option>
						<option value="Fázis 3">Fázis 3</option>
						<option value="Visszavonva">Visszavonva</option>
						<option value="Bukott Fázis 1">Bukott Fázis 1</option>
						<option value="Bukott Fázis 2">Bukott Fázis 2</option>
						<option value="Bukott Fázis 3">Bukott Fázis 3</option>
						<option value="Nincs fejlesztés alatt">Nincs fejlesztés alatt</option>
					</select></td>
				</tr>
				<tr>
					<th>Szabadalom lejár</th>
					<td><select id="patent">
						<option></option>
						<option value="Generikus">Generikus</option>
						<?php for ($year = 2019; $year <= 2030; $year++): ?>
						<option value="<?=$year?>"><?=$year?></option>
						<?php endfor; ?>
					</select></td>
				</tr>
			</table>
		</div>
		<div class="float-left pad-m">
			<table class="form">
				<caption>Indikáció</caption>
				<tr>
					<th>Elsődleges</th>
					<td><input type="text" id="pri"/></td>
				</tr>
				<tr>
					<th>Másodlagos</th>
					<td><input type="text" id="sec"/></td>
				</tr>
			</table>
			<table class="form">
				<caption>Fizkém adatok</caption>
				<tr>
					<th>T<sub>olv</sub> [°C]</th>
					<td><input type="text" id="melt"/></td>
				</tr>
				<tr>
					<th>Víz old. [ug/ml]</th>
					<td><input type="text" id="water"/></td>
				</tr>
				<tr>
					<th>HCl old. [mg/ml]</th>
					<td><input type="text" id="hcl"/></td>
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
		</div>
	</div>
</form>
</div>