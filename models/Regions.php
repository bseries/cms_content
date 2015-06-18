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

class Regions extends \base_core\models\Base {

	protected $_meta = [
		'connection' => false
	];

	protected static $_actsAs = [
		'base_core\extensions\data\behavior\Access'
	];

	protected static $_data = [];

	public static function init() {
		static::finder('all', function($self, $params, $chain) {
			return static::$_data;
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
		if (!isset($options['type'])) {
			$message = 'You must provide a type when registering a region.';
			throw new Exception($message);
		}
		if (isset($options['access'])) {
			$options['access'] = (array) $options['access'];
		}
		static::$_data[$name] = static::create($options + [
			'name' => $name,
			'title' => $name,
			'type' => null, // one of the registered type
			'access' => ['user.role:admin']
		]);
	}
}

Regions::init();

?>