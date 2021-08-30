	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post"  enctype="multipart/form-data">
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
								Ajout de documents  :
							</div>
						</div>
						<div class="row">
							<div class="col  bg-dark-input rounded pt-2">
								<div class="form-group text-right">
									<label class="btn btn-upload-grey btn-file text-center">
										<input type="file" name="files_doc[]" class='form-control-file' multiple id="files-doc">
										Sélectionner
									</label>
								</div>
								<div class="row mt-3">
									<div class="col" id="form-zone"></div>
								</div>
								<div class="row mt-3">
									<div class="col" id="warning-zone"></div>
								</div>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col text-right">
								<button class="btn btn-dark" name="add-doc">Ajouter</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>