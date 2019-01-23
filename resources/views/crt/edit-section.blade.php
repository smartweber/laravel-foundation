<ul class="list-unstyled">
	@for ($i = $from; $i <= $to; $i++)
		<li>
			<div class="state 
				<? 
					$sectionInfo = $sectionsInfo[$i];
					if(!$sectionInfo['finish'])
						echo 'icheckbox_line-grey';
					else
					if($sectionInfo['approved'])
						echo 'icheckbox_line-green checked';
					else
						echo 'icheckbox_line-red checked'; 
				?>													
				">
				<div class="icheck_line-icon"></div>
					Section {!! $i !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					@if($sectionInfo['score'] == 0)
						<b><i>Not Started</i></b>
					@else
						<b>{!! $sectionInfo['score'] !!}%</b>
					@endif
			</div>
		</li>
	@endfor										
</ul>