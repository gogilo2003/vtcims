<!-- Upload Student Photo -->
<div class="modal fade" data-backdrop="static" id="uploadPhotoDialog" tabindex="-1" role="dialog" aria-labelledby="StduentDetails">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="card">
				<form id="uploadPhotoPhotoForm" method="post" action="{{ route('admin-eschool-students-photo') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="card-header card-header-primary">
						<h4>Upload Student Photo</h4>
					</div>
					<div class="card-body">
						<div class="text-center">
							<p>Select an image of 4:5 ratio <br>(width:height)</p>
			                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
			                    <div class="fileinput-new thumbnail img-raised">
			                        <img src="{{ asset(config('admin.prefix_path').'/vendor/admin/img/person_8x10.png') }}" alt="..." class="img-fluid">
			                    </div>
			                    <div class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
			                    <div>
			                        <span class="btn btn-raised btn-round btn-default btn-file">
			                            <span class="fileinput-new">Select image</span>
			                            <span class="fileinput-exists">Change</span>
			                            <input type="file" name="photo" />
			                        </span>
			                        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i
			                                class="fa fa-times"></i> Remove</a>
			                    </div>
			                </div>
			            </div>
					</div>
					<div class="card-footer">
						<input type="hidden" name="id" id="uploadPhotoId">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-primary btn-round"><i class="material-icons">cloud_upload</i> Upload</button>
						<button type="reset" data-dismiss="modal" class="btn btn-danger btn-round"><i class="material-icons">cancel</i> Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
