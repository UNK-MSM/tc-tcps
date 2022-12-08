<?php
if (!function_exists('printHello')) {
	
    function getMenuItems()
    {
		$data = array();
		$data[] = ['name' => 'user', 'route' => 'user.index'];
		$data[] = ['name' => 'system_configuration', 'route' => 'user.index'];

		return $data;

    }
}