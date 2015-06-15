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

namespace cms_content\controllers;

use lithium\security\Auth;
use lithium\g11n\Message;
use li3_flash_message\extensions\storage\FlashMessage;
use cms_content\models\Contents;

class ContentsController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\AdminPublishTrait;

	public function admin_add() {
		extract(Message::aliases());
		$user = Auth::check('default');

		$model = $this->_model;
		$model::pdo()->beginTransaction();

		$item = $model::create([
			'region' => $this->request->region,
			// Set ownership.
			'user_id' => $user['id']
		]);
		$item->type = $item->region('type');

		$redirectUrl = ['action' => 'index', 'library' => $this->_library];

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