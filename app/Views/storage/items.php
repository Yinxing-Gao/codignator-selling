<div id="storage_item_list">
	<div class="row title_div">	
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h2 class="text-center">Cклад (<?= $storage->name; ?>)</h2>
		</div>
		
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon" data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_storage_add_item_sidenav" data-title="add new name">
				<img src="../../../icons/fineko/add_app.svg"/>
			</div>
			<div class="title_div_icon open_storage_sell_sidenav" data-title="sell item">
				<img src="../../../icons/fineko/sales.svg"/>
			</div>
			<div class="title_div_icon open_storage_transfer_sidenav" data-title="transfer to other storage">
				<img src="../../../icons/fineko/додати задачу.svg"/>
			</div>
		</div>	
	</div>
	<?php 
// echo "<pre>";
// 											var_dump($items);
// 											echo "</pre>"; 
											?>

	<div class="filter_btns">
		<div class="form-check" style="display: none">
			<input type="checkbox" class="" id="">
			<label class="form-check-label" for="">пусті позиції</label>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Назва</th>
					<th scope="col">Одиниця вимірювання</th>
					<th scope="col">Закупівельна ціна</th>
					<th scope="col">К-сть</th>
					<th scope="col">Опис</th>
					<th scope="col">Останнє оновлення</th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php foreach ($items as $item): ?>
							<tr data-item_id="<?= $item['id']; ?>">
								<td><?= $item['id']; ?></td>
								<td><?= $item['name']; ?></td>
								<td><?= $item['unit']; ?></td>
								<td><?= $item['buy_price']; ?></td>
								<td class="amount"><?= $item['amount']; ?></td>
								<td><?= $item['description']; ?></td>
								<td><?= !empty($item['last_change_date']) ? date('d.m.Y', $item['last_change_date']) : ''; ?></td>
								<td class="title_div_icon" title="edit"><img class="open_edit_storage_item_name_sidenav pointer" src="../../../icons/bootstrap/pencil.svg"/></td>
								<td class="title_div_icon" title="delete"><?= $item['amount'] == 0 ? '<img class="delete_storage_item_name pointer" src="../../../icons/bootstrap/trash.svg"/>' : ''; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<p>Sorry. There isn't any items in the storage.</p>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- add item side nav -->
<div id="storage_add_item_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="text-center"> 
		<h4>add new item</h4>
	</div>
	
	<form>
		<div class="storage_sidenav_content">
			<label for="name">Storage</label><br>
			<select class="form-control action_select" name="storage_id">
				<option name="storage_id" value="<?= $storage->id; ?>"><?= $storage->name; ?></option>
			</select>
			<hr/>

			<label for="name">Storage item name*</label><br>
			<input class="form-control action_select" type="text" name="name" value="" required="required"><br>
			
			<label for="unit">Unit*</label><br>
			<select class="form-control action_select">
				<option value="шт">шт</option>
				<option value="гр">гр</option>
				<option value="м">м</option>
				<option value="м">мм</option>
				<option value="комплект">комплект</option>
				<option value="кг">кг</option>
			</select><br>

			<label for="name">Description*</label><br>
			<textarea class="form-control action_select" name="description" rows="4" cols="250" placeholder="description"></textarea><br>

			<div class="add_storage_item_price_amount">
				<div class="add_storage_item_price">
					<label for="name">Buy Price*</label><br>
					<input required="required" class="form-control action_select" type="number" name="buy_price" placeholder="0000.00"
						pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
						title="This should be a number with up to 2 decimal places.">
				</div><br>

				<div class="add_storage_item_amount">
					<label for="name">Amount*</label><br>
					<input required="required" class="form-control action_select" type="number" min="0" name="min_amount" placeholder="0">
				</div>
			</div>
			<hr/>

			<div class="col-md-12">
				<label for=""></label>
				<button class="btn btn-primary btn-lg btn-block btn_add_storage_item" type="submit">add item</button>
			</div>
			
		</div>
	</form>
</div>

<!-- edit item side nav -->
<div id="storage_edit_sidenav" class="sidenav color">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="text-center"> 
		<h4>edit name items</h4>
	</div>

	<div class="storage_sidenav_content">

		<label for="name">item name</label>
		<select class="form-control action_select" disabled name="storage_name_id">
			<option name="storage_name_id" value="<?= $storage->id; ?>"><?= $storage->name; ?></option>
		</select><br>

		<label for="name">Please select item*</label>
		<form class="storage_item_add_form">
			<select class="form-control action_select" id="select">
				<?php if (!empty($items)): ?>
					<?php foreach ($items as $item): ?>
						
						<option value="<?= $item['name']; ?>"><?= $item['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</form>
		<hr/>
		
		<div class="storage_item_edit_form">
			<label for="name">item name*</label><br>
			<input class="form-control action_select" type="text" name="name" value="" required="required"><br>

			<label for="unit">Unit*</label><br>
			<select class="form-control action_select">
				<option value="шт">шт</option>
				<option value="гр">гр</option>
				<option value="м">м</option>
				<option value="м">мм</option>
				<option value="комплект">комплект</option>
				<option value="кг">кг</option>
			</select><br>

			<div class="add_storage_item_price_amount">
				<div class="add_storage_item_price">
					<label for="name">Buy Price*</label><br>
					<input required="required" class="form-control action_select" type="number" name="buy_price" placeholder="0000.00"
						pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
						title="This should be a number with up to 2 decimal places.">
				</div><br>

				<div class="add_storage_item_amount">
					<label for="name">Amount*</label><br>
					<input required="required" class="form-control action_select" type="number" min="0" name="min_amount" placeholder="0">
				</div>
			</div><br>

			<form method="post" enctype="multipart/form-data">
				Select image to upload:
				<input type="file" name="fileToUpload" id="fileToUpload">
				
			</form>
			<div class="col-md-12">
				<label for=""></label>
				<button class="btn btn-primary btn-lg btn-block">edit</button>
			</div>
		</div>	
	</div>
</div>



