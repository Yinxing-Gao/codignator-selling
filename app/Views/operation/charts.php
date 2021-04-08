<?php
// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал


?>
<div id="operation_charts">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center"></h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати нового ліда">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Поточний фінансовий тиждень</h3>
			<div style="display: none" id="chart_data_week"><?= $chart_data_week; ?></div>
			<div id="week_chart" style="width: 100%; height: 500px"></div>
		</div>

		<div class="col-md-12">
			<h3>Поточний місяць</h3>
			<div style="display: none" id="chart_data_month"><?= $chart_data_month; ?></div>
			<div id="month_chart" style="width: 100%; height: 500px"></div>
		</div>

		<div class="col-md-12">
			<h3>Поточний рік</h3>
			<div style="display: none" id="chart_data_year"><?= $chart_data_year; ?></div>
			<div id="year_chart" style="width: 100%; height: 500px"></div>
		</div>
	</div>
</div>



