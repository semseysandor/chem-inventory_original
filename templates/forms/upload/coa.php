<div class="card">
	<form action="exec/upload.php" method="post" <?=js('upload', ['batch&cid='.$comp_id, 'batch_'.$comp_id])?> enctype="multipart/form-data">
		<div class="block">
			<span class="float-left"><h3>CoA feltöltése</h3></span>
			<span class="float-right"><?=button('erase_popup')?></span>
			<span class="float-right"><?=button('submit')?></span>
		</div>
		<h4><?=$comp_name?> - <?=$manfac_name?> - <?=$name?> (<?=$lot?>)</h4>
		<h5>.pdf, .doc vagy .docx és max. 5MB</h5>
		<input type="hidden" id="selector" value="coa"/>
		<input type="hidden" id="batch_id" value="<?=$batch_id?>"/>
		<input type="file" id="coa" required />
	</form>
</div>