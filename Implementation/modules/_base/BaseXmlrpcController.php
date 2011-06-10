<?php
class BaseXmlrpcController extends Warecorp_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->params = $this->_getAllParams();
    }   
    public function indexAction()
    {
        $server = new Zend_XmlRpc_Server();
        $server->setClass('Warecorp_Api_Xmlrpc_Zanby_Group', 'group');
        print $server->handle();
        exit;
    }
    
    public function clientAction()
    {
        Warecorp::addTranslation('/modules/xmlrpc/xmlrpc.controller.php.xml');
        $client = new Zend_XmlRpc_Client('http://zanby.sukharev.buick/en/xmlrpc/');
        
        $server = $client->getProxy();
        try {
            if ( null !== $groupID = $server->group->getGroupIdByUrl('http://php-development-group.groups.zanby.sukharev.buick/ru/summary/') ) {
                print Warecorp::t('Group Name : ') . $server->group->getGroupName($groupID) . '<br>';
                print Warecorp::t('Members Count : ') . $server->group->getMembersCount($groupID) . '<br>';
            }
        } catch (Exception $ex) {
            print $ex->getMessage(); exit;
        }
        
        Zend_Debug::dump($client->call('system.listMethods'));
        exit;
    }
}
