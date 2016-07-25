<?php
namespace PpitStudies;

use PpitCore\Model\GenericTable;
use PpitStudies\Model\Student;
use PpitStudies\Model\StudentSport;
use PpitStudies\Model\StudentSportImport;
use PpitStudies\Model\UserImport;
use PpitStudies\Model\VcardImport;
use PpitStudies\Model\MyAuthStorage;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'PpitStudies\Model\StudentTable' =>  function($sm) {
                    $tableGateway = $sm->get('StudentTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'StudentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Student());
                    return new TableGateway('student', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\StudentSportTable' =>  function($sm) {
                    $tableGateway = $sm->get('StudentSportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'StudentSportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new StudentSport());
                    return new TableGateway('student_sport', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\StudentSportImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('StudentSportImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'StudentSportImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new StudentSportImport());
                    return new TableGateway('eleve', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\UserImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'UserImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserImport());
                    return new TableGateway('eleve_user', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\VcardImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('VcardImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'VcardImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new VcardImport());
                    return new TableGateway('eleve_contact_vcard', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
