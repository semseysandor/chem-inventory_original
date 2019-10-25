<hr/>
<nav class="block">
	<button class="button menu font-l bold float-left" <?=js('menu', ['compound'])?>>
		<i class="fas fa-flask"></i> Vegyszerleltár
	</button>
	<button class="button menu font-l bold float-left" <?=js('menu', ['api'])?>>
		<i class="fas fa-atom"></i> API adatbázis
	</button>
	<button class="button menu font-l bold float-left" <?=js('menu', ['solvent'])?>>
		<i class="fas fa-oil-can"></i> Oldószerleltár
	</button>
	<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3 ): ?>
		<div class="button admin font-l bold dropdown float-right" onclick="dropDown('admin_drop')">
			<i class="fas fa-tools"></i> Admin
			<div id="admin_drop" class="drop-left card">
				<div class="button admin font-s"  <?=js_spec('manfac')?>>
					<i class="fas fa-industry"></i> Gyártók
				</div>
				<div class="button admin font-s" <?=js_spec('location')?>>
					<i class="fas fa-map-marker-alt"></i> Lokációk
				</div>
				<div class="button admin font-s" <?=js_spec('rights')?>>
					<i class="fas fa-user-cog"></i><br/>Jogok
				</div>
				<div class="button admin font-s" <?=js_spec('invent_start')?>>
					<i class="fas fa-warehouse"></i> Leltározás
				</div>
			</div>
		</div>
	<?php endif; ?>
</nav>