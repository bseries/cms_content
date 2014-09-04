<?php
/**
 * CMS Content
 *
 * Copyright (c) 2013-2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace cms_content\models;

use lithium\storage\Cache;
use base_media\models\Media;
use OutOfBoundsException;

class Contents extends \base_core\models\Base {

	protected static $_actsAs = [
		'base_media\extensions\data\behavior\Coupler' => [
			'bindings' => [
				'value' => [
					'type' => 'direct',
					'to' => 'value_media_id'
				],
			]
		],
		'base_core\extensions\data\behavior\Timestamp'
	];

	public $belongsTo = [
		'ValueMedia' => [
			'to' => 'base_media\models\Media',
			'key' =>  'value_media_id'
		]
	];

	protected static $_regions = [];

	protected static $_types = [];

	public static function registerRegion($name, array $options = []) {
		static::$_regions[$name] = $options;
	}

	public static function regions() {
		return static::$_regions;
	}

	public function region($entity, $field = null) {
		return $field ? static::$_regions[$entity->region][$field] : static::$_regions[$entity->region];
	}

	public static function registerType($name, array $options = []) {
		static::$_types[$name] = $options;
	}

	public static function types() {
		return static::$_types;
	}

	public function type($entity, $field = null) {
		return $field ? static::$_types[$entity->type][$field] : static::$_types[$entity->type];
	}

	public function value($entity) {
		if ($entity->value_media_id) {
			return Media::find('first', ['conditions' => ['id' => $entity->value_media_id]]);
		}
		return $entity->value_text;
	}

	public static function get($region) {
		if (!isset(static::$_regions[$region])) {
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

// Invalidate caches.
Contents::applyFilter('delete', function($self, $params, $chain) {
	Cache::delete('default', Contents::generateItemCacheKey($params['entity']->region));
	return $chain->next($self, $params, $chain);
});
Contents::applyFilter('save', function($self, $params, $chain) {
	if (isset($params['data']['value_media_id']) && empty($params['data']['value_media_id'])) {
		$params['data']['value_media_id'] = null;
	}

	Cache::delete('default', Contents::generateItemCacheKey($params['entity']->region));
	return $chain->next($self, $params, $chain);
});

?>