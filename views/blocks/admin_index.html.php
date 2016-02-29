<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'cms_content', 'default' => $message]);
};


$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('content blocks')
	]
]);

?>
<article
	class="use-rich-index"
	data-endpoint="<?= $this->url([
		'action' => 'index',
		'page' => '__PAGE__',
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__',
		'filter' => '__FILTER__'
	]) ?>"
>

	<div class="top-actions">
		<?php foreach ($regions as $region => $regionTitle): ?>
			<?php echo $this->html->link(
				$regionTitle,
				['action' => 'add'] + compact('region'),
				['class' => 'button add', 'escape' => false]
			) ?><br>
		<?php endforeach  ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="is-published" class="flag is-published table-sort"><?= $t('publ.?') ?>
					<td data-sort="region" class="region emphasize table-sort"><?= $t('Region') ?>
					<td data-sort="value" class="value excerpt table-sort"><?= $t('Content') ?>
					<td data-sort="modified" class="date modified table-sort desc"><?= $t('Modified') ?>
					<?php if ($useOwner): ?>
						<td class="user"><?= $t('Owner') ?>
					<?php endif ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'table-search',
							'value' => $this->_request->filter
						]) ?>
			</thead>
			<tbody>
				<?php foreach ($data as $item): ?>
				<tr>
					<td class="flag"><i class="material-icons"><?= ($item->is_published ? 'done' : '') ?></i>
					<td class="emphasize region"><?= $item->region()->title() ?>
					<td class="value media">
						<?php echo $item->format($this, 'preview') ?>
					<td class="date modified">
						<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
							<?= $this->date->format($item->modified, 'date') ?>
						</time>
					<?php if ($useOwner): ?>
						<td class="user">
							<?= $item->owner()->name ?>
					<?php endif ?>
					<td class="actions">
						<?= $this->html->link($item->is_published ? $t('unpublish') : $t('publish'), ['id' => $item->id, 'action' => $item->is_published ? 'unpublish': 'publish', 'library' => 'cms_content'], ['class' => 'button']) ?>
						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'cms_content'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>

	<?=$this->view()->render(['element' => 'paging'], compact('paginator'), ['library' => 'base_core']) ?>
</article>