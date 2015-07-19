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
use base_media\models\Media;
use cms_content\models\Regions;
use cms_content\models\Types;
use lithium\storage\Cache;

// Operations are heavily cached in order to minimize costs of defining "dynamic"
// regions in a site.
class Blocks extends \base_core\models\Base {

	protected $_meta = [
		'source' => 'content_blocks'
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

	public function region($entity) {
		if (func_num_args() > 1) {
			// @deprecated
			throw new Exception('Field parameter on Blocks::region() is not supported anymore.');
		}
		return Regions::find('first', [
			'conditions' =>  [
				'name' => $entity->region
			]
		]);
	}

	public function type($entity) {
		return Types::find('first', [
			'conditions' =>  [
				'name' => $entity->type
			]
		]);
	}

	public function input($entity, $context) {
		return $entity->type()->input($context, $entity);
	}

	public function format($entity, $context) {
		return $entity->type()->format($context, $entity);
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

}

// Invalidate caches.
Blocks::applyFilter('delete', function($self, $params, $chain) {
	Cache::delete('default', Blocks::generateItemCacheKey($params['entity']->region));
	return $chain->next($self, $params, $chain);
});
Blocks::applyFilter('save', function($self, $params, $chain) {
	if (isset($params['data']['value_media_id']) && empty($params['data']['value_media_id'])) {
		$params['data']['value_media_id'] = null;
	}

	Cache::delete('default', Blocks::generateItemCacheKey($params['entity']->region));
	return $chain->next($self, $params, $chain);
});

?>