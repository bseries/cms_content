<?php
/**
 * Bureau Node
 *
 * Copyright (c) 2013-2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace cms_node\models;

use lithium\storage\Cache;

class Nodes extends \cms_core\models\Base {

	protected static $_actsAs = [
		'cms_core\extensions\data\behavior\Timestamp',
		'cms_core\extensions\data\behavior\Serializable' => [
			'fields' => [
				'dynamic' => 'json'
			]
		]
	];

	protected static $_regions = [];

	protected static $_types = [];

	// FIXME Implement Node Categories or groups when they become necessary.

	public static function registerRegion($name, array $options = []) {
		static::$_regions[$name] = $options;
	}

	public static function registerType($name, array $options = []) {
		static::$_types[$name] = $options;
	}

	public static function types() {
		return static::$_types;
	}

	public function type($entity) {
		return static::$_types[$entity->type];
	}

	public function __get($property) {
		return $this->dynamic[$property]['value'];
	}

	public function __set($property, $value) {
		$this->dynamic[$property]['value'] = $value;
	}
}

// Must have type.

/*
Nodes::applyFilter('create', function($self, $params, $chain) {
	$type = static::$_types[isset($data['type']) ? $data['type'] : 'page'];

	foreach ($type['fields'] as $name => $field) {
		$entity->dynamic[$name] = [
			'value' => null,
			'type' => $field['type'],
			'length' => $field['length']
		];
	}
	return $chain->next($self, $params, $chain);
});
 */

// Non-Native dynamic columns.
/*
Nodes::applyFilter('save', function($self, $params, $chain) {
	$entity = $params['entity'];

	$type = $entity->type();
	// region implicit
	$original = $entity->dynamic;

	foreach ($type['fields'] as $name => $field) {
		$dynamic[$name] = [
			'value' => isset($original[$name]) ? $original[$name] : null,
			'type' => $field['type'],
			'length' => $field['length']
		];
	}

	// Serialized by behavior.
	return $chain->next($self, $params, $chain);
});
 */
?>