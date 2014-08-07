<?php
/**
 * Bureau Content
 *
 * Copyright (c) 2013-2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace cms_content\controllers;

class ContentsController extends \cms_core\controllers\BaseController {

	use \cms_core\controllers\AdminAddTrait;
	use \cms_core\controllers\AdminEditTrait;
	use \cms_core\controllers\AdminDeleteTrait;

	use \cms_core\controllers\AdminPublishTrait;

	public function admin_index() {
		$model = $this->_model;

		$data = $model::find('all', [
			'order' => ['created' => 'DESC']
		]);
		return compact('data');
	}
}

?>