@extends('dashboard.dashboard')

@section('subcontent')
			<div class="col-lg-12">
				<section class="box ">
					<header class="panel_header">
						<h2 class="title pull-left">Users</h2>
						<div class="actions panel_actions pull-right">
							<i class="box_toggle fa fa-chevron-down"></i>
						</div>
						</header>
						<div class="content-body">
							<div class="bk_addnew">
								<a href="{{ URL::to('report') }}" target="_blank">
									Report
								</a>						
							</div>
							<br />						
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<table id="bk-table-1" class="table table-striped dt-responsive display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Name</th>
												<th>Lastname</th>
												<th>Email</th>
												<th>Type</th>
												<th>Login as</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Name</th>
												<th>Lastname</th>
												<th>Email</th>
												<th>Tyye</th>
												<th>Login as</th>
											</tr>
										</tfoot>
										<!-- BK DATA START HERE -->
										<tbody>
											@foreach ($users as $user)
												<tr>
													<td>{!! $user->name !!}</td>
													<td>{!! $user->lastname !!}</td>
													<td>{!! $user->email !!}</td>
													<td>{!! $user->type !!}</td>
													<td>
														<a href="{{ URL::to('loginas/'.$user->id) }}">
															<img src="{!! asset('assets/images/login-as.png') !!}" width=30px; />
														</a>
													</td>
												</tr>
                                    		@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</section>
				</div>
@stop