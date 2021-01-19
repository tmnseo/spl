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
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /*
    Add to megalog changing vendor status
    */
    if ($mode == 'update_status') {
        if (!empty($_REQUEST['status']) && !empty($_REQUEST['id'])) {
            $types = fn_get_schema('cp_ml', 'types');
            if (!empty($types) && !empty($types['companies']) && !empty($types['companies']['update_status'])) {
                $req_data = array(
                    'company_id' => $_REQUEST['id'],
                    'label' => __("status") . ': ' . $_REQUEST['status'],
                );
                $put_data = array(
                    'controller' => 'companies',
                    'mode' => 'update_status',
                    'method' => 'post',
                    'timestamp' => time(),
                    'user_id' => !empty(Tygh::$app['session']['auth']['user_id']) ? Tygh::$app['session']['auth']['user_id'] : 0,
                    'object_id' => $_REQUEST['id'],
                    'request' => json_encode($req_data)
                );
                fn_cp_megalog_ml_add_log($put_data);
            }
        }
    }
}
