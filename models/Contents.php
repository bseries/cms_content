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

// trigger_error('cms_content\models\Contents is deprecated use Blocks instead.', E_USER_DEPRECATED);

// @deprecated
class Contents extends \cms_content\models\Blocks {

	// @deprecated
	public static function registerRegion($region, array $options = []) {
		trigger_error('Use Regions::register() instead.', E_USER_DEPRECATED);
		return \cms_content\models\Regions::register($region, $options);
	}

	// @deprecated
	public static function regions() {
		trigger_error('Use Regions::find(\'all\') instead.', E_USER_DEPRECATED);
		return \cms_content\models\Regions::find('all');
	}

	// @deprecated
	public static function registerType($name, array $options = []) {
		trigger_error('Use Types::register() instead.', E_USER_DEPRECATED);
		return \cms_content\models\Types::register($name, $options);
	}

	// @deprecated
	public static function types() {
		trigger_error('Use Types::find(\'all\') instead.', E_USER_DEPRECATED);
		return \cms_content\models\Regions::find('all');
	}
}


?>