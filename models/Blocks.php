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

// Operations are heavily cached in order to minimize costs of defining "dynamic"
// regions in a site.
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
		if (func_num_args() > 1) {
			// @deprecated
			throw new Exception('Field parameter on Blocks::region() is not supported anymore.');
		}
		return Regions::registry($entity->region);
	}

	public function type($entity) {
		return Types::registry($entity->type);
	}

	public function input($entity, $context) {
		return $entity->type()->input($context, $entity);
	}

	// $type is either full or preview.
	public function format($entity, $context, $type = 'full') {
		return $entity->type()->format($context, $entity, $type);
	}

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

	/* Deprecated / BC */

	public static function get($region) {
		if (!isset(Regions::registry(true)[$region])) {
			throw new OutOfBoundsException("Region `{$region}` not available.");
		}
		$cacheKey = static::generateItemCacheKey($region);

		if ($result = Cache::read('default', $cacheKey)) {
			return $result;
		}
		$result = static::find('first', [
			'conditions' => [
				'region' => $region,
				'is_published' => true
			],
			'order' => ['id' => 'ASC']
		]);
		if (!$result) {
			// throw new Exception("No content for region `{$region}` available.");
			return $result;
		}
		Cache::write('default', $cacheKey, $result, Cache::PERSIST);
		return $result;
	}

	public static function generateItemCacheKey($region) {
		return 'cms_content:contents:item:' . $region;
	}

}

Blocks::init();

// Invalidate caches.
// @deprecated
Blocks::applyFilter('delete', function($self, $params, $chain) {
	Cache::delete('default', Blocks::generateItemCacheKey($params['entity']->region));
	return $chain->next($self, $params, $chain);
});
Blocks::applyFilter('save', function($self, $params, $chain) {
	if (isset($params['data']['value_media_id']) && empty($params['data']['value_media_id'])) {
		$params['data']['value_media_id'] = null;
	}

	// @deprecated
	Cache::delete('default', Blocks::generateItemCacheKey($params['entity']->region));
	return $chain->next($self, $params, $chain);
});

?>