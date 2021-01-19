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

$schema['controllers']['cp_waranty_cat'] = array(
    'modes' => array(
        'picker' => array(
            'permissions' => true
        ),
    ),
    'permissions' => false,
);
$schema['controllers']['companies']['modes']['add_warranty_cats']['permissions'] = true;
$schema['controllers']['companies']['modes']['update_war_brands']['permissions'] = true;
$schema['controllers']['companies']['modes']['cp_warranty_delete']['permissions'] = true;
$schema['controllers']['companies']['modes']['cp_warranty_delete_brand']['permissions'] = true;

return $schema;