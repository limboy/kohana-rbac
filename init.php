<?php
Route::set('rbac', 'admin/rbac(/<action>(/<param>))')
	->defaults(array(
		'directory' => 'admin',
		'controller' => 'rbac',
	));
