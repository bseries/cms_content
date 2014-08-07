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

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?>">

	<?=$this->form->create($item) ?>
		<?= $this->form->field('type', [
			'type' => 'hidden'
		]) ?>
		<div class="grid-row grid-row-last">
			<div class="grid-column-left">
				<?php
					$type = $item->type();

					if (is_callable($type['field'])) {
						echo $type['field']($this);
					} else {
						echo $this->form->field('value_text', $type['field']);
					}
				?>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('region', [
					'type' => 'select',
					'label' => $t('Region'),
					'list' => $regions
				]) ?>
			</div>
		</div>

		<div class="bottom-actions">
			<?php if ($item->exists()): ?>
				<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button large']) ?>
			<?php endif ?>
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'button large save']) ?>
		</div>
	<?=$this->form->end() ?>
</article>