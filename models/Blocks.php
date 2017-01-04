<?php
/**
 * CMS Content
 *
 * Copyright (c) 2013 Atelier Disko - All rights reserved.
 *
 * Licensed under the AD General Software License v1.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * You should have received a copy of the AD General Software
 * License. If not, see http://atelierdisko.de/licenses.
 */

namespace cms_content\models;

use Exception;
use OutOfBoundsException;
use base_media\models\Media;
use cms_content\cms\content\Regions;
use cms_content\cms\content\Types;
use lithium\storage\Cache;

// Blocks are small units of content, that can be injected into the site. This one of the
// more traditional CMS means that are provided.
//
// Blocks access is restricted additionally by region access controls. Adding or deleting
// a block to a region is considered modifying that region. Modifying is not.
//
// FIXME Find Operations shoudl be heavily cached in order to minimize costs of defining
// "dynamic" regions in a site. This used to be the case with the get() method. But we
// dropped support for it in favor of using plain find(). Caching should in the future
// hook into find() and must also find a good way of invalidating the cache.
class Blocks extends \base_core\models\Base {

	protected $_meta = [
		'source' => 'content_blocks'
	];

	public $belongsTo = [
		'Owner' => [
			'to' => 'base_core\models\Users',
			'key' => 'owner_id'
		],
		'ValueMedia' => [
			'to' => 'base_media\models\Media',
			'key' =>  'value_media_id'
		]
	];

	protected $_actsAs = [
		'base_core\extensions\data\behavior\Ownable',
		'base_media\extensions\data\behavior\Coupler' => [
			'bindings' => [
				'value' => [
					'type' => 'direct',
					'to' => 'value_media_id'
				],
			]
		],
		'base_core\extensions\data\behavior\Timestamp',
		'base_core\extensions\data\behavior\Searchable' => [
			'fields' => [
				'Owner.name',
				'type',
				'region'
			]
		],
		'base_core\extensions\data\behavior\Localizable' => [
			'fields' => [
				'value_number' => 'number',
				'value_money' => 'money'
			]
		]
	];

	public static function init() {
		$model = static::_object();

		if (PROJECT_LOCALE !== PROJECT_LOCALES) {
			static::bindBehavior('li3_translate\extensions\data\behavior\Translatable', [
				'fields' => ['value_text'],
				'locale' => PROJECT_LOCALE,
				'locales' => explode(' ', PROJECT_LOCALES),
				'strategy' => 'inline'
			]);
		}
	}

	public function region($entity) {
		return Regions::registry($entity->region);
	}

	public function type($entity) {
		return Types::registry($entity->type);
	}

	// Formats the content blocks' value using registered handler.
	// $context is the rendering context (usually $this when called from the view.
	// $type is either full or preview.
	public function format($entity, $context, $type = 'full') {
		return $entity->type()->format($context, $entity, $type);
	}

	// Returns the content blocks' value (without formatting applied). This can
	// be depend on the content block type a number, a string or a Media entity.
	public function value($entity) {
		if ($entity->value_media_id) {
			return Media::find('first', ['conditions' => ['id' => $entity->value_media_id]]);
		}
		foreach (['value_number', 'value_money', 'value_text'] as $field) {
			if ($entity->{$field}) {
				return $entity->{$field};
			}
		}
	}

	// Renders an HTML input field for the block, using the registered renderer.
	// Used mainly only inside the admin.
	// $context is the rendering context (usually $this when called from the view.
	public function input($entity, $context) {
		return $entity->type()->input($context, $entity);
	}
}

Blocks::init();

Blocks::applyFilter('save', function($self, $params, $chain) {
	if (isset($params['data']['value_media_id']) && empty($params['data']['value_media_id'])) {
		$params['data']['value_media_id'] = null;
	}
	return $chain->next($self, $params, $chain);
});

?>