<div class="card">
	<form action="exec/upload.php" method="post" <?=js('upload', ['drug&aid='.$api_id, 'drug_'.$api_id])?> enctype="multipart/form-data">
		<div class="block">
			<span class="float-left"><h3>Irodalom feltöltése</h3></span>
			<span class="float-right"><?=button('erase_popup')?></span>
			<span class="float-right"><?=button('submit')?></span>
		</div>
		<h5>.pdf, .doc vagy .docx és max. 5MB</h5>
		<input type="hidden" id="selector" value="new_literature"/>
		<input type="hidden" id="literature_id" value="<?=$literature_id?>"/>
		<input type="file" id="literature" required />
	</form>
</div>