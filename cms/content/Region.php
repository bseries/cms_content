<?php
/**
 * Copyright 2013 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace cms_content\cms\content;

use Exception;
use li3_access\security\Access;

class Region {

	protected $_config = [];

	public function __construct(array $config) {
		if (!isset($config['type'])) {
			$message = 'You must provide a type when registering a region.';
			throw new Exception($message);
		}
		if (isset($config['access'])) {
			$config['access'] = (array) $config['access'];
		}
		return $this->_config = $config + [
			// The (display) title of the method, can also be an anonymous function.
			'title' => null,

			// one of the registered type
			'type' => null,

			// Set of conditions of which any must be fulfilled, so
			// that the method is made available to a user.
			'access' => ['user.role:admin']
		];
	}

	public function __call($name, array $arguments) {
		if (!array_key_exists($name, $this->_config)) {
			throw new BadMethodCallException("Method or configuration `{$name}` does not exist.");
		}
		return $this->_config[$name];
	}

	public function hasAccess($user) {
		return Access::check(
			'entity',
			$user,
			$this,
			$this->_config['access']
		);
	}

	public function title() {
		return is_callable($value = $this->_config[__FUNCTION__]) ? $value() : $value;
	}
}

?>