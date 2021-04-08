<div class="row title_div">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<h3 class="text-center">Завантаження Excel</h3>
	</div>
	<div class="col-md-3 title_div_icons">
		<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
		   data-title="Відео інструкція">
			<img src="<?= base_url() ?>/icons/fineko/video.svg"/>
		</a>
	</div>
</div>

<p>Тут можна заповнити дані по вашій компанії, завантажуючи їх в вигляді таблиці Excel</p>


<div class="js-ui-accordion">

	<h3>1. Список співробітників</h3>
	<div>
		З цієї таблиці сформуються також посади, які використовуються і департаменти, до котрих вони відносяться
		<table id="users_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>name</b> - ім'я</li>
						<li><b>surname</b> - прізвище</li>
						<li><b>email</b> - email</li>
						<li><b>phone</b> - телефон ( формат +380961111111 )</li>
						<li><b>language</b> - мова ( ua - українська, ru - російська )</li>
						<li><b>profession</b> - назва посади</li>
						<li><b>department</b> - назва департаменту</li>
						<li><b>salary_amount</b> - ставка сіпвробітника без процентів</li>
						<li><b>salary_currency</b> - валюта ставки ( UAH, USD, RUB )</li>
						<li><b>work_days</b> - кількість робочих днів в тижні за які нараховується ставка</li>
					</ul>
				</td>
				<td data-label="Шаблон"><a href="/uploads/excel_templates/users.xlsx">Шаблон</a></td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3>2. Список контрагентів</h3>
	<div>
		<table id="contractor_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>name</b> - повне ім'я або назва</li>
						<li><b>сontractor_type</b> - тип контрагента ( employee - співробітник, provider - поставщик,
							client - клиент, contractor - підрядник )
						</li>
						<li><b>adress</b> - прізвище</li>
						<li><b>email</b> - email</li>
						<li><b>phone</b> - телефон ( формат +380961111111 )</li>
					</ul>
				</td>
				<td data-label="Шаблон"><a href="/uploads/excel_templates/contractors.xlsx">Шаблон</a></td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3>3. Статті витрат і доходів</h3>
	<div>
		<table id="contractor_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>department</b> - назва департаменту</li>
						<li><b>amount</b> - сума</li>
						<li><b>currency</b> - валюта ( UAH - гривня, RUB - рубль, USD - долар, EUR - євро</li>
						<li><b>type</b> - тип операції ( 1 - прихід, 2 - витрата )</li>
						<li><b>contractor</b> - повне ім'я або назва контрагента</li>
						<li><b>comment</b> - коментар</li>
						<li><b>date</b> - день дати ( 15 - 15 числа кожного місяця, 30 - 30 - го числа кожного місяця)
						</li>
						<li><b>article</b> - назва статті</li>

					</ul>
				</td>
				<td data-label="Шаблон">
					<a href="/uploads/excel_templates/repeated_operations.xlsx">Шаблон</a>
				</td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3>4. Щомісячні витрати</h3>
	<div>
		<table id="contractor_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>department</b> - назва департаменту</li>
						<li><b>amount</b> - сума</li>
						<li><b>currency</b> - валюта ( UAH - гривня, RUB - рубль, USD - долар, EUR - євро</li>
						<li><b>type</b> - тип операції ( 1 - прихід, 2 - витрата )</li>
						<li><b>contractor</b> - повне ім'я або назва контрагента</li>
						<li><b>comment</b> - коментар</li>
						<li><b>date</b> - день дати ( 15 - 15 числа кожного місяця, 30 - 30 - го числа кожного місяця)
						</li>
						<li><b>article</b> - назва статті</li>

					</ul>
				</td>
				<td data-label="Шаблон">
					<a href="/uploads/excel_templates/repeated_operations.xlsx">Шаблон</a>
				</td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<h3>5. Завантажте товари з ваших складів</h3>
	<div>
		<p>
			З цієї таблиці підвантажаться найменування, а також назви складів і департаментів, до яких вони належать
		</p>
		<table id="contractor_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>name</b> - повне ім'я або назва</li>
						<li><b>address</b> - прізвище</li>
						<li><b>email</b> - email</li>
						<li><b>phone</b> - телефон ( формат +380961111111 )</li>
						<li><b>language</b> - мова ( ua - українська, ru - російська )</li>
					</ul>
				</td>
				<td data-label="Шаблон">
					<a href="/uploads/excel_templates/users.xlsx">Шаблон</a>
				</td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3>6. Завантажте специфікації по вашим продуктах</h3>
	<div>
		<p>
			З цієї таблиці підванатажаться найменування, а також назви складів і департаментів, до яких вони належать
		</p>
		<table id="contractor_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>name</b> - повне ім'я або назва</li>
						<li><b>address</b> - прізвище</li>
						<li><b>email</b> - email</li>
						<li><b>phone</b> - телефон ( формат +380961111111 )</li>
						<li><b>language</b> - мова ( ua - українська, ru - російська )</li>
					</ul>
				</td>
				<td data-label="Шаблон">
					<a href="/uploads/excel_templates/users.xlsx">Шаблон</a></td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3>7. Завантажте продукти вашої компанії + прайси</h3>
	<div>
		<p>
			З цієї таблиці підванатажаться найменування, а також назви складів і департаментів, до яких вони належать
		</p>
		<table id="contractor_table" border="1px solid">
			<thead>
			<tr>
				<!--				<th>#</th>-->
				<th>Дані</th>
				<th>Шаблон</th>
				<th>Завантажити</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<!--				<td></td>-->
				<td data-label="Дані" style="width: 400px">
					<ul>
						<li><b>name</b> - повне ім'я або назва</li>
						<li><b>address</b> - прізвище</li>
						<li><b>email</b> - email</li>
						<li><b>phone</b> - телефон ( формат +380961111111 )</li>
						<li><b>language</b> - мова ( ua - українська, ru - російська )</li>
					</ul>
				</td>
				<td data-label="Шаблон">
					<a href="/uploads/excel_templates/users.xlsx">Шаблон</a>
				</td>
				<td data-label="Завантажити">
					<input class="form-control" type="file"/>
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
