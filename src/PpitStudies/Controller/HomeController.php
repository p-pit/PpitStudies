<?php
namespace PpitStudies\Controller;

use PpitCore\Model\Context;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Mvc\Controller\AbstractActionController;

class HomeController extends AbstractActionController
{
	public function indexAction()
	{
		// Retrieve the context
		$context = Context::getCurrent();

		if ($currentUser->isAllowed('instance')) return $this->redirect()->toRoute('instance');
		elseif ($currentUser->isAllowed('eleve')) {
    		return $this->redirect()->toRoute('eleve', array(
                'action' => 'index',
    		));
    	}
    	elseif ($currentUser->isAllowed('learningEvaluation')) {
    	    	return $this->redirect()->toRoute('learningEvaluation');
    	}

        $eleve = Student::getTable()->get($currentUser->perimetre);
	       	if (!$eleve) {
       		return $this->redirect()->toRoute('ppitUser/logout', array(
       				'action' => 'logout',
       		));
       	}
        $select = Evaluation::getTable()->getSelect()
    		->where(array('learner_id' => $eleve->id))
    		->order(array('period DESC', 'subject'));
    	$evaluations = Evaluation::getTable()->selectWith($select);
    	$resultSports = ResultSport::getTable()->get($eleve->id);
    	$absences = ReleveEleve::getTable()->get($eleve->id);
    	$rdvs = SanteRdv::getTable()->get($eleve->id);
       		   
	   // Prepare the SQL request
	   $major = $this->params()->fromQuery('major', NULL);
	   if (!$major) $major = 'name';
	   $dir = $this->params()->fromQuery('dir', NULL);
	   if (!$dir) $dir = 'ASC';
	   $select = Link::getTable()->getSelect();
	   $select->where(array('parent_id' => $currentUser->fs_root->id));
	   $select->order(array($major.' '.$dir, 'name', 'uploaded_time DESC'));
	   
	   // Execute the request
	   $links = Link::getTable()->selectWith($select);
	   
	   return new ViewModel(array(
	   			'context' => $context,
				'config' => $context->getconfig(),
	   			'eleve' => $eleve,
    			'evaluations' => $evaluations,
    			'resultSports' => $resultSports,
		   		'rdvs' => $rdvs,
    			'absences' => $absences,
	   			'complet' => $this->params()->fromQuery('complet', NULL),
		   		'links' => $links,
	   			'parent' => $currentUser->fs_root,
    			'major' => $major,
    			'dir' => $dir,
	   			'id' => null,
	   	));
	}
}
