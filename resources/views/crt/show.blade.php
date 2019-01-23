<tr id="tr_crt_{{{ $crt->id }}}">
@if($crt->getStatus() == 1)		
	<td>
		<a href="{{ URL::to('crt/' . $crt->getId(). '/edit') }}" data-toggle="modal" data-target="#dashboard-modal">
			{{ $crt->name }} {{ $crt->lastname }}
		</a>
	</td>
	<td>
		@for ($i = 1; $i <= 7; $i++)
			@if(!Auth::User()->is('DM') || $i != 7)
				<a href="{{{ URL::to('section/' . $crt->getId(). '/'. $i) }}}" 
					data-toggle="modal" data-target="#dashboard-modal">
					<span class="circle-option 
						<? 
							$sectionInfo = $crt->sectionInfo($i);
							if(!$sectionInfo['finish'])
								echo 'circle-pending';
							else
							if($sectionInfo['approved'])
								echo 'circle-good';
							else
								echo 'circle-bad'; 
						?>
					">
						{{{ $i }}}
					</span>
				</a>
			@endif
		@endfor
	</td>
	<td>{{{ $crt->restNumber }}}</td>
	<td>{{{ $crt->getMFPName() }}}</td>
	<td><? echo isset($dfp[$crt->Dfp_id]) ? $dfp[$crt->Dfp_id] : "" ; ?></td>
	<td>{!! config('status.crt.'.$crt->getStatus()) !!}</td>
	<td> 
		<a href="javascript:;" onclick="deleteCrt({{{ $crt->id }}},'{{{ $crt->name }}} {{{ $crt->lastname }}}');">
			<span class="glyphicon glyphicon-trash icon-danger"></span>
		</a>
	</td>
@endif

@if($crt->getStatus() == 2 || $crt->getStatus() == 3)		
	<td>
		<a href="{{ URL::to('crt/' . $crt->getId(). '/edit') }}" data-toggle="modal" data-target="#dashboard-modal">
			{{ $crt->name }} {{ $crt->lastname }}
		</a>
	</td>
	<td>
		@for ($i = 1; $i <= 7; $i++)
			@if(!Auth::User()->is('DM') || $i != 7)
				<a href="{{{ URL::to('section/' . $crt->getId(). '/'. $i) }}}" 
					data-toggle="modal" data-target="#dashboard-modal">
					<span class="circle-option 
						<? 
							$sectionInfo = $crt->sectionInfo($i);
							if(!$sectionInfo['finish'])
								echo 'circle-pending';
							else
							if($sectionInfo['approved'])
								echo 'circle-good';
							else
								echo 'circle-bad'; 
						?>
					">
						{{{ $i }}}
					</span>
				</a>
			@endif
		@endfor
	</td>
	<td class="text-center">					 
		@if(isset($crt->precertified_at))
			<span class="glyphicon glyphicon-ok icon-success"></span>
		@else
			<span class="glyphicon glyphicon-remove icon-danger"></span>
		@endif
	</td>
	<td>
		@if(isset($crt->precertified_at))
			{{{ $crt->precertified_at->format('Y/m/d') }}}
		@else
		    -
		@endif
	</td>
	<td>
		@if(isset($crt->precertified_at))
			{{{ date_add(clone $crt->precertified_at,new DateInterval('P90D'))->format('Y/m/d') }}}
		@else
		    -
		@endif
	</td>
	<td>
		@if(isset($crt->precertified_at))
			{{{ date_diff(date_time_set(new DateTime(),0,0,0), date_add(clone $crt->precertified_at,new DateInterval('P90D')))->format('%R%a days') }}}
		@else
		    -
		@endif
	</td>				
	<td>{{{ $crt->restNumber }}}</td>
	<td>{{{ $crt->getMFPName() }}}</td>
	<td><? echo isset($dfp[$crt->Dfp_id]) ? $dfp[$crt->Dfp_id] : "" ; ?></td>
	<td>{!! config('status.crt.'.$crt->getStatus()) !!}</td>
	<td> 
		<a href="javascript:;" onclick="deleteCrt({{{ $crt->id }}},'{{{ $crt->name }}} {{{ $crt->lastname }}}');">
			<span class="glyphicon glyphicon-trash icon-danger"></span>
		</a>
	</td>
@endif
@if($crt->getStatus() == 4)		
	<td>
		<a href="{{ URL::to('crt/' . $crt->getId(). '/edit') }}" data-toggle="modal" data-target="#dashboard-modal">
			{{ $crt->name }} {{ $crt->lastname }}
		</a>
	</td>
	<td>{{{ $crt->restNumber }}}</td>
	<td>
		{{{ $crt->certified_at->format('Y/m/d') }}}
	</td>
	<td>
		{{{ date_add(clone $crt->certified_at,new DateInterval('P1Y'))->format('Y/m/d') }}}
	</td>
	<td>
		{{{ date_diff(date_time_set(new DateTime(),0,0,0), date_add(clone $crt->certified_at,new DateInterval('P1Y')))->format('%R%a days') }}}
	</td>
	<td>
		@for ($i = 1; $i <= 7; $i++)
			@if(!Auth::User()->is('DM') || $i != 7)
				<a href="{{{ URL::to('section/' . $crt->getId(). '/'. $i) }}}" 
					data-toggle="modal" data-target="#dashboard-modal">
					<span class="circle-option 
						<? 
							$sectionInfo = $crt->sectionInfo($i);
							if(!$sectionInfo['finish'])
								echo 'circle-pending';
							else
							if($sectionInfo['approved'])
								echo 'circle-good';
							else
								echo 'circle-bad'; 
						?>
					">
						{{{ $i }}}
					</span>
				</a>
			@endif
		@endfor
	</td>				
	<td>{{{ $crt->getMFPName() }}}</td>
	<td><? echo isset($dfp[$crt->Dfp_id]) ? $dfp[$crt->Dfp_id] : "" ; ?></td>
	<td>{!! config('status.crt.'.$crt->getStatus()) !!} (<a href="{{{ URL::to('crt/decertify/' . $crt->getId()) }}}" class="decertify" id="dc_{{{ $crt->getId() }}}">de-certify</a>)</td>
	<td> 
		<a href="javascript:;" onclick="deleteCrt({{{ $crt->id }}},'{{{ $crt->name }}} {{{ $crt->lastname }}}');">
			<span class="glyphicon glyphicon-trash icon-danger"></span>
		</a>
	</td>
@endif
</tr>
<script>
 	function deleteCrt(crtId, crtName)
 	{
 		var r = confirm("Are you sure you want to remove CRT: "+crtName);
 		if (r != true)
 			return false;
	 	urlTo = '{{ URL::to("crt/destroy/crtId") }}';
	 	urlTo = urlTo.replace('crtId', crtId);
 		$.ajax({
 		   url: urlTo,
 		   type: 'post',
 		   dataType: 'json',
 		   success: function(data) {
				$('#bk-table-1').DataTable().row('#tr_crt_'+data.id).remove().draw(false);
				$('#bk-table-2').DataTable().row('#tr_crt_'+data.id).remove().draw(false);
				$('#bk-table-3').DataTable().row('#tr_crt_'+data.id).remove().draw(false);
				alert('CRT was removed successfully');
 		   }
 		});
 	}
</script>