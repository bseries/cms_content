<?php
/**
 * CMS Content
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

use lithium\net\http\Router;

$persist = ['admin', 'controller', 'library'];

Router::connect("/admin/cms-content/contents/add/region:{:region:[a-z\.\-\_]+}", [
	'controller' => 'contents',
	'action' => 'add',
	'library' => 'cms_content',
	'admin' => true
], compact('persist'));

?>