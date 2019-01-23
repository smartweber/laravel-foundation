<?
if(!Auth::User()->is('MFP'))
	$disabled = '';
else
	$disabled = 'disabled';

$sectionsInfo = array();
for($i = 1; $i <=7; $i++)
	$sectionsInfo[$i] = $crt->sectionInfo($i);
?>
<script type="text/javascript">
	function precertifyCRT(){
		var url = "{!! url('crt/precertify') !!}/{!! $crt->id !!}";
		$.post(url,{},
			function(data, status){
				crtId = data.id;
				alert("CRT was Pre-certified successfully");
				$('#dashboard-modal').modal('hide');
			});
	}
	function submitForm(){
		var $form = $( '#crtForm' ),
				data = $form.serialize(),
				url = $form.attr( "action" );
	    var posting = $.post( url, data );
		posting.done(function( data ) {
			if(data.fail) {
				var errorMsg = "Whoops! There were some problems with your input \r\n";
				$.each(data.errors, function( index, value ) {
					errorMsg = errorMsg+"   -"+value+"\r\n";
				});
				alert(errorMsg);
			}
			if(data.duplicate) {
				var errorMsg = "Whoops! \r\nThe CRT's email ("+ data.crtEmail +") is already associated to MFP: "+ data.mfpName +" and DM: "+ data.dmName +". This CRT can only be entered once and can't be added a second time";
				// $.each(data.errors, function( index, value ) {
				// 	errorMsg = errorMsg+"   -"+value+"\r\n";
				// });
				alert(errorMsg);
			}
			if(data.success) {
				crtId = data.id;
				if (data.refresh) {
					alert("CRT(s) have been successfully reassigned.")
					location.reload();
				} else {
					alert("CRT was saved successfully");
					$('#dashboard-modal').modal('hide');
				}
			} //success
		}); //done
	}
</script>
<script type="text/javascript">
	$("#crtForm").submit(function( event ) {
		event.preventDefault();
		var $form = $( this ),
				data = $form.serialize(),
				url = $form.attr( "action" );
		var posting = $.post( url, data );
		posting.done(function( data ) {
			if(data.fail) {
				var errorMsg = "Whoops! There were some problems with your input \r\n";
				$.each(data.errors, function( index, value ) {
					errorMsg = errorMsg+"   -"+value+"\r\n";
				});
				alert(errorMsg);
			}
			if(data.duplicate) {
				var errorMsg = "Whoops! \r\nThe CRT's email ("+ data.crtEmail +") is already associated to MFP: "+ data.mfpName +" and DM: "+ data.dmName +". This CRT can only be entered once and can't be added a second time";
				// $.each(data.errors, function( index, value ) {
				// 	errorMsg = errorMsg+"   -"+value+"\r\n";
				// });
				alert(errorMsg);
			}
			if(data.success) {
				crtId = data.id;
				if (data.refresh) {
					alert("CRT(s) have been successfully reassigned.")
					location.reload();
				} else {
					alert("CRT was saved successfully");
					$('#dashboard-modal').modal('hide');
				}
			} //success
		}); //done
	});
	$("#approveCRT").click(function(){
		var url = "{!! url('crt/approve') !!}/{!! $crt->id !!}";
		$.post(url,{},
				function(data, status){
					crtId = data.id;
					alert("Sent to MFP.");
					$('#dashboard-modal').modal('hide');
				});
	});
	$("#precertifyCRT").click(function(){
		var url = "{!! url('crt/precertify') !!}/{!! $crt->id !!}";
		$.post(url,{},
				function(data, status){
					crtId = data.id;
					alert("CRT was Pre-certified successfully");
					$('#dashboard-modal').modal('hide');
				});
	});
	$("#certifyCRT").click(function(){
		var url = "{!! url('crt/certify') !!}/{!! $crt->id !!}";
		$.post(url,{},
				function(data, status){
					crtId = data.id;
					alert("CRT was certified successfully");
					$('#dashboard-modal').modal('hide');
				});
	});

	$("#rest_select").change(function(){

		// make sure value is in list
		$("#listRest option").each(function(i){
			inList = false;
			if ($("#rest_select").val() == $(this).val()) {
				inList = true;
				return false;
			}
		});

		if (!inList) {
			alert ("The Restaurant ID you entered is not a valid selection. Please verify your input.");

			$("#rest_select").val("");
			$("#rest_select").focus();
			return false;
		}

		// Get MFP & DFP for restaurant
		var url = "{!! url('restaurants/getMfpDfp') !!}/" + $("#rest_select").val();
		var posting = $.get( url );
		posting.done(function( data ) {
			if(data.fail) {
				var errorMsg = "Whoops! There were some problems with your input \r\n";
				$.each(data.errors, function( index, value ) {
					errorMsg = errorMsg+"   -"+value+"\r\n";
				});
				alert(errorMsg);
			}
			if(data.success) {
				$("#mfp_select").val(data.mfp_id);
				$("#dfp_select").val(data.dfp_id);
				$('.selectpicker').selectpicker('refresh')
			} //success
		}); //done*/

		// Get all CRTs for selected restaurant
		var url = "{!! url('crt/getCrts') !!}/" + $("#rest_select").val();
		var posting = $.get( url );
		posting.done(function( data ) {
			if(data.fail) {
				var errorMsg = "Whoops! There were some problems with your input \r\n";
				$.each(data.errors, function( index, value ) {
					errorMsg = errorMsg+"   -"+value+"\r\n";
				});
				alert(errorMsg);
			}
			if(data.success) {
				// TODO: if we have existing crts...

				// Generate HTML table to go in container div
				table  = '<table class="table table-bordered table-condensed"><tbody>';
				for (crt of data.crts) {
					table += '<tr><td><label for="existing_crt_'+ crt.id +'">'+
							 '<input type="checkbox" value="'+ crt.id +'" ' +
							 'name="existing_crt[]" id="existing_crt_'+ crt.id +'" />' + crt.name +
							 '</label></td></tr>';
				}
				table += '</tbody></table>';

				// Update container div with informaiton
				$('#existing-crts').html(table);

			} //success
		}); //done*/
	})

	$(document).ready(function () {
		$('.selectpicker').selectpicker({
			liveSearch: true,
			maxOptions: 1
		});

		$('#crt-tabs a').click(function (e) {
			var disableExistingCrtTab = <?php echo $editMode ? 'true' : 'false'; ?>;

			if (disableExistingCrtTab) {
				if ($(this).attr('href').split('-')[1] == 'exist') {
					alert("Please use the \"Add new\" link at the top left to select existing CRTs.");
				}
				return false;
			}

			e.preventDefault()
			$(this).tab('show')

			$('#active-tab').val($("#crt-tabs li.active a").attr('href').split('-')[1]);
		});

	});

