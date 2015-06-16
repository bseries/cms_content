<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'cms_content', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'single',
		'title' => null,
		'empty' => null,
		'object' => $t('content')
	],
	'meta' => [
		'type' => $item->type,
		'is_published' => $item->is_published ? $t('published') : $t('unpublished'),
	]
]);

?>
<article>

	<?=$this->form->create($item) ?>
		<?= $this->form->field('type', [
			'type' => 'hidden'
		]) ?>
		<?php if ($useOwner): ?>
			<div class="grid-row">
				<h1><?= $t('Access') ?></h1>

				<div class="grid-column-left"></div>
				<div class="grid-column-right">
					<?= $this->form->field('owner_id', [
						'type' => 'select',
						'label' => $t('Owner'),
						'list' => $users
					]) ?>
				</div>
			</div>
		<?php endif ?>
		<div class="grid-row">
			<div class="grid-column-left"></div>
			<div class="grid-column-right">
				<?= $this->form->field('region', [
					'type' => 'select',
					'label' => $t('Region'),
					'list' => $regions
				]) ?>
			</div>
		</div>

		<div class="grid-row">
			<?php echo $item->input($this) ?>
		</div>
		<div class="bottom-actions">
			<?php if ($item->exists()): ?>
				<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button large']) ?>
			<?php endif ?>
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'button large save']) ?>
		</div>
	<?=$this->form->end() ?>
</article>