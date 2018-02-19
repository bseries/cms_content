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
use lithium\util\Collection;
use BadMethodCallException;

class Type {

	protected $_config = [];

	public function __construct(array $config) {
		return $this->_config = $config + [
			'input' => function($context, $item) {},
			'format' => function($context, $item) {}
		];
	}

	public function __call($name, array $arguments) {
		if (!array_key_exists($name, $this->_config)) {
			throw new BadMethodCallException("Method or configuration `{$name}` does not exist.");
		}
		return $this->_config[$name];
	}

	public function title() {
		return is_callable($value = $this->_config[__FUNCTION__]) ? $value() : $value;
	}

	public function input($context, $item) {
		return $this->_config[__FUNCTION__]($context, $item);
	}

	// $type is either full or preview.
	public function format($context, $item, $type = 'full') {
		return $this->_config[__FUNCTION__]($context, $item, $type);
	}
}

?>