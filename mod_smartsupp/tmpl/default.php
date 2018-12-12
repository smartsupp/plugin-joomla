<?php
/**
 * Smartsupp Live Chat integration module.
 * 
 * @package   Smartsupp
 * @author    Smartsupp <vladimir@smartsupp.com>
 * @link      http://www.smartsupp.com
 * @copyright 2015 Smartsupp.com
 * @license   GPL-2.0+
 *
 * Plugin Name:       Smartsupp Live Chat
 * Plugin URI:        http://www.smartsupp.com
 * Description:       Adds Smartsupp Live Chat code to Joomla.
 * Version:           3.0.0
 * Author:            Smartsupp
 * Author URI:        http://www.smartsupp.com
 * Text Domain:       smartsupp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
?>

<?php
    defined('_JEXEC') or die;
    $user = JFactory::getUser();
?>

<?php
    if(empty($smartsupp_key) || $smartsupp_key=="0")
    {
        echo "<a href='https://www.smartsupp.com' target='_blank'>Sign up Smartsupp</a>";
    }
    else
    {
    ?>
    <div class="mod_smartsupp">
    <?php	
        $smartsupp_cookie_domain = '.' . str_replace(array('http://', 'https://'), array(), JURI::base());
        if(!$user->guest) {
            if ($enableVariables) {
                $smartsupp_variables_js = '';
                $smartsupp_dashboard_name = $user->name;
                $smartsupp_dashboard_email = $user->email;
                if ($showId) {
                    $smartsupp_variables_js .= 'id : {label: "' . ('COM_MOD_SMARTSUPP_ID' == JText::_('COM_MOD_SMARTSUPP_ID') ? 'Id' : JText::_('COM_MOD_SMARTSUPP_ID')) . '", value: "' . $user->id . '"},';
                }
                if ($showName) {
                    $smartsupp_variables_js .= 'name : {label: "' . ('COM_MOD_SMARTSUPP_NAME' == JText::_('COM_MOD_SMARTSUPP_NAME') ? 'Name' : JText::_('COM_MOD_SMARTSUPP_NAME')) . '", value: "' . $user->name . '"},';
                }
                if ($showEmail) {
                    $smartsupp_variables_js .= 'email : {label: "' . ('COM_MOD_SMARTSUPP_EMAIL' == JText::_('COM_MOD_SMARTSUPP_EMAIL') ? 'Email' : JText::_('COM_MOD_SMARTSUPP_EMAIL')) . '", value: "' . $user->email . '"}, ';
                }

                $db = JFactory::getDbo();
                $db->setQuery("SELECT enabled FROM #__extensions WHERE element = 'com_virtuemart'");
                $isVmEnabled = $db->loadResult();
                
                if ($isVmEnabled) {
                    if (!class_exists( 'VmConfig' ))  {
                        require(JPATH_ADMINISTRATOR .'/components/com_virtuemart/helpers/config.php');
                    }                
                    if (!class_exists( 'VmModel' )) {
                        require(JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/vmmodel.php');
                    }
                    VmConfig::loadConfig();
                    $userModel = VmModel::getModel('User');
                    $vm_user = $userModel->getCurrentUser();

                    if ($showRole) {
                        $group = null;
                        $shopperGroupModel = VmModel::getModel('ShopperGroup');
                        foreach ($vm_user->shopper_groups as $shopper_group) {
                            $vm_shopper_group = $shopperGroupModel->getShopperGroup($shopper_group);
                            $group[] = $vm_shopper_group->shopper_group_name;
                        }
                        $smartsupp_variables_js .= 'role : {label: "' . ('COM_MOD_SMARTSUPP_ROLE' == JText::_('COM_MOD_SMARTSUPP_ROLE') ? 'Role' : JText::_('COM_MOD_SMARTSUPP_ROLE')) . '", value: "' . implode(',', $group) . '"}, ';
                    }
                    if ($showPhone) {
                        $phone = $vm_user->userInfo[1]->phone_1 ? $vm_user->userInfo[1]->phone_1 : $vm_user->userInfo[1]->phone_2;
                        $smartsupp_variables_js .= 'phone : {label: "' . ('COM_MOD_SMARTSUPP_PHONE' == JText::_('COM_MOD_SMARTSUPP_PHONE') ? 'Phone' : JText::_('COM_MOD_SMARTSUPP_PHONE')) . '", value: "' . $phone . '"}, ';
                    }
                    if ($showSpendings || $showOrders) {
                        $currency = CurrencyDisplay::getInstance();                    
                        $orderModel = VmModel::getModel('Orders');
                        $orders = $orderModel->getOrdersList($user->id);
                        $count = 0;
                        $spendings = 0;
                        foreach ($orders as $order) {
                            if (in_array($order->order_status, array('F', 'C', 'U'))) {
                                $count++;
                                $spendings += $order->order_total;
                            }
                        }
                        if ($showSpendings) {
                            $smartsupp_variables_js .= 'spendings : {label: "' . ('COM_MOD_SMARTSUPP_SPENDINGS' == JText::_('COM_MOD_SMARTSUPP_SPENDINGS') ? 'Spendings' : JText::_('COM_MOD_SMARTSUPP_SPENDINGS')) . '", value: "' . $currency->priceDisplay($spendings) . '"}, ';
                        }
                        if ($showOrders) {
                            $smartsupp_variables_js .= 'orders : {label: "' . ('COM_MOD_SMARTSUPP_ORDERS' == JText::_('COM_MOD_SMARTSUPP_ORDERS') ? 'Orders' : JText::_('COM_MOD_SMARTSUPP_ORDERS')) . '", value: "' . $count . '"}, ';
                        }
                    }
                }
                $smartsupp_variables_js = trim($smartsupp_variables_js, ', ');
            } else {
                $smartsupp_dashboard_name = '';
                $smartsupp_dashboard_email = '';
                $smartsupp_variables_enabled = '0';
                $smartsupp_variables_js = '';
            }
        }

        $script = '<!-- Smartsupp Live Chat script -->';
        $script .= '<script type="text/javascript">';
        if ($enableVariables && !empty($smartsupp_variables_js)) {
            $script .= "var prSmartsuppVars = {" . $smartsupp_variables_js . "};";
        }
        $script .= "var _smartsupp = _smartsupp || {};";
        $script .= "_smartsupp.key = '" . $smartsupp_key . "';";
        $script .= "_smartsupp.cookieDomain = '" . $smartsupp_cookie_domain . "';";
        $script .= "window.smartsupp||(function(d) {";
        $script .= "var s,c,o=smartsupp=function(){o._.push(arguments)};o._=[];";
        $script .= "s=d.getElementsByTagName('script')[0];c=d.createElement('script');";
        $script .= "c.type='text/javascript';c.charset='utf-8';c.async=true;";
        $script .= "c.src='//www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);";
        $script .= "})(document);";
        $script .= "smartsupp('name', '" . $smartsupp_dashboard_name . "');";
        $script .= "smartsupp('email', '" . $smartsupp_dashboard_email . "');";
        if ($enableVariables && !empty($smartsupp_variables_js)) {
            $script .= "smartsupp('variables', prSmartsuppVars);";
        }
        if (isset($smartsupp_optional_api)) {
            $script .= $smartsupp_optional_api;
        }
        $script .= '</script>';	
        echo $script;
    ?>	
    </div>
    <?php
    }