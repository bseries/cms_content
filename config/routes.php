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

$persist = ['persist' => ['admin', 'controller']];

Router::connect('/admin/contents/{:action}:{:contentType}', [
	'controller' => 'contents', 'library' => 'cms_content', 'admin' => true
], $persist);
Router::connect('/admin/contents/{:action}/{:id:[0-9]+}', [
	'controller' => 'contents', 'library' => 'cms_content', 'admin' => true
], $persist);
Router::connect('/admin/contents/{:action}/{:args}', [
	'controller' => 'contents', 'library' => 'cms_content', 'admin' => true
], $persist);

?>