<?php
?>
<div id="lead_operations">
	<h2>Плановані операції <?= $lead->name; ?><img src="../../../icons/bootstrap/plus-circle.svg"></h2>
	<div class="row">
		<div class="col-xs-12 col-md-12 operation_table_div">
			<?php if (!empty($planned_operations)): ?>

				<table class="table">
					<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Сума</th>
						<th scope="col">Коментар</th>
						<th style="min-width:120px" scope="col">Стаття</th>
						<th scope="col">Планована дата</th>
						<th scope="col">Вірогідність</th>
						<th style="min-width: 55px" scope="col">

						</th>
					</tr>
					</thead>
					<tbody>

					<?php foreach ($planned_operations as $operation): ?>
						<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
							<td><?= $operation['id']; ?></td>
							<td>
								<div class="operation_amount"
									 style="color:<?= $op_style[$operation['operation_type_id']]; ?>; border:2px solid <?= $op_style[$operation['operation_type_id']]; ?>">
									<?= $operation['amount2']; ?>
									&nbsp;<?= key_exists($operation['currency2'], $currencies)
										? $currencies[$operation['currency2']] : ''; ?>
							</td>
							<td class="filter_td"><?= $operation['comment']; ?></td>
							<td>
								<?php
//								$articles = $income_list;
								$select = true;
								?>
								<?php if ($select): ?>
									<select class="form-control article_id"
											name="article_id" required="">

										<option value="">Не вибрана стаття</option>

<!--										--><?php //if (!empty($articles)): ?>
<!--											--><?php //foreach ($articles as $article_item): ?>
<!--												<optgroup label="--><?//= $article_item['name']; ?><!--">-->
<!--													--><?php //if (!empty($article_item['children'])): ?>
<!--														--><?php //foreach ($article_item['children'] as $article_1): ?>
<!--															<option-->
<!--																--><?//= $operation['article_id'] == $article_1['id'] ? 'selected' : '' ?>
<!--																value="--><?//= $article_1['id']; ?><!--">--><?//= $article_1['name']; ?><!--</option>-->
<!--														--><?php //endforeach; ?>
<!--													--><?php //else: ?>
<!--														<option-->
<!--															--><?//= $operation['article_id'] == $article_item['id'] ? 'selected' : '' ?>
<!--															value="--><?//= $article_item['id']; ?><!--">--><?//= $article_item['name']; ?><!--</option>-->
<!---->
<!--													--><?php //endif; ?>
<!--												</optgroup>-->
<!--											--><?php //endforeach; ?>
<!--										--><?php //endif; ?>
									</select>
								<?php else: ?>
									<?php if ($operation['operation_type_id'] == 4): ?>
										<span>Кредит</span>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td><?= !empty($operation['planned_on']) ? date('d.m.Y', $operation['planned_on']) : ''; ?></td>
							<td><?= $operation['probability']; ?> %</td>
							<td>
								<img class="edit_operation" src="../../../icons/bootstrap/pencil.svg"/>
								<img class="delete_operation" src="../../../icons/bootstrap/trash.svg"/>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

			<?php else: ?>
				<p class="no_operations">Планованих операцій поки немає</p>
			<?php endif; ?>
		</div>
	</div>
</div>

