<?php
/*****************************************************************************
*                                                        © 2013 Cart-Power   *
*           __   ______           __        ____                             *
*          / /  / ____/___ ______/ /_      / __ \____ _      _____  _____    *
*      __ / /  / /   / __ `/ ___/ __/_____/ /_/ / __ \ | /| / / _ \/ ___/    *
*     / // /  / /___/ /_/ / /  / /_/_____/ ____/ /_/ / |/ |/ /  __/ /        *
*    /_//_/   \____/\__,_/_/   \__/     /_/    \____/|__/|__/\___/_/         *
*                                                                            *
*                                                                            *
* -------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license *
* and  accept to the terms of the License Agreement can install and use this *
* program.                                                                   *
* -------------------------------------------------------------------------- *
* website: https://store.cart-power.com                                      *
* email:   sales@cart-power.com                                              *
******************************************************************************/

$schema['central']['cart_power_addons'] = array(
	'items' => array(
		'cp_addons_manager' => array(
			'href' => 'cp_addons_manager.manage',
			'position' => 0,
            'subitems' => array(
                'cp_my_addons' => array(
                    'href' => 'cp_addons_manager.manage',
                    'position' => 100
                ),
                'cp_all_addons' => array(
                    'href' => 'cp_addons_manager.all',
                    'position' => 200
                )
            )
		)
	),
	'position' => 90000
);

return $schema;
