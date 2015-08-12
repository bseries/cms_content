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

namespace cms_content\controllers;

use base_core\extensions\cms\Settings;
use base_core\models\Users;
use base_core\security\Gate;
use cms_content\models\Regions;
use li3_access\security\AccessDeniedException;
use li3_flash_message\extensions\storage\FlashMessage;
use lithium\g11n\Message;

class BlocksController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\AdminPublishTrait;

	public function admin_add() {
		extract(Message::aliases());

		$model = $this->_model;
		$model::pdo()->beginTransaction();

		$item = $model::create([
			'region' => $this->request->region,
			// Will not be saved without error when there is no such field.
			'owner_id' => Gate::user(true, 'id')
		]);
		$item->type = $item->region()->type;

		if (!$item->region()->hasAccess(Gate::user())) {
			throw new AccessDeniedException('No access to region.');
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

		$useOwner = Settings::read('security.checkOwner');
		$useOwner = $useOwner && Gate::checkRight('owner');
		if ($useOwner) {
			$users = Users::find('list', [
				'order' => 'name',
				'conditions' => ['is_active' => true]
			]);
		}

		$this->_render['template'] = 'admin_form';
		return compact('item', 'users', 'useOwner') + $this->_selects($item);
	}

	protected function _selects($item = null) {
		$user = Gate::user(true);

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