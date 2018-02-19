<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

use base_core\extensions\cms\Widgets;
use cms_content\models\Blocks;
use cms_content\models\Pages;
use lithium\g11n\Message;

extract(Message::aliases());

Widgets::register('authoring', function() use ($t) {
	return [
		'data' => [
			$t('Pages', ['scope' => 'cms_content']) => Pages::find('count'),
			$t('Content blocks', ['scope' => 'cms_content']) => Blocks::find('count')
		]
	];
}, [
	'type' => Widgets::TYPE_TABLE,
	'group' => Widgets::GROUP_DASHBOARD,
	'weight' => Widgets::WEIGHT_HIGH
]);

?>