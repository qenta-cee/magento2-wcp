<?php
/**
 * Shop System Plugins - Terms of Use
 *
 * The plugins offered are provided free of charge by Qenta Payment CEE GmbH
 * (abbreviated to Qenta CEE) and are explicitly not part of the Qenta CEE range of
 * products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 2 (GPLv2) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Qenta CEE does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the plugins at their own risk. Qenta CEE does not guarantee their full
 * functionality neither does Qenta CEE assume liability for any disadvantages related to
 * the use of the plugins. Additionally, Qenta CEE does not guarantee the full functionality
 * for customized shop systems or installed plugins of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the plugin's functionality before starting productive
 * operation.
 *
 * By installing the plugin into the shop system the customer agrees to these terms of use.
 * Please do not use the plugin if you do not agree to these terms of use!
 */

namespace Qenta\CheckoutPage\Controller\Adminhtml\Support;

use Magento\Backend\App\Action\Context;


class Sendrequest extends \Magento\Backend\App\Action
{
    /**
     * @var \Qenta\CheckoutPage\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Qenta\CheckoutPage\Model\Support
     */
    protected $_supportModel;

    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $_resultPageFactory;

    public function __construct(
        Context $context,
        \Qenta\CheckoutPage\Model\Support $supportModel,
        \Qenta\CheckoutPage\Helper\Data $dataHelper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_supportModel         = $supportModel;
        $this->_dataHelper        = $dataHelper;
        $this->_resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $redirectUrl = $this->getUrl('qentacheckoutpage/support/contact');

        if (!($data = $this->getRequest()->getPostValue())) {
            $this->_redirect($redirectUrl);
            return;
        }

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($data);

        try {
            $this->_supportModel->sendrequest($postObject);
            $this->messageManager->addNoticeMessage($this->_dataHelper->__('Support request sent successfully!'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect($redirectUrl);
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Payment::payment');
    }
}
