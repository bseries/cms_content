<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

use lithium\net\http\Router;

Router::scope('admin', function() {
	$persist = ['admin', 'controller', 'library'];

	Router::connect("/cms-content/locks/add/region:{:region:[a-z\.\-\_\:]+}", [
		'controller' => 'blocks',
		'action' => 'add',
		'library' => 'cms_content',
		'admin' => true
	], compact('persist'));
});

?>