<?php
/**
 * CMS Content
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

use cms_content\models\Contents;
use lithium\g11n\Message;

extract(Message::aliases());

Contents::registerType('text', [
	'title' => $t('Text'),
	'field' => [
		'label' => $t('Text'),
		'type' => 'text'
	]
]);
Contents::registerType('richtext', [
	'title' => $t('Rich-Text Content'),
	'editor' => [
		'label' => $t('Content'),
		'features' => 'minimal',
		'size' => 'beta'
	]
]);
Contents::registerType('page', [
	'title' => $t('Page Content'),
	'editor' => [
		'label' => $t('Content'),
		'features' => 'full',
		'size' => 'beta'
	]
]);
Contents::registerType('media', [
	'title' => $t('Media'),
	'media' => [
		'attachment' => 'direct'
	]
]);

?>