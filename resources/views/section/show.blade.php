<div class="col-lg-12">
	<section class="box ">
		<header class="panel_header">
			<h2 class="title pull-left">Select your assessment</h2>
			<div class="actions panel_actions pull-right">
				<i class="fa fa-times" data-dismiss="modal" aria-hidden="true"></i>
			</div>
		</header>
	</section>
	<div class="content-body">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<ul class="nav nav-tabs primary">
					@for ($i = 1; $i <= 7; $i++)
						@if(!Auth::User()->is('DM') || $i != 7)
							<li class="{{{ $section == $i?'active':'' }}}">
								<a href="{{{ URL::to('section/' . $crt->getId(). '/'. $i .'/edit') }}}" data-toggle="tabajax" data-target="#section-modal">
									{{{ $i }}}
								</a>
							</li>
						@endif
					@endfor
				</ul>
				<div class="tab-content primary">
					<div class="tab-pane fade in active" id="section-modal">
					</div>
				</div>
				<br>
					<div class="clearfix"></div>
				<br>				
			</div>
	</div>
</div>
<br>
<div class="clearfix"></div>
<br>
<script>
	$('[data-toggle="tabajax"]').click(function (e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');
	
	    //Add the selector of the element you want to fetch from the external page to the url (with a blank space in between)
	    $(targ).load(loadurl, function () {
	        $this.tab('show');
			var modal        = $('#dashboard-modal'),
		      backdrop     = $('.modal-backdrop'),
		      modalContent = modal.find('.modal-dialog'),
		      modalHeight  = function() {
		        return (modalContent.height() > $(window).height()) ? modalContent.height() : $(window).height();
		      };
		      backdrop.css({
		    	  height: modalHeight() + 60
		    	});	        
	    });
	
	    return false;
	});	

	$('li.active [data-toggle="tabajax"]').click();	
</script>