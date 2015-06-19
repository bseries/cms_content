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
use base_core\security\Gate;
use lithium\g11n\Message;
use li3_flash_message\extensions\storage\FlashMessage;
use cms_content\models\Regions;
use li3_access\security\AccessDeniedException;
use base_core\models\Users;

class BlocksController extends \base_core\controllers\BaseController {

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
			'owner_id' => $user['id']
		]);
		$item->type = $item->region()->type;

		if (!$item->region()->hasAccess($user)) {
			throw new AccessDeniedException();
		}

		if ($this->request->data) {
			if ($item->save($this->request->data)) {
				$model::pdo()->commit();

				FlashMessage::write($t('Successfully saved.', ['scope' => 'cms_content']), [
					'level' => 'success'
				]);
				return $this->redirect(['action' => 'index']);
			} else {
				$model::pdo()->rollback();
				FlashMessage::write($t('Failed to save.', ['scope' => 'cms_content']), [
					'level' => 'error'
				]);
			}
		}
		$users = Users::find('list', ['order' => 'name']);
		$useOwner = Gate::check('users');

		$this->_render['template'] = 'admin_form';
		return compact('item', 'users', 'useOwner') + $this->_selects($item);
	}

	protected function _selects($item = null) {
		$user = Auth::check('default');

		$regions = Regions::find('all')
			->find(function($item) use ($user) {
				return $item->hasAccess($user);
			})
			->map(function($item) {
				return $item->title;
			})->to('array');

		return compact('regions', 'users');
	}
}

?>