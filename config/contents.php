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
use base_media\models\Media;

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
	'field' => [
		'label' => $t('Content'),
		'type' => 'textarea',
		'wrap' => ['class' => 'editor-size--beta use-editor editor-basic editor-link'],
	]
]);
Contents::registerType('page', [
	'title' => $t('Page Content'),
	'field' => [
		'label' => $t('Content'),
		'type' => 'textarea',
		'wrap' => ['class' => 'editor-size--beta use-editor editor-basic editor-link editor-size editor-media editor-list editor-headline'],
	]
]);
Contents::registerType('media', [
	'title' => $t('Media'),
	'field' => function($context) {
		extract(Message::aliases());

		$html  = '<div class="media-attachment use-media-attachment-direct">';
		$html .= $context->form->label('ContentsValueMedia', $t('Media'));
		$html .= $context->form->hidden('value_media_id');
		$html .= '<div class="selected"></div>';
		$html .= $context->html->link($t('select'), '#', ['class' => 'button select']);
		$html .= '</div>';

		return $html;
	}
]);

Media::registerDependent('cms_content\models\Contents', [
	'value' => 'direct'
]);

?>