<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['rights', 'index'])?>>
<div class="block">
	<span class="float-left"><h3>Felhasználó módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<h4><?=$name?></h4>
	<input type="hidden" id="uid" value="<?=$user_id?>"/>
	<input type="hidden" id="selector" value="user"/>
	<table class="form">
		<tr>
			<th>Vegyszerleltár szint</th>
			<td><select id="chemical">
				<option value="0" <?=($chemical == 0) ? 'selected ':''?>>
					Vendég
				</option>
				<option value="1" <?=($chemical == 1) ? 'selected ':''?>>
					Felhasználó
				</option>
				<option value="2" <?=($chemical == 2) ? 'selected ':''?>>
					Admin
				</option>
				<option value="3" <?=($chemical == 3) ? 'selected ':''?>>
					Überadmin
				</option>
			</select></td>
		</tr>
		<tr>
			<th>API-leltár szint</th>
			<td><select id="api">
				<option value="0" <?=($api == 0) ? 'selected ':''?>>
					Vendég
				</option>
				<option value="1" <?=($api == 1) ? 'selected ':''?>>
					Felhasználó
				</option>
				<option value="2" <?=($api == 2) ? 'selected ':''?>>
					Admin
				</option>
				<option value="3" <?=($api == 3) ? 'selected ':''?>>
					Überadmin
				</option>
			</select></td>
		</tr>
		<tr>
			<th>Oldószerleltár szint</th>
			<td><select id="solvent">
				<option value="0" <?=($solvent == 0) ? 'selected ':''?>>
					Vendég
				</option>
				<option value="1" <?=($solvent == 1) ? 'selected ':''?>>
					Felhasználó
				</option>
				<option value="2" <?=($solvent == 2) ? 'selected ':''?>>
					Admin
				</option>
				<option value="3" <?=($solvent == 3) ? 'selected ':''?>>
					Überadmin
				</option>
			</select></td>
		</tr>
	</table>
</form>
</div>