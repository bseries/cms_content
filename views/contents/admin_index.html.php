<?php

use cms_content\models\Contents;

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('contens')
	]
]);

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?> use-list">
	<div class="top-actions">
		<?php foreach (Contents::types() as $name => $type): ?>
			<?= $this->html->link(
				$t('New {:type}', ['type' => $type['title']]),
				['action' => 'add', 'contentType' => $name, 'library' => 'cms_content'],
				['class' => 'button add']
			) ?>
		<?php endforeach ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="is-published" class="flag is-published list-sort"><?= $t('publ.?') ?>
					<td data-sort="title" class="emphasize title list-sort"><?= $t('Title') ?>
					<td data-sort="type" class="type list-sort"><?= $t('Type') ?>
					<td data-sort="region" class="region list-sort"><?= $t('Region') ?>
					<td data-sort="created" class="date created list-sort"><?= $t('Created') ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'list-search'
						]) ?>
			</thead>
			<tbody class="list">
				<?php foreach ($data as $item): ?>
				<tr>
					<td class="flag is-published"><?= ($item->is_published ? '✓' : '×') ?>

					<td class="emphasize title"><?= $item->title ?>
					<td class="type"><?= $item->type ?>
					<td class="region"><?= $item->region ?>
					<td class="date created">
						<time datetime="<?= $this->date->format($item->created, 'w3c') ?>">
							<?= $this->date->format($item->created, 'date') ?>
						</time>
					<td class="actions">
						<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button']) ?>
						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'cms_core'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>
</article>