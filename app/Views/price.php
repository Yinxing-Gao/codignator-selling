<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php

?>
<div id="prices">
	<div class="text-center">
		<h2>Прайси </h2>
	</div>

	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col"><?= lang_('Price.name', $locale); ?></th>
					<?php if (!empty($price_types)): ?>
						<?php foreach ($price_types as $type): ?>
							<th><?= lang_('Price.' . $type, $locale); ?></th>
						<?php endforeach; ?>
					<?php endif; ?>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($products)): ?>
					<?php foreach ($products as $product): ?>
						<tr data-product_id="<?= $product['id']; ?>">
							<td><?= $product['id']; ?></td>
							<td><?= $product['name']; ?></td>
							<?php if (!empty($price_types)): ?>
								<?php foreach ($price_types as $type): ?>
									<td>
										<?= !empty($prices[$product['id']][$type]['amount']) ?
											$prices[$product['id']][$type]['amount'] : 0; ?>
										<?= !empty($prices[$product['id']][$type]['currency']) ? $prices[$product['id']][$type]['currency'] : ' ' ?>
									</td>
								<?php endforeach; ?>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>


