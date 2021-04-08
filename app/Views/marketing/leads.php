<div id="leads_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Ліди</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?=base_url()?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати нового ліда">
				<img src="<?=base_url()?>/icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="doublescroll">
				<table class="table table-striped tablesorter" data-label="">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Дата</th>
						<th>Ім'я</th>
						<th>Контакти</th>
						<th>Сума</th>
						<th>Джерело</th>
						<th>Статус</th>
						<th>Кваліфікація</th>
						<th>Коментарі</th>
						<th>Задачі</th>
						<th>Документи</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($leads)): ?>
						<?php foreach ($leads as $lead): ?>
							<tr data-lead_id="<?= $lead['id']; ?>">
								<td data-label="#"><?= $lead['id']; ?></td>
								<td data-label="Дата"><?= date('d.m.Y H:i', $lead['date']); ?></td>
								<td data-label="Ім'я"><?= $lead['name']; ?></td>
								<td data-label="Контакти">
									<?php if (!empty($lead['contacts'])): ?>
										<?php foreach ($lead['contacts'] as $contact): ?>
											<?php switch ($contact['type']) {
												case 'phone': ?>
													<a href="tel: <?= $contact['value']; ?>"><?= $contact['value']; ?></a>
													<?php break;
												case 'telegram': ?>
													<a href="https://t.me/ <?= $contact['value']; ?>"><?= $contact['value']; ?></a>
													<?php break;
												case 'site': ?>
													<a href="<?= $contact['value']; ?>"><?= $contact['value']; ?></a>
													<?php break;
												case 'fb': ?>
													<a href="https://www.facebook.com/<?= $contact['value']; ?>"><?= $contact['value']; ?></a>
													<?php break;
												default: ?>
													<p><?= $contact['value']; ?></p>

													<?php break;
											} ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</td>
								<td data-label="Сума"><?= (!empty($lead['currency']) && !empty($lead['amount'])) ? $lead['amount'] . ' ' . (!empty($currencies[$lead['currency']]) ? $currencies[$lead['currency']] : $currencies['UAH']) : ''; ?>
								</td>
								<td data-label="Джерело"><?= $lead['source_name']; ?></td>
								<td data-label="Статус">
									<?php if (!empty($lead_statuses)): ?>
										<select data-old-value="<?= $lead['status'] ?>" class="form-control status"
												style="min-width: 60px" name="status"
												required="">
											<?php foreach ($lead_statuses as $status => $translation): ?>
												<option value="<?= $status; ?>"
														<?= $lead['status'] == $status ? 'selected' : ''; ?>><?= $translation; ?></option>
											<?php endforeach; ?>
										</select>
									<?php else: ?>
										<?= $lead['status']; ?>
									<?php endif; ?>
                                </td>
                                <td data-label="Кваліфікація">
									<?php if (!empty($lead_qualifications)): ?>
										<select class="form-control qualification" style="min-width: 60px"
												name="qualification"
												required="">
											<?php foreach ($lead_qualifications as $qualification): ?>
												<option value="<?= $qualification; ?>" <?= $lead['qualification'] == $qualification ? 'selected' : ''; ?>><?= $qualification; ?></option>
											<?php endforeach; ?>
										</select>
									<?php else: ?>
										<?= $lead['qualification']; ?>
									<?php endif; ?>
								</td>
								<td data-label="Коментарі" class="comment_td" data-lead_id="<?= $lead['id']; ?>">
									<?php if (!empty($lead['comments'])): ?>
										<?php for ($i = 0; $i < count($lead['comments']); $i++) : ?>
											<?php $comment = $lead['comments'][$i]; ?>
											<!--									--><?php //for ($lead['comments'] as $comment): ?>
											<div
												class="comment <?= ($i < count($lead['comments']) - 1) ? 'hidden' : ''; ?> ">
												<b><?= date('d.m.Y H:i', $comment['date']); ?></b>
												<?= $comment['comment']; ?>

											</div>
										<?php endfor; ?>
										<!--									--><?php //if (count($lead['comments']) > 1): ?>
										<!--										<a href="#" class="show_all_comments" data-shown="0">Показати всі</a>-->
										<!--									--><?php //endif; ?>
									<?php endif; ?>
								</td>
								<td data-label="Задачі" class="task_td">
									<?php if (!empty($lead['tasks'])): ?>
										<?php foreach ($lead['tasks'] as $task): ?>
                                            <?php if($task['status'] != app\Models\Tasks::$STATUS_ACTIVE){
                                                continue;
                                            } ?>

											<div class="task <?= $task['date_to'] < time() ? "expired" : '' ?>" data-task_id="<?= $task['id'] ?>">
												<div class="task_actions">
													<img src="../../../icons/bootstrap/pencil.svg" class="edit_task" data-task_id="<?= $task['id'] ?>" />
													<img src="../../../icons/bootstrap/check-square.svg"
														 class="finish_task" title="Відмітити задачу виконаною і забрати зі списку" data-task_id="<?= $task['id'] ?>" />
												</div>
												<b class="task_card_date"><?= date('d.m.Y H:i', $task['date_to']); ?></b>
												<span class="task_card_name"><?= $task['task']; ?></span>
											</div>

										<?php endforeach; ?>
									<?php endif; ?>
								</td>
								<td data-label="Документи">
									<?php if (!empty($lead['docs'])) : ?>
										<?php $files = json_decode(html_entity_decode($lead['docs']));
										    if(is_array($files)){
                                                foreach($files as $file){
                                                    $filename = basename($file);
                                                    echo "
										            <div class='block'><a href='{$file}'>{$filename}</a></div>
										        ";
                                                }
                                            }
										?>
										<!--									--><?php //foreach ($files as $file): ?>
										<!--										<span>-->
										<!--									<a href="--><? //= $file; ?><!--" class="order_file"-->
										<!--									   target="_blank">--><? //= substr($file, strripos($file, '/') + 1); ?><!--</a>-->
										<!--									</span>-->
										<!--									--><?php //endforeach; ?>
									<?php endif; ?>
								</td>
								<td data-label="" class="lead_actions" data-lead_id="<?= $lead['id']; ?>">
									<img src="<?=base_url();?>/icons/bootstrap/pencil.svg" class="edit_lead"/>
									<img src="<?=base_url();?>/icons/bootstrap/chat.svg" class="add_lead_comment"/>
									<img src="<?=base_url();?>/icons/bootstrap/check-square.svg" class="add_lead_task"/>
									<img src="<?=base_url();?>/icons/bootstrap/cash-stack.svg" class="lead_operations"/>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>

