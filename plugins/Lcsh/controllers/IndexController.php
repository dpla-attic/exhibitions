<?php
class Lcsh_IndexController extends Omeka_Controller_Action
{
    const LCSH_SUGGEST_URL = 'http://id.loc.gov/authorities/suggest/';
    
    public function lcshProxyAction()
    {
        $client = new Zend_Http_Client();
        $client->setUri(self::LCSH_SUGGEST_URL);
        $client->setParameterGet('q', $this->getRequest()->getParam('term'));
        $json = json_decode($client->request()->getBody());
        $this->_helper->json($json[1]);
    }
}
