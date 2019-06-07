<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['api', 'index'])?>>
<div class="block">
	<span class="float-left"><h3>API módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<h4><?=$name?></h4>
	<input type="hidden" id="api_id" value="<?=$api_id?>"/>
	<input type="hidden" id="selector" value="api"/>
	<div class="block">
	<div class="float-left pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" value="<?=$name?>" required /></td>
			</tr>
			<tr>
				<th>Forma</th>
				<td><input type="text" id="form" value="<?=$form?>"/></td>
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
					<option value="Értékelni" <?=($eval == 'Értékelni') ? 'selected ':''?>>
						Értékelni
					</option>
					<option value="Értékelni - Orphan" <?=($eval == 'Értékelni - Orphan') ? 'selected ':''?>>
						Értékelni - Orphan
					</option>
					<option class="green" value="Jelölt" <?=($eval == 'Jelölt') ? 'selected ':''?>>
						Jelölt
					</option>
					<option class="red" value="Elutasítva - PK" <?=($eval == 'Elutasítva - PK') ? 'selected ':''?>>
						Elutasítva - PK
					</option>
					<option class="red" value="Elutasítva - FizKém" <?=($eval == 'Elutasítva - FizKém') ? 'selected ':''?>>
						Elutasítva - FizKém
					</option>
					<option value="Nincs potenciál" <?=($eval == 'Nincs potenciál') ? 'selected ':''?>>
						Nincs potenciál
					</option>
					<option value="Nincs elég adat" <?=($eval == 'Nincs elég adat') ? 'selected ':''?>>
						Nincs elég adat
					</option>
				</select></td>
			</tr>
			<tr>
				<th>Fejlesztési fázis</th>
				<td><select id="dev">
					<option></option>
					<option class="green" value="Pipeline-ban" <?=($dev == 'Pipeline-ban') ? 'selected ':''?>>
					Pipeline-ban
					</option>
					<option class="red" value="Formulázás nem sikerült" <?=($dev == 'Formulázás nem sikerült') ? 'selected ':''?>>
					Formulázás nem sikerült
					</option>
					<option class="red" value="Project lezárva" <?=($dev == 'Project lezárva') ? 'selected ':''?>>
					Project lezárva
					</option>
				</select></td>
			</tr>
			<tr>
				<th>Piaci helyzet</th>
				<td><select id="market">
					<option></option>
					<option value="Piacon" <?=($market == 'Piacon') ? 'selected ':''?>>
						Piacon
					</option>
					<option value="Preklinika" <?=($market == 'Preklinika') ? 'selected ':''?>>
						Preklinika
					</option>
					<option value="Fázis 1" <?=($market == 'Fázis 1') ? 'selected ':''?>>
						Fázis 1
					</option>
					<option value="Fázis 2" <?=($market == 'Fázis 2') ? 'selected ':''?>>
						Fázis 2
					</option>
					<option value="Fázis 3" <?=($market == 'Fázis 3') ? 'selected ':''?>>
						Fázis 3
					</option>
					<option value="Visszavonva" <?=($market == 'Visszavonva') ? 'selected ':''?>>
						Visszavonva
					</option>
					<option value="Bukott Fázis 1" <?=($market == 'Bukott Fázis 1') ? 'selected ':''?>>
						Bukott Fázis 1
					</option>
					<option value="Bukott Fázis 2" <?=($market == 'Bukott Fázis 2') ? 'selected ':''?>>
						Bukott Fázis 2
					</option>
					<option value="Bukott Fázis 3" <?=($market == 'Bukott Fázis 3') ? 'selected ':''?>>
						Bukott Fázis 3
					</option>
					<option value="Nincs fejlesztés alatt" <?=($market == 'Nincs fejlesztés alatt') ? 'selected ':''?>>
						Nincs fejlesztés alatt
					</option>
				</select></td>
			</tr>
			<tr>
				<th>Szabadalom lejár</th>
				<td><select id="patent">
					<option></option>
					<option value="Generikus" <?=($patent == 'Generikus') ? 'selected ':''?>>Generikus</option>
					<?php for ($year = 2019; $year <= 2030; $year++): ?>
					<option value="<?=$year?>" <?=($patent == $year) ? 'selected ':''?>><?=$year?></option>
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
				<td><input type="text" id="pri" value="<?=$pri?>"/></td>
			</tr>
			<tr>
				<th>Másodlagos</th>
				<td><input type="text" id="sec" value="<?=$sec?>"/></td>
			</tr>
		</table>
		<table class="form">
			<caption>Fizkém adatok</caption>
			<tr>
				<th>T<sub>olv</sub> [°C]</th>
				<td><input type="text" id="melt" value="<?=$melt?>"/></td>
			</tr>
			<tr>
				<th>Víz old. [ug/ml]</th>
				<td><input type="text" id="water" value="<?=$water?>"/></td>
			</tr>
			<tr>
				<th>HCl old. [mg/ml]</th>
				<td><input type="text" id="hcl" value="<?=$hcl?>"/></td>
			</tr>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td><textarea id="note" cols="30"><?=$note?></textarea></td>
			</tr>
		</table>
	</div>
	</div>
</form>
</div>