<?php

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

if (is_callable($type['field'])) {
	$typeHtml = $type['field']($this);
} else {
	$typeHtml = $this->form->field('value_text', $type['field'] + ['value' => $item->value_text]);
}

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?>">

	<?=$this->form->create($item) ?>
		<?= $this->form->field('type', [
			'type' => 'hidden'
		]) ?>
		<div class="grid-row">
			<div class="grid-column-left">
				<?php if ($type['name'] !== 'page'): ?>
					<?php echo $typeHtml ?>
				<?php endif ?>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('region', [
					'type' => 'select',
					'label' => $t('Region'),
					'list' => $regions
				]) ?>
			</div>
		</div>

		<div class="grid-row">
			<?php if ($type['name'] === 'page'): ?>
				<?php echo $typeHtml ?>
			<?php endif ?>
		</div>
		<div class="bottom-actions">
			<?php if ($item->exists()): ?>
				<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button large']) ?>
			<?php endif ?>
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'button large save']) ?>
		</div>
	<?=$this->form->end() ?>
</article>