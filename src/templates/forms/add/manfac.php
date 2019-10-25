<div class="card">
<form action="exec/add.php" method="post" <?=js('submit', ['manfac', 'index'])?>>
	<div class="block">
		<span class="float-left"><h3>Gyártó hozzáadása</h3></span>
		<span class="float-right"><?=button('erase_popup')?></span>
		<span class="float-right"><?=button('submit')?></span>
	</div>
	<input type="hidden" id="selector" value="manfac"/>
	<div class="block">
		<table class="form">
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" required /></td>
			</tr>
			<tr>
				<th>Gyakori</th>
				<td><input type="checkbox" id="is_freq" value="1"/></td>
			</tr>
		</table>
	</div>
</form>
</div>