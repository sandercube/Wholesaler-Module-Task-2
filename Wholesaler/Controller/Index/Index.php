<?php

namespace Sander\Wholesaler\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomCart;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $productRepository;
    protected $cart;
    protected $messageManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        CustomCart $cart,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->cart              = $cart;
        $this->messageManager    = $messageManager;
    }

    public function execute()
    {
        if ($this->getRequest()->getPost('sku')) {
            $sku = $this->getRequest()->getPost('sku');
            $qty = $this->getRequest()->getPost('qty');
            try {
                $product = $this->productRepository->get($sku);
                $params  = array(
                    'qty' => $qty,
                );
                $this->cart->addProduct($product->getId(), $params);
                $this->cart->save();
                $this->messageManager->addSuccessMessage('Товар успешно добавлен');
                $this->_redirect($this->_redirect->getRefererUrl());
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage('Введите SKU и повторите попытку');
                $this->_redirect($this->_redirect->getRefererUrl());
            }
        } else {
            $this->messageManager->addErrorMessage('Введите SKU и повторите попытку');

            return $resultPage = $this->resultPageFactory->create();
        }
    }
}
