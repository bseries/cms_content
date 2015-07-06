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
use lithium\util\Collection;

class Types extends \base_core\models\Base {

	protected $_meta = [
		'connection' => false
	];

	protected static $_data = [];

	public static function init() {
		static::finder('all', function($self, $params, $chain) {
			return new Collection(['data' => static::$_data]);
		});
		static::finder('list', function($self, $params, $chain) {
			$results = [];

			foreach (static::$_data as $name => $item) {
				$results[$name] = $item->title;
			}
			return $results;
		});
		static::finder('first', function($self, $params, $chain) {
			return static::$_data[$params['options']['conditions']['name']];
		});
	}

	public static function register($name, array $options = []) {
		static::$_data[$name] = static::create($options + [
			'input' => function($context, $item) {},
			'format' => function($context, $item) {}
		]);
	}

	public function input($entity, $context, $item) {
		$handler = $entity->data(__FUNCTION__);
		return $handler($context, $item);
	}

	public function format($entity, $context, $item) {
		$handler = $entity->data(__FUNCTION__);
		return $handler($context, $item);
	}
}

Types::init();

?>