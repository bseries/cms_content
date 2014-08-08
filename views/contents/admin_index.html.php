<?php

use cms_content\models\Contents;
use textual\Modulation as Textual;

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('contents')
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
					<td data-sort="region" class="region emphasize list-sort asc"><?= $t('Region') ?>
					<td data-sort="value" class="value excerpt list-sort"><?= $t('Content') ?>
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
					<td class="emphasize region"><?= $item->region('title') ?>
					<td class="value">
						<?php
							$value = $item->value();

							if (is_object($value)) {
								echo $this->media->image($value->version('fix3admin')->url('http'), ['class' => 'media']);
							} else {
								echo Textual::limit(strip_tags($value));
							}
						?>
					<td class="date created">
						<time datetime="<?= $this->date->format($item->created, 'w3c') ?>">
							<?= $this->date->format($item->created, 'date') ?>
						</time>
					<td class="actions">
						<?= $this->html->link($t('delete'), ['id' => $item->id, 'action' => 'delete', 'library' => 'cms_content'], ['class' => 'delete button']) ?>
						<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button']) ?>
						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'cms_content'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>
</article>