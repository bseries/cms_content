<?php
/**
 * CMS Content
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
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

use lithium\net\http\Router;

Router::scope('admin', function() {
	$persist = ['admin', 'controller', 'library'];

	Router::connect("/admin/cms-content/locks/add/region:{:region:[a-z\.\-\_\:]+}", [
		'controller' => 'blocks',
		'action' => 'add',
		'library' => 'cms_content',
		'admin' => true
	], compact('persist'));
});

?>