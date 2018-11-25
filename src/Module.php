<?php
namespace PpitStudies;

use PpitCore\Model\GenericTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module
{
     public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                Model\AbsenceTable::class =>  function($sm) {
                    $tableGateway = $sm->get(Model\AbsenceTableGateway::class);
                    return new GenericTable($tableGateway);
                },
                Model\AbsenceTableGateway::class => function ($sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);
                	$resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Absence());
                    return new TableGateway('student_absence', $dbAdapter, null, $resultSetPrototype);
                },
                Model\NoteTable::class =>  function($sm) {
                    $tableGateway = $sm->get(Model\NoteTableGateway::class);
                    return new GenericTable($tableGateway);
                },
                Model\NoteTableGateway::class => function ($sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);
                	$resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Note());
                    return new TableGateway('student_note', $dbAdapter, null, $resultSetPrototype);
                },
                Model\NoteLinkTable::class =>  function($sm) {
                    $tableGateway = $sm->get(Model\NoteLinkTableGateway::class);
                    return new GenericTable($tableGateway);
                },
                Model\NoteLinkTableGateway::class => function ($sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);
                	$resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\NoteLink());
                    return new TableGateway('student_note_link', $dbAdapter, null, $resultSetPrototype);
                },
                Model\ProgressTable::class =>  function($sm) {
                    $tableGateway = $sm->get(Model\ProgressTableGateway::class);
                    return new GenericTable($tableGateway);
                },
                Model\ProgressTableGateway::class => function ($sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);
                	$resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Progress());
                    return new TableGateway('student_progress', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
