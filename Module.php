<?php
namespace PpitStudies;

use PpitCore\Model\GenericTable;
use PpitStudies\Model\Absence;
use PpitStudies\Model\Note;
use PpitStudies\Model\Progress;
use PpitStudies\Model\Student;
use PpitStudies\Model\StudentSport;
use PpitStudies\Model\StudentSportImport;
use PpitStudies\Model\UserImport;
use PpitStudies\Model\VcardImport;
use CitAccounting\Model\Bill;
use CitAccounting\Model\BillRow;
use CitAccounting\Model\BillOption;
use CitAccounting\Model\BillTerm;
use CitAccounting\Model\Product;
use CitAccounting\Model\ProductOption;
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
                'PpitStudies\Model\AbsenceTable' =>  function($sm) {
                    $tableGateway = $sm->get('AbsenceTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'AbsenceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Absence());
                    return new TableGateway('student_absence', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\NoteTable' =>  function($sm) {
                    $tableGateway = $sm->get('NoteTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'NoteTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Note());
                    return new TableGateway('student_note', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\ProgressTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProgressTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'ProgressTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Progress());
                    return new TableGateway('student_progress', $dbAdapter, null, $resultSetPrototype);
                },
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
                'PpitStudies\Model\BillImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('BillImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'BillImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BillImport());
                    return new TableGateway('eleve_bill', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\BillRowImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('BillRowImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
/*                'BillRowImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BillRowImport());
                    return new TableGateway('eleve_bill_row', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\BillOptionImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('BillOptionImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'BillOptionImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BillOptionImport());
                    return new TableGateway('eleve_bill_option', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\BillTermImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('BillTermImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'BillTermImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BillTermImport());
                    return new TableGateway('eleve_bill_term', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\ProductImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProductImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'ProductImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ProductImport());
                    return new TableGateway('eleve_product', $dbAdapter, null, $resultSetPrototype);
                },
                'PpitStudies\Model\ProductOptionImportTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProductOptionImportTableGateway');
                    $table = new GenericTable($tableGateway);
                    return $table;
                },
                'ProductOptionImportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ProductOptionImport());
                    return new TableGateway('eleve_product_option', $dbAdapter, null, $resultSetPrototype);
                },*/
                ),
        );
    }
}
