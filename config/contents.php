<?php
/**
 * Bureau Content
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

Contents::registerRegion('home_welcome', [
	'title' => $t('Home Welcome Box'),
]);

Contents::registerType('page', [
	'title' => $t('Page'),
	'fields' => [
		'title' => ['type' => 'string', 'title' => $t('Title'), 'length' => 250],
		'body' => ['type' => 'richbasic', 'title' => $t('Content')],
		'cover' => ['type' => 'media', 'title' => $t('Media')]
	]
]);
Contents::registerType('generic_text_content', [
	'title' => $t('Generic Text Content Element'),
	'fields' => [
		'body' => ['type' => 'richextra', 'title' => $t('Content')],
	]
]);
Contents::registerType('generic_media_content', [
	'title' => $t('Generic Media Content Element'),
	'fields' => [
		'media' => ['type' => 'media', 'media' => $t('Media')],
	]
]);

?>