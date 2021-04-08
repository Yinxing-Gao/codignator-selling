<div id="leads_list">
	<input type="hidden" id="api_key" value="<?= $account->api_key; ?>"/>
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Фінансова модель</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 ">
			<div class="">
				<table class="table table-striped tablesorter">
					<thead class="thead-dark">
					<tr>
						<th></th>
						<th>Маржинальність</th>
						<th>02.21</th>
						<th>03.21</th>
						<th>04.21</th>
						<th>05.21</th>
						<th>06.21</th>
						<th>07.21</th>
						<th>08.21</th>
						<th>09.21</th>
						<th>10.21</th>
						<th>11.21</th>
						<th>12.21</th>
						<th>01.2</th>

					</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan="2">Продукт 1</td>
						<td colspan="2" >20%</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>
					<tr>
						<td><input type="number"</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>
					<tr>
						<td>Продукт 2</td>
						<td>20%</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>
					<tr>
						<td>Продукт 3</td>
						<td>20%</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>
					<tr>
						<td>Інші доходи</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="budgets_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>
	<h4 class="text-center">Додати бюджет</h4>
	<div class="budget_sidenav_content">

		<form>
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="">
						Назва*</label>
					<input class="form-control" type="text" name="name">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Стаття</label>
					<select class="form-control" name="article_id">
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Сума</label>
					<div class="input-group">
						<input class="form-control" type="text" name="amount">
						<select class="form-control" id="currency" name="currency" required="">
							<option value="UAH">₴</option>
							<!--							<option value="USD">$</option>-->
							<!--							<option value="EUR">€</option>-->
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Тип</label>
					<div class="input-group">
						<select class="form-control" name="type" required="">
							<option value="fixed costs">Постійні витрати</option>

						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Коментар</label>
					<textarea class="form-control" name="comment"></textarea>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_budget" type="submit">
						Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
