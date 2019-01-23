@extends('app')

@section('content')
      <!-- START TOPBAR -->
      <div class='page-topbar '>
         <div class='logo-area'></div>
         <div class='quick-area'>
         </div>
         <div class='pull-right'>
            <ul class="info-menu right-links list-inline list-unstyled">
               <!--
                  <li class="profile">
                     Hello, DM Name
                  </li>
                  <li class="profile">
                     |
                  </li>
                  -->
               <li class="profile">
                  <a href="{{ url('/auth/logout') }}"><i class="fa fa-lock"></i> Logout </a>
               </li>
               <li class="profile">
               </li>
            </ul>
         </div>
      </div>
      <!-- END TOPBAR --> 
      <!-- START CONTAINER -->
      <div class="page-container row-fluid">
         <!-- START CONTENT -->
         <section id=" " class=" ">
            <section class="wrapper" style='margin-top:80px;display:inline-block;width:100%;padding:15px 0 0 15px;'>
               <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                  <div class="page-title">
                     <div class="pull-left">
                        <h1 class="title">Welcome {{ Auth::User()->type }} {{ Auth::User()->name }} {{ Auth::User()->lastname }}</h1>
                     </div>
                  </div>
               </div>
               <div class="clearfix"></div>      
				
				@yield('subcontent')
		
            </section>
         </section>
         
         <!-- END CONTENT -->
         <div class="chatapi-windows ">
         </div>		
      </div>
      <!-- END CONTAINER -->
@stop

@section('modals')
	<!-- General section box modal start -->
		<div class="modal" id="dashboard-modal" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				</div>
			</div>
		</div>
	<!-- modal end --> 
      <script>
      		var crtId = null;
			$(document).on("hidden.bs.modal", function (e) {
			    $(e.target).removeData("bs.modal").find(".modal-content").empty();
			    if(crtId != null)
			    {
			    	$.getJSON("{!! URL::to('crt') !!}/"+crtId, function(data, status){
				    	var dataRow = $("#tr_crt_"+crtId);
				    	var dataTable = $('#bk-table-1').DataTable();
				    	if(dataRow != null)
						    dataTable = $(dataRow.closest("table")).DataTable();
						    	
			    		if(dataTable.row('#tr_crt_'+crtId).length)
		     				dataTable.row('#tr_crt_'+crtId).remove().draw(false);
	     				if(data.view != "")
	     				{
		     				var tableId = "#bk-table-";
		     				switch(data.status){
		     					case 1:
			     					tableId = tableId + "1";
			     					break;
		     					case 2:
			     					tableId = tableId + "2";
			     					break;
		     					case 3:
			     					tableId = tableId + "2";
			     					break;
		     					case 4:
			     					tableId = tableId + "3";
			     					break;
		     				}
		     				$(tableId).DataTable().row.add($(data.view)).draw(true);
	     				}
			    		crtId = null;
			        });
			    }
			});
			$(document).on('shown.bs.modal, loaded.bs.modal', function () {
				var modal        = $(this),
			      backdrop     = $('.modal-backdrop'),
			      modalContent = modal.find('.modal-dialog'),
			      modalHeight  = function() {
			        return (modalContent.height() > $(window).height()) ? modalContent.height() : $(window).height();
			      };
			      backdrop.css({
			    	  height: modalHeight() + 60
			    	});
			});

			$(document).ready(function(){
				$(".decertify").click(function(e){
					e.preventDefault();

					var r = confirm("Are you sure you want to de-certify this CRT?");
					if (r != true)
						return;
					urlTo = '{{{ URL::to("crt/decertify/crtId") }}}';
					urlTo = urlTo.replace('crtId',e.target.id.split("_")[1]);
					$.ajax({
						url: urlTo,
						type: 'GET',
						dataType: 'json',
						success: function(data) {
							$('#bk-table-3').DataTable().row('#tr_crt_'+data.id).remove().draw(false);
							alert('CRT was successfully de-certified');
						}
					});

				});
			});
      </script>

@stop