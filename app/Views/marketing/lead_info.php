<div id="lead_info">
	<form>
		<div class="row" style="height: 95%">
			<div class="col-md-3 fields_column">
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<label for="">
							Ім'я*</label>
						<input class="form-control" type="text" name="name" value="<?= $lead->name; ?>">
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
							<?php if (!empty($lead->contacts)): ?>
								<?php foreach ($lead->contacts as $contact): ?>
									<div class="contact_field">
										<label><?= $contact_types[$contact['type']] ?></label>
										<input data-type="<?= $contact['type']; ?>" class="form-control"
											   value="<?= $contact['value']; ?>">
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-xs-12 col-md-12">
						<label for="">
							Продукт</label>
						<select class="form-control" name="product_id" required="">
							<?php if (!empty($products)): ?>
								<?php foreach ($products as $product): ?>
									<option
										<?= (!empty($lead->product_id) && $lead->product_id === $product['id']) ? 'selected' : '' ?>
										value="<?= $product['id']; ?>"><?= $product['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-xs-12 col-md-12">
						<label for="">
							Вартість продукту</label>
						<div class="input-group">
							<input class="form-control" type="text" name="amount" value="<?= $lead->amount; ?>">
							<select class="form-control" name="currency" required="">
								<option value="UAH"
										<?= (!empty($lead->currency) && $lead->currency === 'UAH') ? 'selected' : '' ?>>
									₴
								</option>
								<option value="USD"
										<?= (!empty($lead->currency) && $lead->currency === 'USD') ? 'selected' : '' ?>>
									$
								</option>
								<option value="EUR"
										<?= (!empty($lead->currency) && $lead->currency === 'EUR') ? 'selected' : '' ?>>
									€
								</option>
							</select>
						</div>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="">
							Джерело</label>
						<select class="form-control" name="type" required="">
							<?php if (!empty($sources)): ?>
								<?php foreach ($sources as $source): ?>
									<option value="<?= $source['id']; ?>"
											<?= $lead->source_id == $source['id'] ? 'selected' : '' ?>>
										<?= $source['name']; ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="">
							Кваліфікація</label>
						<select class="form-control" name="qualification" required="">
							<option value="A"
									<?= (!empty($lead->qualification) && $lead->qualification === 'A') ? 'selected' : '' ?>>
								A
							</option>
							<option value="B"
									<?= (!empty($lead->qualification) && $lead->qualification === 'B') ? 'selected' : '' ?>>
								B
							</option>
							<option value="C"
									<?= (!empty($lead->qualification) && $lead->qualification === 'C') ? 'selected' : '' ?>>
								C
							</option>
						</select>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="">
							Документи</label>
						<input class="form-control" type="file" multiple name="docs">
						<div class="uploaded_file">
                            <?php $files = json_decode(html_entity_decode($lead->docs));
                            if(is_array($files)){
                                foreach($files as $file){
                                    $filename = basename($file);
                                    echo "<span><a href='{$file}'>{$filename}</a><img class='delete_uploaded_file' src='../../../icons/bootstrap/trash.svg'></span>";
                                }
                            }
                            ?>
                        </div>
						<input type="hidden" name="uploaded_files" class="ajax-reply"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 all_question_groups">
						<?php if (!empty($all_question_groups)): ?>
							<h4>Додаткові блоки запитань</h4>
							<?php foreach ($all_question_groups as $question_group): ?>
								<div class="q_group">
									<label><?= $question_group['name']; ?></label>
                                    <input name="question_groups_ids" type="checkbox" class="form-control" data-question_group_id="<?= $question_group['id']; ?>"
                                        <?= in_array($question_group['id'], $lead->question_groups_ids) ? 'checked' : '' ?>/>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="col-md-3 answers_column">
				<?php if (!empty($question_groups)): ?>
					<?php foreach ($question_groups as $question_group): ?>
						<?php if (!empty($question_group['questions'])): ?>
                            <div class="question_group_questions" data-question_group_id="<?= $question_group['id'] ?>">
                                <h4><?= $question_group['name']; ?></h4>
                                <div class="group_questions_content">
                                    <?php foreach ($question_group['questions'] as $question): ?>
                                        <div class="lead_answers">
                                            <label><?= $question['question']; ?></label>
                                            <textarea class="form-control" data-question_id="<?= $question['id']; ?>"
                                                      data-question_group_id="<?= $question_group['id']; ?>"><?= $question['answer']; ?></textarea>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div class="col-md-3 comment_column">
				<?php if (!empty($lead->comments)): ?>
					<?php for ($i = 0; $i < count($lead->comments); $i++) : ?>
						<?php $comment = $lead->comments[$i]; ?>
						<!--									--><?php //for ($lead->comments as $comment): ?>
						<div
							class="comment">
							<b><?= date('d.m.Y H:i', $comment['date']); ?></b>
							<?= $comment['comment']; ?>

						</div>
					<?php endfor; ?>
				<?php endif; ?>
			</div>
			<div class="col-md-3 task_column">
				<?php if (!empty($lead->tasks)): ?>
					<?php foreach ($lead->tasks as $task): ?>

						<div class="task <?= $task['date_to'] < time() ? "expired" : '' ?>">
							<div class=" task_actions">
								<img src="../../../icons/bootstrap/pencil.svg" class="edit_task"/>
								<img src="../../../icons/bootstrap/check-square.svg"
									 class="finish_task"/>
							</div>
							<b><?= date('d.m.Y H:i', $task['date_to']); ?></b>
							<?= $task['task']; ?>
						</div>

					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="col-md-12">
				<div class="row">
					<div class="col-md-10">
					</div>
					<div class="col-md-2">
						<input class="form-control btn btn-success save_lead_info" data-lead_id="<?= $lead->id; ?>"
							   value="Зберегти"/>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$('#lead_info input[type=file]').on('change', function (event) {
		console.log('worked');
		files = this.files;

		event.stopPropagation(); // остановка всех текущих JS событий
		event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

		upload_files(files, '/marketing/upload_order_ajax');
	});
</script>
