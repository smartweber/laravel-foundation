@extends('dashboard.dashboard')

@section('subcontent')
<div class="col-lg-12">
	<section class="box ">
		<header class="panel_header">
			<h2 class="title pull-left">CRT - Pending Approval by DM</h2>
			<div class="actions panel_actions pull-right">
				<i class="box_toggle fa fa-chevron-down"></i>
			</div>
		</header>
		<div class="content-body">
			<div class="bk_addnew">
				<a href="{{ URL::to('crt/create') }}" data-toggle="modal" data-target="#dashboard-modal">
					Add new
				</a>						
			</div>
			<br />
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<table id="bk-table-1" class="table table-striped dt-responsive display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>CRT Full Name</th>
								<th>Assessment</th>
								<th>Rest #</th>
								<th>MFP</th>
								<th>DFP</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>CRT Full Name</th>
								<th>Assessment</th>
								<th>Rest #</th>
								<th>MFP</th>
								<th>DFP</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<!-- BK DATA START HERE -->
						<tbody>
							@foreach ($crts as $crt)
								@if($crt->getStatus() == 1)
									@include('crt.show', ['crt' => $crt, 'dfp' => $dfp])
								@endif
                    		@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="col-lg-12">
	<section class="box ">
		<header class="panel_header">
			<h2 class="title pull-left">CRT's READY FOR PRE-CERTIFICATION APPROVAL</h2>
				<div class="actions panel_actions pull-right">
					<i class="box_toggle fa fa-chevron-down"></i>
				</div>
		</header>
		<div class="content-body">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<table id="bk-table-2" class="table table-striped dt-responsive display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>CRT Full Name</th>
								<th>Assessment</th>
								<th>Pre-Certified</th>
								<th>Pre-Certified Date</th>
								<th>Date Expires</th>
								<th>Days Left</th>												
								<th>Rest #</th>
								<th>MFP</th>
								<th>DFP</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>CRT Full Name</th>
								<th>Assessment</th>
								<th>Pre-Certified</th>
								<th>Pre-Certified Date</th>
								<th>Date Expires</th>
								<th>Days Left</th>												
								<th>Rest #</th>
								<th>MFP</th>
								<th>DFP</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<!-- BK DATA START HERE -->
						<tbody>
							@foreach ($crts as $crt)
								@if($crt->getStatus() == 2 || $crt->getStatus() == 3)
									@include('crt.show', ['crt' => $crt])
								@endif
                    		@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="col-lg-12">
	<section class="box ">
		<header class="panel_header">
			<h2 class="title pull-left">Certified - CRT's</h2>
				<div class="actions panel_actions pull-right">
					<i class="box_toggle fa fa-chevron-down"></i>
				</div>
		</header>
		<div class="content-body">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<table id="bk-table-3" class="table table-striped dt-responsive display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>CRT Full Name</th>
								<th>Rest #</th>
								<th>Certified date</th>
								<th>Date Expires</th>
								<th>Days Left</th>
								<th>Assessment</th>			
								<th>MFP</th>
								<th>DFP</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>CRT Full Name</th>
								<th>Rest #</th>
								<th>Certified date</th>
								<th>Date Expires</th>
								<th>Days Left</th>
								<th>Assessment</th>			
								<th>MFP</th>
								<th>DFP</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<!-- BK DATA START HERE -->
						<tbody>
							@foreach ($crts as $crt)
								@if($crt->getStatus() == 4)
									@include('crt.show', ['crt' => $crt])
								@endif
                    		@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>
@stop