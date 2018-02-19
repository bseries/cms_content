<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace cms_content\config;

use base_core\extensions\cms\Panes;
use lithium\g11n\Message;

extract(Message::aliases());

Panes::register('cms.contents', [
	'title' => $t('Content Blocks', ['scope' => 'cms_content']),
	'url' => ['controller' => 'blocks', 'action' => 'index', 'library' => 'cms_content', 'admin' => true],
	'weight' => 60
]);

?>