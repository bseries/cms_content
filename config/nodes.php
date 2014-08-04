<?php
/**
 * Bureau Node
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

use cms_core\models\Nodes;
use lithium\g11n\Message;

extract(Message::aliases());

Nodes::registerRegion('home_welcome', [
	'title' => $t('Home Welcome Box'),
]);

Nodes::registerType('page', [
	'title' => $t('Page'),
	'fields' => [
		'title' => ['type' => 'string', 'title' => $t('Title'), 'length' => 250],
		'body' => ['type' => 'richbasic', 'title' => $t('Content')],
		'cover' => ['type' => 'media', 'title' => $t('Media')]
	]
]);
Nodes::registerType('generic_text_content', [
	'title' => $t('Generic Text Content Element'),
	'fields' => [
		'body' => ['type' => 'richextra', 'title' => $t('Content')],
	]
]);
Nodes::registerType('generic_media_content', [
	'title' => $t('Generic Media Content Element'),
	'fields' => [
		'media' => ['type' => 'media', 'media' => $t('Media')],
	]
]);

?>