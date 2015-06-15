<?php
/**
 * CMS Content
 *
 * Copyright (c) 2013 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace cms_content\models;

use Exception;
use OutOfBoundsException;
use lithium\storage\Cache;
use base_media\models\Media;

// Model to manage content regions and their types. Operations are
// heavily cached in order to minimize costs of defining "dynamic"
// regions in a site.
class Contents extends \base_core\models\Base {

	protected static $_actsAs = [
		'base_media\extensions\data\behavior\Ownable',
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

	public $belongsTo = [
		'ValueMedia' => [
			'to' => 'base_media\models\Media',
			'key' =>  'value_media_id'
		]
	];

	protected static $_regions = [];

	protected static $_types = [];

	public static function registerRegion($region, array $options = []) {
		if (!isset($options['type'])) {
			$message = 'You must provide a type when registering a region.';
			trigger_error($message, E_USER_DEPRECATED); // @deprecated
			// throw new Exception($message);
		}
		static::$_regions[$region] = $options + [
			'name' => $region,
			'title' => $region,
			'type' => null, // one of the registered type
		];
	}

	public static function regions() {
		return static::$_regions;
	}

	public function region($entity, $field = null) {
		return $field ? static::$_regions[$entity->region][$field] : static::$_regions[$entity->region];
	}

	public static function registerType($name, array $options = []) {
		static::$_types[$name] = $options + [
			'input' => function($context, $item) {},
			'format' => function($context, $item) {}
		];
	}

	public static function types() {
		return static::$_types;
	}

	public function type($entity) {
		return static::$_types[$entity->type];
	}

	public function input($entity, $context) {
		$callable = static::$_types[$entity->type]['input'];
		return $callable($context, $entity);
	}

	public function format($entity, $context) {
		$callable = static::$_types[$entity->type]['format'];
		return $callable($context, $entity);
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