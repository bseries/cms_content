<?php
/**
 * CMS Content
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * Licensed under the AD General Software License v1.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * You should have received a copy of the AD General Software
 * License. If not, see https://atelierdisko.de/licenses.
 */

namespace cms_content\config;

use lithium\g11n\Message;
use cms_content\cms\content\Types;
use textual\Modulation as Textual;

extract(Message::aliases());

Types::register('text', [
	'input' => function($context, $item) use ($t) {
		if ($context['isTranslated']) {
			$output = '';

			foreach ($item->translate('value_text') as $locale => $value) {
				$output .= $context->editor->field("i18n.value_text.{$locale}", [
					'label' => $t('Text', ['scope' => 'cms_content']) . ' (' . $context->g11n->name($locale) . ')',
					'value' => $value,
					'type' => 'text'
				]);
			}
			return $output;
		} else {
			return $context->form->field('value_text', [
				'label' => $t('Text', ['scope' => 'cms_content']),
				'type' => 'text',
				'value' => $item->value_text
			]);
		}
	},
	'format' => function($context, $item) {
		return $item->value_text;
	}
]);

Types::register('richtext', [
	'input' => function($context, $item) use ($t) {
		if ($context['isTranslated']) {
			$output = '';

			foreach ($item->translate('value_text') as $locale => $value) {
				$output .= $context->editor->field("i18n.value_text.{$locale}", [
					'label' => $t('Content', ['scope' => 'cms_content']) . ' (' . $context->g11n->name($locale) . ')',
					'value' => $value,
					'features' => 'full',
					'size' => 'beta'
				]);
			}
			return $output;
		} else {
			return $context->editor->field('value_text', [
				'label' => $t('Content', ['scope' => 'cms_content']),
				'value' => $item->value_text,
				'features' => 'full',
				'size' => 'beta'
			]);
		}
	},
	'format' => function($context, $item, $type) {
		$result = $context->editor->parse($item->value_text, [
			'mediaVersion' => 'fix3admin'
		]);

		if ($type === 'preview') {
			$result = strip_tags(Textual::limit($result, 40));
		}
		return $result;
	}
]);

Types::register('media', [
	'input' => function($context, $item) use ($t) {
		return $context->media->field('value_media_id', [
			'value' => $item->value(),
			'attachment' => 'direct'
		]);
	},
	'format' => function($context, $item) {
		if (!$medium = $item->value()) {
			return;
		}
		return $context->media->image($medium->version('fix3admin'), [
			'data-media-id' => $medium->id
		]);
	}
]);

Types::register('number', [
	'input' => function($context, $item) use ($t) {
		return $context->form->field('value_number', [
			'label' => $t('Number', ['scope' => 'cms_content']),
			'type' => 'text',
			'value' => $context->number->format($item->value_number ?: 0, 'decimal')
		]);
	},
	'format' => function($context, $item) {
		return $context->number->format($item->value_number ?: 0, 'decimal');
	}
]);

Types::register('money', [
	// Simulating money format helper method as we don't want to depend
	// on whole billing_core module for just the helper.
	'input' => function($context, $item) use ($t) {
		return $context->form->field('value_money', [
			'label' => $t('Money', ['scope' => 'cms_content']),
			'type' => 'text',
			'value' => $context->number->format(($item->value_money / 100) ?: 0, 'decimal')
		]);
	},
	'format' => function($context, $item) {
		return $context->number->format(($item->value_money / 100) ?: 0, 'decimal');
	}
]);

?>