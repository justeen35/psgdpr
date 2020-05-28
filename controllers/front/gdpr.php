<?php
/**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class psgdprgdprModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->display_column_right = false;
        $this->display_column_left = false;
        $context = Context::getContext();
        if (empty($context->customer->id)) {
            Tools::redirect('index.php');
        }

        parent::initContent();

        $ps_version = (bool) version_compare(_PS_VERSION_, '1.7', '>=');

        $params = [
            'psgdpr_token' => sha1($context->customer->secure_key),
        ];

        $this->context->smarty->assign([
            'psgdpr_contactUrl' => $this->context->link->getPageLink('contact', true, $this->context->language->id),
            'psgdpr_front_controller' => Context::getContext()->link->getModuleLink('psgdpr', 'gdpr', $params, true),
            'psgdpr_csv_controller' => Context::getContext()->link->getModuleLink('psgdpr', 'ExportDataToCsv', $params, true),
            'psgdpr_pdf_controller' => Context::getContext()->link->getModuleLink('psgdpr', 'ExportDataToPdf', $params, true),
            'psgdpr_ps_version' => (bool) version_compare(_PS_VERSION_, '1.7', '>='),
            'psgdpr_id_customer' => Context::getContext()->customer->id,
        ]);

        $this->context->smarty->tpl_vars['page']->value['body_classes']['page-customer-account'] = true;

        $this->setTemplate('module:psgdpr/views/templates/front/customerPersonalData.tpl');
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $breadcrumb['links'][] = $this->addMyAccountToBreadcrumb();

        return $breadcrumb;
    }

    public function setMedia()
    {
        $js_path = $this->module->getPathUri() . '/views/js/';
        $css_path = $this->module->getPathUri() . '/views/css/';

        parent::setMedia();
        $this->context->controller->addJS($js_path . 'front.js');
        $this->context->controller->addCSS($css_path . 'customerPersonalData.css');
    }
}