</script>
{!! Form::model($crt, array('url' => $action, 'id' => 'crtForm')) !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">CRT Profile</h4>
</div>
<div class="modal-body">
	<section class="box ">
		<div class="content-bodyx">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="input-group">
						If your restaurant does not appear in the list, it may not meet the CRT minimum requirements. Please contact your MFP for more information.
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon">Restaurant ID</span>
						{!! Form::text('restNumber', null, array('class' => 'form-control m-bot15', 'list' => 'listRest', 'id' => 'rest_select', $disabled)) !!}
						<datalist id=listRest>
							@foreach(/*config('restaurants')*/ $rests as $value)
								<option value="{!! $value !!}"></option>
							@endforeach
						</datalist>
					</div>
					<br />
					<div class="input-group search-pulldown">
										<span class="input-group-addon">
											MFP
										</span>
						{!! Form::select('mfps_id', $mfps, $crt->getMFPId(), array('class'=>'form-control m-bot15 selectpicker show-menu-arrow', 'id'=>'mfp_select')) !!}
					</div>
					<br />
					<div class="input-group search-pulldown">
										<span class="input-group-addon">
											DFP
										</span>
						{!! Form::select('dfps_id', $dfps, $crt->Dfp_id, array('class'=>'form-control m-bot15 selectpicker show-menu-arrow', 'id'=>'dfp_select')) !!}
					</div>


					<hr>

					<ul class="nav nav-tabs" role="tablist" id="crt-tabs">
						<li role="presentation" class="active"><a href="#tab-new-crt" role="tab" aria-controls="tab-new-crt" data-toggle="tab">Add New</a></li>
						<li role="presentation"><a href="#tab-exist-crt" role="tab" aria-controls="tab-exist-crt" data-toggle="tab">Select Existing</a></li>
					</ul>

					<input type="hidden" name="active-tab" id="active-tab" value="new" />

					<div class="tab-content">

						<div role="tabpanel" class="tab-pane fade" id="tab-exist-crt">
							<div class="panel panel-default">
								<div class="panel-body" style="height:150px; max-height:150px; overflow-y: scroll;" id="existing-crts">
									Select a Restaurant Above
								</div>
							</div>
						</div>

						<div class="tabpanel" class="tab-pane fade active" id="tab-new-crt">
							<div class="input-group">
												<span class="input-group-addon">
													<span class="arrow"></span>
													<i class="fa fa-user"></i>
												</span>
								{!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'First Name', $disabled)) !!}
							</div>
							<br />
							<div class="input-group">
												<span class="input-group-addon">
													<span class="arrow"></span>
													<i class="fa fa-user"></i>
												</span>
								{!! Form::text('lastname', null, array('class' => 'form-control', 'placeholder' => 'Last Name', $disabled)) !!}
							</div>
							<br>
							<div class="input-group">
												<span class="input-group-addon">
													<span class="arrow"></span>
													<i class="fa fa-envelope"></i>
												</span>
								{!! Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Email', $disabled)) !!}
							</div>
						</div>
					</div>

					<hr>
					<div class="col-md-6">
						@include('crt.edit-section', ['sectionsInfo' => $sectionsInfo, 'from' => 1, 'to' => '3'])
					</div>
					<div class="col-md-6">
						@include('crt.edit-section', ['sectionsInfo' => $sectionsInfo, 'from' => 4, 'to' => '6'])
					</div>
					@if(Auth::User()->is('DM'))
						<div class="clearfix"></div>
						<hr>
						@if(isset($crt->userMFP) && $sectionsInfo[1]['approved'] && $sectionsInfo[2]['approved'] && $sectionsInfo[3]['approved'] && $sectionsInfo[4]['approved'] && $sectionsInfo[5]['approved'] && $sectionsInfo[6]['approved'])
							@if($crt->getStatus() == 1)
								<b>
									Submit {!! $crt->name !!} {!! $crt->lastname !!}
									to  {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!}
									for pre-certification approval.
								</b>
								<div class="col-xs-12">
									<button type="button" class="btn btn-primary  pull-right" id="approveCRT">Send</button>
								</div>
								<div class="clearfix"></div>
							@else
								<b>
									Submitted by {!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!}
									to {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!}:
									{{{ $crt->approved_at->format('Y/m/d') }}}
								</b>
							@endif
						@else
							<b>
								Complete all the sections and select an MFP to submit the CRT.
							</b>
						@endif
					@endif
					@if(Auth::User()->is('MFP'))
						<hr>
						<div class="clearfix"></div>
						<div class="col-md-6">
							@include('crt.edit-section', ['sectionsInfo' => $sectionsInfo, 'from' => 7, 'to' => '7'])
						</div>
						<div class="clearfix"></div>
						<hr>
						@if($crt->getStatus() == 1)
							<b>
								Awaiting {!! $crt->userDM->name !!} {!! $crt->userDM->lastname !!} to finish approval of {!! $crt->name !!} {!! $crt->lastname !!}
							</b>
						@elseif($crt->getStatus() == 2)
							<b>
								Pre-Certify {!! $crt->name !!} {!! $crt->lastname !!}
							</b>
							<div class="col-xs-12">
								<button type="button" class="btn btn-primary  pull-right" onclick="precertifyCRT()">Pre-Certify</button>
							</div>
							<div class="clearfix"></div>
						@elseif($crt->getStatus() == 3)
							@if($sectionsInfo[7]['approved'])
								<b>
									Final Certification for {!! $crt->name !!} {!! $crt->lastname !!}
								</b>
								<div class="col-xs-12">
									<button type="button" class="btn btn-primary  pull-right" id="certifyCRT">Certify</button>
								</div>
							@else
								<b>
									Complete section 7 to certify the CRT {!! $crt->name !!} {!! $crt->lastname !!}
								</b>
							@endif
							<div class="clearfix"></div>
						@elseif($crt->getStatus() == 4)
							<b>
								Certified by {!! $crt->userMFP->name !!} {!! $crt->userMFP->lastname !!}:
								{{{ $crt->certified_at->format('Y/m/d') }}}
							</b>
						@endif
					@endif
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal-footer">
<!--{!! Form::submit('Save', array('class' => 'btn btn-success', 'id'=> 'save_btn2')) !!} -->
	<button onclick="submitForm()" class="btn btn-success" type="button">Save</button>
	<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
{!! Form::close() !!}

<style>
	.input-group.search-pulldown, .input-group.search-pulldown .form-control
	{
		z-index: 10;
	}
	.bootstrap-select.btn-group .btn .caret {
		left: 12px;
	}
	.bootstrap-select.btn-group .btn .filter-option {
		text-align: right;
	}
</style>