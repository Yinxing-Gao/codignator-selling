<div id="application_charts">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Графіки</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h3>1 місяць</h3>
			<div style="display: none" id="chart_data_month"><?= $chart_data_month; ?></div>
			<div id="month_chart" style="width: 100%; height: 500px"></div>
		</div>

		<div class="col-md-12">
			<h3>3 місяці</h3>
			<div style="display: none" id="chart_data_3_month"><?= $chart_data_3_month; ?></div>
			<div id="3_month_chart" style="width: 100%; height: 500px"></div>
		</div>
	</div>
</div>


