<?php
/**
 * Smartsupp Live Chat integration module.
 * 
 * @package   Smartsupp
 * @author    Smartlook <vladimir@smartsupp.com>
 * @link      http://www.smartsupp.com
 * @copyright 2015 Smartsupp.com
 * @license   GPL-2.0+
 *
 * Plugin Name:       Smartsupp
 * Plugin URI:        http://www.smartsupp.com
 * Description:       Adds Smartupp Live Chat code to Joomla.
 * Version:           1.0.0
 * Author:            Smartsupp
 * Author URI:        http://www.smartsupp.com
 * Text Domain:       smartlook
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
?>

<?php
defined('_JEXEC') or die('Restricted access');

$smartsupp_key = $params->get('smartsupp_key', '');
$smartsupp_variables = $params->get('smartsupp_variables', '');
$smartsupp_id = $params->get('smartsupp_id', '');
$smartsupp_name = $params->get('smartsupp_name', '');
$smartsupp_email = $params->get('smartsupp_email', '');
$smartsupp_phone = $params->get('smartsupp_phone', '');
$smartsupp_role = $params->get('smartsupp_role', '');
$smartsupp_spendings = $params->get('smartsupp_spendings', '');
$smartsupp_orders = $params->get('smartsupp_orders', '');
$smartsupp_optional_api = $params->get('smartsupp_optional_api', '');

$enableVariables = false;
if($smartsupp_variables == "yes")
{
    $enableVariables = true;
}

$showId = false;
if($smartsupp_id == "yes")
{
    $showId = true;
}

$showName = false;
if($smartsupp_name == "yes")
{
    $showName = true;
}

$showEmail = false;
if($smartsupp_email == "yes")
{
    $showEmail = true;
}

$showPhone = false;
if($smartsupp_phone == "yes")
{
    $showPhone = true;
}

$showRole = false;
if($smartsupp_role == "yes")
{
    $showRole = true;
}

$showSpendings = false;
if($smartsupp_spendings == "yes")
{
    $showSpendings = true;
}

$showOrders = false;
if($smartsupp_orders == "yes")
{
    $showOrders = true;
}

require(JModuleHelper::getLayoutPath('mod_smartsupp'));