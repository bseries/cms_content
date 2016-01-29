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
		return $this->_config['input']($context, $item);
	}

	// $type is either full or preview.
	public function format($context, $item, $type = 'full') {
		return $this->_config['input']($context, $item, $type);
	}
}

?>