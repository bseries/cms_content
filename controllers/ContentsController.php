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

use lithium\g11n\Message;
use li3_flash_message\extensions\storage\FlashMessage;
use cms_content\models\Contents;

class ContentsController extends \cms_core\controllers\BaseController {

	use \cms_core\controllers\AdminEditTrait;
	use \cms_core\controllers\AdminDeleteTrait;

	use \cms_core\controllers\AdminPublishTrait;

	public function admin_index() {
		$model = $this->_model;

		$data = $model::find('all', [
			'order' => ['region' => 'ASC', 'created' => 'DESC']
		]);
		return compact('data');
	}

	public function admin_add() {
		extract(Message::aliases());

		$model = $this->_model;
		$model::pdo()->beginTransaction();

		$redirectUrl = $this->_redirectUrl + [
			'action' => 'index', 'library' => $this->_library
		];

		$item = $model::create([
			'type' => $this->request->contentType
		]);

		if ($this->request->data) {
			if ($item->save($this->request->data)) {
				$model::pdo()->commit();
				FlashMessage::write($t('Successfully saved.'), ['level' => 'success']);
				return $this->redirect($redirectUrl);
			} else {
				$model::pdo()->rollback();
				FlashMessage::write($t('Failed to save.'), ['level' => 'error']);
			}
		}
		$this->_render['template'] = 'admin_form';
		return compact('item') + $this->_selects($item);
	}

	protected function _selects($item = null) {
		$regions = [];
		foreach (Contents::regions() as $name => $item) {
			$regions[$name] = $item['title'];
		}
		return compact('regions');
	}
}

?>