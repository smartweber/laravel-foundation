<div class="modal-header bk-main-title-{{{ $survey->color }}}">
    <h4 class="modal-title">{{{ $section }}}. {{{ $survey->title }}}</h4>
</div>
<div class="modal-body">
	<section class="box ">
		@foreach($survey->surveySubs as $surveySub)
			<div class="modal-header bk-main-title-{{{ $survey->color }}}">
				<h4 class="modal-title">{{{ $surveySub->subtitle }}}</h4>
			</div>
			<br />
			<div class="clearfix"></div>			
			@foreach($crt->filterAnswers($survey->id,$surveySub->id) as $answerIndex => $answer)
			<?php
				$disabled = "";
				if(!$answer->isEditable() || (Auth::User()->is('DM') && $section == 7) || ((Auth::User()->is('MFP') && $section != 7)))
					$disabled = "disabled"; 
			?>
				<div class="form-group">
		        	<div class="Bk-Assessment-title">
		            	<label class="form-label" for="field-1">{{{ $answerIndex+1 }}}. {{{ $answer->surveyItem->matter }}}</label>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<label class="form-label">Answer</label>
						<br />
						<ul class="list-unstyled">
							<li>
								{!! Form::radio($answer->id, $answer->surveyItem->type, $answer->value === $answer->surveyItem->type,  array('tabindex'=>1, 'fieldId' => 'value', $disabled) ) !!}
								<label class="iradio-label form-label" for="square-radio-1">Yes</label>
							</li>
							<li>
								{!! Form::radio($answer->id, 0, $answer->value === 0,  array('tabindex'=>1, 'fieldId' => 'value', $disabled) ) !!}
								<label class="iradio-label form-label" for="square-radio-1">No</label>
								{!! Form::hidden("auto_kill_".$answer->id, $answer->surveyItem->autoKill, ["id" => "auto_kill_".$answer->id]) !!}
							</li>
							<li>
								<label class="form-label" id="points_earned_{!! $answer->id !!}">
									<b>
										@if(isset($answer->value))
											Points Earned: {!! $answer->value !!}
										@endif
									</b>
								</label>
							</li>
							<li id="auto_kill_msg_{!! $answer->id !!}" style="display:none;">
								This is a required response and the CRT can not pass without selecting Yes.
							</li>
						</ul>
					</div>
					<div class="col-xs-6">
						<label class="form-label">Action Required</label>
						<ul class="list-unstyled">
							<li>
								{!! Form::checkbox($answer->id, 1, $answer->actionRequired, array('tabindex'=>3, 'fieldId' => 'actionRequired', $disabled) ) !!}
								<label class="icheck-label form-label" for="square-checkbox-2">Yes</label>
							</li>
						</ul>
					</div>
				</div>
				<br />	
				<div class="form-group">
					{!! Form::text($answer->id, $answer->comments, array($disabled, 'fieldId' => 'comments', 'cols'=>5,'class'=>'form-control autogrow','placeholder'=>'Comments','style','overflow: hidden; word-wrap: break-word; resize: horizontal; height: 54px;')) !!}
				</div>
				<br />
				<div class="clearfix"></div>
			@endforeach			
		@endforeach                                    
	</section>
</div>
<div class="modal-footer">
	<button class="btn btn-success" type="button" onclick="$('#dashboard-modal').modal('hide');">Save</button>
</div>
<script>
	$( ".modal-body select, .modal-body input" ).change(function() {
		crtId = {!! $crt->id !!};
		var url = "{{{ url('/answer') }}}"+'/'+$(this).attr('name');
		var field = $(this).attr('fieldId');
		var value =  $(this).val();
		if(field == 'actionRequired' && !$(this).is(':checked'))
			value = 0;
		url = url + '/' + field + '/' + value;
		$.get(url).fail(function() {
		    alert( "Error saving data" );
		  });
	});

	$('.modal-body input:radio').change(
		    function(){
				var answerId = $(this).attr('name');
				var value =  $(this).val();
				$("#points_earned_"+answerId+" b").html("Points Earned: "+value);

				if (value == 0 && $("#auto_kill_"+answerId).val() == 1) {
					$("#auto_kill_msg_"+answerId).show();
				}
				else {
					$("#auto_kill_msg_"+answerId).hide();
				}
		    }
		);

	$('.modal-body input:text').keypress(
		    function(e){
			    if(e.which == 13)
				    $(this).blur();
		    }
		);	
	
</script>