<div id="lead_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
		</a>

		<div class="lead_sidenav_content sidenav_content">
		<form class="needs-validation">
			<h4 id="title" class="text-center">Додати нового ліда</h4>
<!--			<input type="hidden" class="form-control" name="account_id"-->
<!--				   value="--><?//= $account_id; ?><!--">-->
<!---->
<!--			<input type="hidden" class="form-control" name="author_id"-->
<!--				   value="--><?//= $user_id; ?><!--">-->
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="">
						Ім'я*</label>
					<input class="form-control" type="text" name="name">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="description">
						Контакти</label>
					<div class="contacts_block">
						<div class="contact" data-type="phone" data-title="додати телефон">
							<img src="../../../icons/fineko/phone.svg" class="phone"/>
						</div>
						<div class="contact" data-type="telegram" data-title="додати Telegram">
							<img src="../../../icons/fineko/telegram.svg" class="telegram"/>
						</div>
						<div class="contact" data-type="fb" data-title="додати фб">
							<img src="../../../icons/fineko/fb.svg" class="fb"/>
						</div>
						<div class="contact" data-type="other" data-title="додати інший контакт">
							<img src="../../../icons/fineko/other_contact.svg" class="other"/>
						</div>
					</div>
					<div class="contact_fields_block">

					</div>
				</div>
				<div class="col-xs-12 col-md-12">
					<label for="">
						Продукт</label>
					<select class="form-control" id="lead_product" name="product_id" required="">
					</select>
				</div>
				<div class="col-xs-12 col-md-12">
					<label for="">
						Вартість продукту</label>
					<div class="input-group">
						<input class="form-control" type="text" name="amount">
						<select class="form-control" id="currency" name="currency" required="">
							<option value="UAH">₴</option>
							<option value="USD">$</option>
							<option value="EUR">€</option>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Джерело</label>
					<select class="form-control" name="source_id" id="lead_source" required="">
						<!--							<option value="0">Лендінг</option>-->
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Кваліфікація</label>
					<select class="form-control" name="qualification" required="">
						<option value="A">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Документи</label>
					<input class="form-control" type="file" multiple name="documents">
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_lead" type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="edit_lead_task_sidenav" class="sidenav">
    <a href="javascript:void(0)" class="close_nav_btn">
        <img src="../../icons/bootstrap/x.svg"/>
    </a>
    <?php include "app/Views/tasks/edit_task_form.php"?>
</div>
