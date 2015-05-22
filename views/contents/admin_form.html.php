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

$type = $item->type();

if ($type['field']) {
	if (is_callable($type['field'])) {
		$typeHtml = $type['field']($this);
	} else {
		$typeHtml = $this->form->field('value_text', $type['field'] + [
			'value' => $item->value_text
		]);
	}
} elseif ($type['media']) {
	$typeHtml = $this->media->field('value_media_id', $type['media'] + [
		'value' => $item->value_text
	]);
} elseif ($type['editor']) {
	$typeHtml = $this->editor->field('value_text', $type['editor'] + [
		'value' => $item->value_text
	]);
}

?>
<article>

	<?=$this->form->create($item) ?>
		<?= $this->form->field('type', [
			'type' => 'hidden'
		]) ?>
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
			<?php echo $typeHtml ?>
		</div>
		<div class="bottom-actions">
			<?php if ($item->exists()): ?>
				<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button large']) ?>
			<?php endif ?>
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'button large save']) ?>
		</div>
	<?=$this->form->end() ?>
</article>