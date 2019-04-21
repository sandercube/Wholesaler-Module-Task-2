<?php

namespace Sander\Wholesaler\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    private $actionFactory;
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->scopeConfig   = $scopeConfig;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier  = trim($request->getPathInfo(), '/');
        $configValue = $this->scopeConfig->getValue('settings/general/frontend_url');
        if ($identifier != $configValue) {
            return false;
        }
        $request->setModuleName('sanderwholesaler')->setControllerName('index')->setActionName('index');

        return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
    }
}
