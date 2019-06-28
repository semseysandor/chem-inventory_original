<div class="block">
	<span class="float-left">
		<h2>Bevételezés</h2>
	</span>
<span class="float-right">
	<button class="button restart font-m" <?=js_spec('inc_start')?>> Újrakezdés</button>
</span>
</div>
<form class="search" action="exec/incoming.php" <?=js_spec('inc_comp')?> method="get">
	<h4>Vegyszer keresés</h4>
	<input type="search" id="q" class="font-m" placeholder="Név, rövidítés, CAS, stb.." required />
	<button type="submit" class="button submit fa-lg">
		<i class="fas fa-search"></i>
	</button>
</form>
<section id="incoming"></section>