<?php
/**
 * Copyright 2013 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace cms_content\controllers;

use base_core\extensions\cms\Settings;
use base_core\security\Gate;
use cms_content\cms\content\Regions;
use li3_access\security\AccessDeniedException;
use li3_flash_message\extensions\storage\FlashMessage;
use lithium\g11n\Message;

class BlocksController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminEditTrait {
		\base_core\controllers\AdminEditTrait::admin_edit as protected _admin_edit;
	}
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\AdminPublishTrait;
	use \base_core\controllers\UsersTrait;

	// Overridden as not the content block itself carries access rights
	// but the region which belongs to each content block.
	public function admin_add() {
		extract(Message::aliases());

		$model = $this->_model;
		$model::pdo()->beginTransaction();

		$item = $model::create([
			'region' => $this->request->region,
			// Will not be saved without error when there is no such field.
			'owner_id' => Gate::user(true, 'id')
		]);
		$item->type = $item->region()->type();

		if (!$item->region()->hasAccess(Gate::user(true))) {
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
		$isTranslated = $model::hasBehavior('Translatable');
		$isDeletable = $item->region()->hasAccess(Gate::user(true));

		$useOwner = Settings::read('security.checkOwner');
		$useOwner = $useOwner && Gate::checkRight('owner');
		if ($useOwner) {
			$users = $this->_users($item, ['field' => 'owner_id']);
		}

		$this->_render['template'] = 'admin_form';
		return compact('item', 'users', 'useOwner', 'isTranslated', 'isDeletable') + $this->_selects($item);
	}

	// Overridden to provide isDeletable feature.
	public function admin_edit() {
		if (is_array($return = $this->_admin_edit())) {
			$return['isDeletable'] = $return['item']->region()->hasAccess(Gate::user(true));
		}
		return $return;
	}

	protected function _selects($item = null) {
		$user = Gate::user(true);

		$regions = Regions::registry(true)
			->find(function($item) use ($user) {
				return $item->hasAccess($user);
			})
			->map(function($item) {
				return $item->title();
			})->to('array');

		return compact('regions', 'users');
	}
}

?>