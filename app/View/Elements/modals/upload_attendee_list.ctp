<div id="uploadAttendeeList" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadAttendeeList" aria-hidden="true">
<div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">	
    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3>Upload Attendee List</h3>
	</div>
	<div class="modal-body">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <form id="attendeeUploadForm" action="/admin/jobs/importAttendeeList" method="post" enctype="multipart/form-data">
                        <label>
                            Upload Document (in csv format, table only)
                        </label>
                        <input type="file" name="csvfile" placeholder="Upload file here..." />
                        <p>
                        </p>
                        <input type="hidden" name='job_id' id='attendeeJobId' />
                        <input class="btn btn-info" type="submit" value="Upload" />
                    </form>
                </div>
            </div>
	</div>
	<div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
	
    </div>
</div>
</div>