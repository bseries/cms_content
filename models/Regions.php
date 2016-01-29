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
use lithium\util\Collection;

class Regions extends \base_core\models\Base {

	protected $_meta = [
		'connection' => false
	];

	protected $_actsAs = [
		'base_core\extensions\data\behavior\Access'
	];

	protected static $_data = [];

	public static function init() {
		trigger_error('Deprecated use class in cms\content NS.', E_USER_DEPRECATED);
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
		trigger_error('Deprecated use class in cms\content NS.', E_USER_DEPRECATED);
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

	public static function has($name) {
		trigger_error('Deprecated use class in cms\content NS.', E_USER_DEPRECATED);
		return isset(static::$_data[$name]);
	}
}

Regions::init();

?>