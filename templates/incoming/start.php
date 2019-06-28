<div class="block">
	<h2>Bevételezés</h2>
	<button id="button_restart" class="button restart font-l" <?=js_spec('inc_start')?>> Újrakezdés</button>
	<form id="form_search_comp" class="search" action="exec/incoming.php" <?=js_spec('inc_comp')?> method="get">
		<span class="font-m">Vegyszer</span>
		<input type="search" id="q" class="font-m" required />
		<button type="submit" class="button submit fa-lg">
			<i class="fas fa-search"></i>
		</button>
	</form>
	<section id="incoming"></section>
</div>