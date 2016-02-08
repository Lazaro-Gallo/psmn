<?php

class ContactController extends Vtx_Action_Abstract
{
    public function init()
    {
        $this->_helper->getHelper('ajaxContext')
            ->addActionContext('index', array('json'))
            ->initContext();
    }

    
    public function indexAction()
    {
        if (!$this->getRequest()->isPost()) {
            return;
        }
        
        $Configuration = new Model_Configuration;
        $data = $this->getRequest()->getParams();
        // Sandra - acessar ciclo atual
        $currentYearRow = $Configuration->getConfigurationByConfKey('competitionIdKey');
        $this->view->ciclo = $currentYearRow->getConfValue();
        $to = 'mulherdenegocios@fnq.org.br';
        $subject = 'Mulher de Negócios 2014 :: Contado enviado pelo site';
        $message = nl2br($data['contact']['message']);

        Model_EmailMessage::createQuicklyWithRecipients('contact_notification', $subject, $message, array($to));
        
        $this->view->itemSendSuccess = true;
    }
    
    public function perfAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $Model = new Model_City;
        $cities = $Model->getAll();
        foreach ($cities as $key => $city) {
            Zend_Debug::dump($city->getName());
            
            //$city->findParentState()->getName()
        }

    }
}
