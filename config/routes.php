<?php
/**
 * Bureau Node
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

use lithium\net\http\Router;

Router::connect('/admin/nodes/{:action}:{:nodeType}', [
	'controller' => 'nodes', 'library' => 'cms_core', 'admin' => true
], $persist);
Router::connect('/admin/nodes/{:action}/{:id:[0-9]+}', [
	'controller' => 'nodes', 'library' => 'cms_core', 'admin' => true
], $persist);
Router::connect('/admin/nodes/{:action}/{:args}', [
	'controller' => 'nodes', 'library' => 'cms_core', 'admin' => true
], $persist);

?>