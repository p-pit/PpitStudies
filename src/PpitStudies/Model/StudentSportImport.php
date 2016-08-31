<?php
namespace PpitStudies\Model;

use PpitCommitment\Model\Commitment;
use PpitCommitment\Model\Account;
use PpitContact\Model\Community;
use PpitContact\Model\Contract;
use PpitContact\Model\Contact;
use PpitContact\Model\Vcard;
use PpitCore\Model\Context;
use PpitDocument\Model\Document;
use PpitMasterData\Model\Place;
use PpitOrder\Model\Order;
use PpitStudies\Model\Student;
use PpitStudies\Model\StudentSport;
use PpitStudies\Model\UserImport;
use PpitStudies\Model\VcardImport;
use PpitUser\Model\User;
use Zend\db\sql\Where;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class StudentSportImport implements InputFilterAwareInterface
{
    public $id;
//    public $commitment_id;
    public $annee_scolaire;
    public $date_inscription;
    public $nom_famille;
    public $prenoms;
    public $sexe;
    public $date_naissance;
    public $lieu_naissance;
    public $nationalite;
    public $centre;
    public $sport;
    public $categorie;
    public $classe;
    public $specialite;
    public $internat;
    public $we_dimanche;
    public $formule;
    public $adresse;
    public $code_postal;
    public $ville;
    public $pays;
    public $email;
    public $num_portable;
    public $nb_age_frere_soeur;
    public $nom_repr_legal;
    public $email_repr_legal;
    public $num_repr_legal;
    public $sit_fam_parent;
    public $pere_nom;
    public $pere_adresse;
    public $pere_code_postal;
    public $pere_ville;
    public $pere_pays;
    public $pere_profession;
    public $pere_email;
    public $pere_num_portable;
    public $pere_num_fixe;
    public $mere_nom;
    public $mere_adresse;
    public $mere_code_postal;
    public $mere_ville;
    public $mere_pays;
    public $mere_profession;
    public $mere_email;
    public $mere_num_portable;
    public $mere_num_fixe;
    public $choix_adresse_facture;
    public $facture_nom;
    public $facture_adresse;
    public $facture_code_postal;
    public $facture_ville;
    public $facture_pays;
    public $caution;
    public $ass_med_mutuelle;
    public $photo_ci_passeport;
    public $recensement;
    public $visite_medicale;
    public $origine;
    public $observations;
    public $boo_licencie;
    public $niveau;
    public $resultats;
    public $boo_droitier;
    public $poste_princ;
    public $postes_sec;
    public $nom_club;
    public $adresse_club;
    public $nom_club_prec;
    public $nom_coach;
    public $num_coach;
    public $boo_non_sollicit;
    public $nom_contact;
    public $num_contact;
    public $classe_prec;
    public $etab_princ;
    public $moyenne_trim_prec;
    public $adresse_etab_prec;
    public $specialite_prevue;
    public $langue_mat;
    public $LV1;
    public $LV2;
    public $poids;
    public $taille;
    public $pointure;
    public $taille_vetement;
    public $ante_blessure;
    public $ante_fracture;
    public $ante_maladie;
    public $bilan_medical;
    public $bilan_musculaire;
    public $bilan_etirements;
    public $ecg;
    public $bilan_bucco_dentaire;
    public $medecin_referent;
    public $update_time;
    
    protected $inputFilter;

    // Static fields
    private static $table;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
//        $this->commitment_id = (isset($data['commitment_id'])) ? $data['commitment_id'] : null;
        $this->center_id = (isset($data['center_id'])) ? $data['center_id'] : null;
        $this->dossier_principal = (isset($data['dossier_principal'])) ? $data['dossier_principal'] : null;
	    $this->incomplet = (isset($data['incomplet'])) ? $data['incomplet'] : null;
        $this->annee_scolaire = (isset($data['annee_scolaire'])) ? $data['annee_scolaire'] : null;
        $this->date_inscription = (isset($data['date_inscription'])) ? $data['date_inscription'] : null;
        $this->nom_famille = (isset($data['nom_famille'])) ? $data['nom_famille'] : null;
	    $this->prenoms = (isset($data['prenoms'])) ? $data['prenoms'] : null;
	    $this->username = (isset($data['username'])) ? $data['username'] : null;
	    $this->sexe = (isset($data['sexe'])) ? $data['sexe'] : null;
	    $this->date_naissance = (isset($data['date_naissance'])) ? $data['date_naissance'] : null;
	    $this->lieu_naissance = (isset($data['lieu_naissance'])) ? $data['lieu_naissance'] : null;
        $this->nationalite = (isset($data['nationalite'])) ? $data['nationalite'] : null;
	    $this->centre = (isset($data['centre'])) ? $data['centre'] : null;
        $this->sport = (isset($data['sport'])) ? $data['sport'] : null;
        $this->categorie = (isset($data['categorie'])) ? $data['categorie'] : null;
        $this->classe = (isset($data['classe'])) ? $data['classe'] : null;
        $this->specialite = (isset($data['specialite'])) ? $data['specialite'] : null;
        $this->internat = (isset($data['internat'])) ? $data['internat'] : null;
        $this->formule = (isset($data['formule'])) ? $data['formule'] : null;
	    $this->adresse = (isset($data['adresse'])) ? $data['adresse'] : null;
	    $this->code_postal = (isset($data['code_postal'])) ? $data['code_postal'] : null;
	    $this->ville = (isset($data['ville'])) ? $data['ville'] : null;
	    $this->pays = (isset($data['pays'])) ? $data['pays'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
	    $this->num_portable = (isset($data['num_portable'])) ? $data['num_portable'] : null;
	    $this->nb_age_frere_soeur = (isset($data['nb_age_frere_soeur'])) ? $data['nb_age_frere_soeur'] : null;
	    $this->nom_repr_legal = (isset($data['nom_repr_legal'])) ? $data['nom_repr_legal'] : null;
	    $this->email_repr_legal = (isset($data['email_repr_legal'])) ? $data['email_repr_legal'] : null;
	    $this->num_repr_legal = (isset($data['num_repr_legal'])) ? $data['num_repr_legal'] : null;
	    $this->sit_fam_parent = (isset($data['sit_fam_parent'])) ? $data['sit_fam_parent'] : null;
	    $this->pere_nom = (isset($data['pere_nom'])) ? $data['pere_nom'] : null;
	    $this->pere_adresse = (isset($data['pere_adresse'])) ? $data['pere_adresse'] : null;
	    $this->pere_code_postal = (isset($data['pere_code_postal'])) ? $data['pere_code_postal'] : null;
	    $this->pere_ville = (isset($data['pere_ville'])) ? $data['pere_ville'] : null;
	    $this->pere_pays = (isset($data['pere_pays'])) ? $data['pere_pays'] : null;
	    $this->pere_profession = (isset($data['pere_profession'])) ? $data['pere_profession'] : null;
	    $this->pere_email = (isset($data['pere_email'])) ? $data['pere_email'] : null;
	    $this->pere_num_portable = (isset($data['pere_num_portable'])) ? $data['pere_num_portable'] : null;
	    $this->pere_num_fixe = (isset($data['pere_num_fixe'])) ? $data['pere_num_fixe'] : null;
	    $this->mere_nom = (isset($data['mere_nom'])) ? $data['mere_nom'] : null;
	    $this->mere_adresse = (isset($data['mere_adresse'])) ? $data['mere_adresse'] : null;
	    $this->mere_code_postal = (isset($data['mere_code_postal'])) ? $data['mere_code_postal'] : null;
	    $this->mere_ville = (isset($data['mere_ville'])) ? $data['mere_ville'] : null;
	    $this->mere_pays = (isset($data['mere_pays'])) ? $data['mere_pays'] : null;
	    $this->mere_profession = (isset($data['mere_profession'])) ? $data['mere_profession'] : null;
	    $this->mere_email = (isset($data['mere_email'])) ? $data['mere_email'] : null;
	    $this->mere_num_portable = (isset($data['mere_num_portable'])) ? $data['mere_num_portable'] : null;
	    $this->mere_num_fixe = (isset($data['mere_num_fixe'])) ? $data['mere_num_fixe'] : null;
	    $this->choix_adresse_facture = (isset($data['choix_adresse_facture'])) ? $data['choix_adresse_facture'] : null;
	    $this->facture_nom = (isset($data['facture_nom'])) ? $data['facture_nom'] : null;
	    $this->facture_adresse = (isset($data['facture_adresse'])) ? $data['facture_adresse'] : null;
	    $this->facture_code_postal = (isset($data['facture_code_postal'])) ? $data['facture_code_postal'] : null;
	    $this->facture_ville = (isset($data['facture_ville'])) ? $data['facture_ville'] : null;
	    $this->facture_pays = (isset($data['facture_pays'])) ? $data['facture_pays'] : null;
	    $this->paiement = (isset($data['paiement'])) ? $data['paiement'] : null;
	    $this->caution = (isset($data['caution'])) ? $data['caution'] : null;
	    $this->ass_med_mutuelle = (isset($data['ass_med_mutuelle'])) ? $data['ass_med_mutuelle'] : null;
	    $this->photo_ci_passeport = (isset($data['photo_ci_passeport'])) ? $data['photo_ci_passeport'] : null;
	    $this->recensement = (isset($data['recensement'])) ? $data['recensement'] : null;
	    $this->visite_medicale = (isset($data['visite_medicale'])) ? $data['visite_medicale'] : null;
	    $this->origine = (isset($data['origine'])) ? $data['origine'] : null;
	    $this->observations = (isset($data['observations'])) ? $data['observations'] : null;
	    // Sport
        $this->boo_licencie = (isset($data['boo_licencie'])) ? $data['boo_licencie'] : null;
        $this->niveau = (isset($data['niveau'])) ? $data['niveau'] : null;
        $this->resultats = (isset($data['resultats'])) ? $data['resultats'] : null;
        $this->boo_droitier = (isset($data['boo_droitier'])) ? $data['boo_droitier'] : null;
        $this->poste_princ = (isset($data['poste_princ'])) ? $data['poste_princ'] : null;
        $this->postes_sec = (isset($data['postes_sec'])) ? $data['postes_sec'] : null;
        $this->nom_club = (isset($data['nom_club'])) ? $data['nom_club'] : null;
        $this->adresse_club = (isset($data['adresse_club'])) ? $data['adresse_club'] : null;
        $this->nom_club_prec = (isset($data['nom_club_prec'])) ? $data['nom_club_prec'] : null;
        $this->nom_coach = (isset($data['nom_coach'])) ? $data['nom_coach'] : null;
        $this->num_coach = (isset($data['num_coach'])) ? $data['num_coach'] : null;
        $this->boo_nom_sollicit = (isset($data['boo_nom_sollicit'])) ? $data['boo_nom_sollicit'] : null;
        $this->nom_contact = (isset($data['nom_contact'])) ? $data['nom_contact'] : null;
        $this->num_contact = (isset($data['num_contact'])) ? $data['num_contact'] : null;
		// Etudes
        $this->classe_prec = (isset($data['classe_prec'])) ? $data['classe_prec'] : null;
        $this->etab_prec = (isset($data['etab_prec'])) ? $data['etab_prec'] : null;
        $this->moyenne_trim_prec = (isset($data['moyenne_trim_prec'])) ? $data['moyenne_trim_prec'] : null;
        $this->adresse_etab_prec = (isset($data['adresse_etab_prec'])) ? $data['adresse_etab_prec'] : null;
        $this->specialite_prevue = (isset($data['specialite_prevue'])) ? $data['specialite_prevue'] : null;
        $this->langue_mat = (isset($data['langue_mat'])) ? $data['langue_mat'] : null;
        $this->LV1 = (isset($data['LV1'])) ? $data['LV1'] : null;
        $this->LV2 = (isset($data['LV2'])) ? $data['LV2'] : null;
		// Santé
        $this->poids = (isset($data['poids'])) ? $data['poids'] : null;
        $this->taille = (isset($data['taille'])) ? $data['taille'] : null;
        $this->pointure = (isset($data['pointure'])) ? $data['pointure'] : null;
        $this->taille_vetement = (isset($data['taille_vetement'])) ? $data['taille_vetement'] : null;

        $this->update_time = (isset($data['update_time'])) ? $data['update_time'] : null;
    }

    public function toArray()
    {
    	$data = array();
    	$data['id'] = (int) $this->id;
//    	$data['commitment_id'] = (int) $this->commitment_id;
    	$data['annee_scolaire'] = $this->annee_scolaire;
    	$data['date_inscription'] = ($this->date_inscription) ? $this->date_inscription : null;
    	$data['nom_famille'] = $this->nom_famille;
    	$data['prenoms'] = $this->prenoms;
    	$data['sexe'] = $this->sexe;
    	$data['date_naissance'] = $this->date_naissance;
    	$data['lieu_naissance'] = $this->lieu_naissance;
    	$data['nationalite'] = $this->nationalite;
    	$data['centre'] = $this->centre;
    	$data['sport'] = $this->sport;
    	$data['categorie'] = $this->categorie;
    	$data['classe'] = $this->classe;
    	$data['specialite'] = $this->specialite;
    	$data['internat'] = $this->internat;
    	$data['we_dimanche'] = $this->we_dimanche;
    	$data['formule'] = $this->formule;
    	$data['adresse'] = $this->adresse;
    	$data['code_postal'] = $this->code_postal;
    	$data['ville'] = $this->ville;
    	$data['pays'] = $this->pays;
    	$data['email'] = $this->email;
    	$data['num_portable'] = $this->num_portable;
    	$data['nb_age_frere_soeur'] = $this->nb_age_frere_soeur;
    	$data['nom_repr_legal'] = $this->nom_repr_legal;
    	$data['email_repr_legal'] = $this->email_repr_legal;
    	$data['num_repr_legal'] = $this->num_repr_legal;
    	$data['sit_fam_parent'] = $this->sit_fam_parent;
    	$data['pere_nom'] = $this->pere_nom;
    	$data['pere_adresse'] = $this->pere_adresse;
    	$data['pere_code_postal'] = $this->pere_code_postal;
    	$data['pere_ville'] = $this->pere_ville;
    	$data['pere_pays'] = $this->pere_pays;
    	$data['pere_profession'] = $this->pere_profession;
    	$data['pere_email'] = $this->pere_email;
    	$data['pere_num_portable'] = $this->pere_num_portable;
    	$data['pere_num_fixe'] = $this->pere_num_fixe;
    	$data['mere_nom'] = $this->mere_nom;
    	$data['mere_adresse'] = $this->mere_adresse;
    	$data['mere_code_postal'] = $this->mere_code_postal;
    	$data['mere_ville'] = $this->mere_ville;
    	$data['mere_pays'] = $this->mere_pays;
    	$data['mere_profession'] = $this->mere_profession;
    	$data['mere_email'] = $this->mere_email;
    	$data['mere_num_portable'] = $this->mere_num_portable;
    	$data['mere_num_fixe'] = $this->mere_num_fixe;
    	$data['choix_adresse_facture'] = $this->choix_adresse_facture;
    	$data['facture_nom'] = $this->facture_nom;
    	$data['facture_adresse'] = $this->facture_adresse;
    	$data['facture_code_postal'] = $this->facture_code_postal;
    	$data['facture_ville'] = $this->facture_ville;
    	$data['facture_pays'] = $this->facture_pays;
    	$data['caution'] = $this->caution;
    	$data['ass_med_mutuelle'] = $this->ass_med_mutuelle;
    	$data['photo_ci_passeport'] = $this->photo_ci_passeport;
    	$data['recensement'] = $this->recensement;
    	$data['visite_medicale'] = $this->visite_medicale;
    	$data['origine'] = $this->origine;
    	$data['observations'] = $this->observations;
    	$data['boo_licencie'] = $this->boo_licencie;
    	$data['niveau'] = $this->niveau;
    	$data['resultats'] = $this->resultats;
    	$data['boo_droitier'] = $this->boo_droitier;
    	$data['poste_princ'] = $this->poste_princ;
    	$data['postes_sec'] = $this->postes_sec;
    	$data['nom_club'] = $this->nom_club;
    	$data['adresse_club'] = $this->adresse_club;
    	$data['nom_club_prec'] = $this->nom_club_prec;
    	$data['nom_coach'] = $this->nom_coach;
    	$data['num_coach'] = $this->num_coach;
    	$data['boo_non_sollicit'] = $this->boo_non_sollicit;
    	$data['nom_contact'] = $this->nom_contact;
    	$data['num_contact'] = $this->num_contact;
    	$data['classe_prec'] = $this->classe_prec;
    	$data['etab_prec'] = $this->etab_prec;
    	$data['moyenne_trim_prec'] = $this->moyenne_trim_prec;
    	$data['adresse_etab_prec'] = $this->adresse_etab_prec;
    	$data['specialite_prevue'] = $this->specialite_prevue;
    	$data['langue_mat'] = $this->langue_mat;
    	$data['LV1'] = $this->LV1;
    	$data['LV2'] = $this->LV2;
    	$data['poids'] = $this->poids;
    	$data['taille'] = $this->taille;
    	$data['pointure'] = $this->pointure;
    	$data['taille_vetement'] = $this->taille_vetement;
    	$data['ante_blessure'] = $this->ante_blessure;
    	$data['ante_fracture'] = $this->ante_fracture;
    	$data['ante_maladie'] = $this->ante_maladie;
    	$data['bilan_medical'] = $this->bilan_medical;
    	$data['bilan_musculaire'] = $this->bilan_musculaire;
    	$data['bilan_etirements'] = $this->bilan_etirements;
    	$data['ecg'] = $this->ecg;
    	$data['bilan_bucco_dentaire'] = $this->bilan_bucco_dentaire;
    	$data['medecin_referent'] = $this->medecin_referent;
    	$data['update_time'] = $this->update_time;
    	return $data;
    }

    public static function importUser($firstCommunityId, $firstVcardId, $firstUserId, $firstDocumentId)
    {
    	$context = Context::getCurrent();

    	$where = new Where;
    	$where->equalTo('instance_id', $context->getInstanceId());
    	$where->greaterThanOrEqualTo('id', $firstCommunityId);
    	Community::getTable()->transMultipleDelete($where);

    	$where = new Where;
    	$where->equalTo('instance_id', $context->getInstanceId());
    	$where->greaterThanOrEqualTo('id', $firstVcardId);
    	Vcard::getTable()->transMultipleDelete($where);

    	$where = new Where;
    	$where->equalTo('instance_id', $context->getInstanceId());
    	$where->greaterThanOrEqualTo('user_id', $firstUserId);
    	User::getTable()->transMultipleDelete($where);

    	$where = new Where;
    	$where->equalTo('instance_id', $context->getInstanceId());
    	$where->greaterThanOrEqualTo('id', $firstDocumentId);
    	Document::getTable()->transMultipleDelete($where);
    	
    	$select = UserImport::getTable()->getSelect();
    	$cursor = UserImport::getTable()->transSelectWith($select);
    	foreach ($cursor as $userImport) {
    		if ($userImport->contact_id) {
	    		$community = new Community;
	    		Community::getTable()->save($community);

	    		$document = new Document;
	    		$document->parent_id = 0;
	    		$document->type = 'directory';
	    		$document->name = 'Documents';
	    		$document->acl = array('communities' => array($community->id => 'write'), 'contacts' => array());
	    		Document::getTable()->save($document);
	    		
	    		$community->root_document_id = $document->id;

	    		$vcard = new Vcard;
	    		$vcard->community_id = $community->id;
	    		
    		    if ($userImport->contact_id == 1) {
    		    	$eleveImport = StudentSportImport::getTable()->transGet($userImport->perimetre);
		    		$vcard->n_first = $eleveImport->prenoms;
		    		$vcard->n_last = $eleveImport->nom_famille;
		    		$vcard->n_fn = $vcard->n_last.', '.$vcard->n_first;
		    		$vcard->email = $eleveImport->email;
    		    }
    			else {
	    			$vcardImport = VcardImport::getTable()->transGet($userImport->contact_id);
    				$vcard->n_title = $vcardImport->n_title;
		    		$vcard->n_first = $vcardImport->n_first;
		    		$vcard->n_last = $vcardImport->n_last;
		    		$vcard->n_fn = $vcardImport->n_fn;
		    		$vcard->org = $vcardImport->org;
		    		$vcard->tel_work = $vcardImport->tel_work;
		    		$vcard->tel_cell = $vcardImport->tel_cell;
		    		$vcard->email = $vcardImport->email;
    			}
	    		Vcard::getTable()->save($vcard);
	
	    		$community->name = $vcard->n_fn;
	    		$community->contact_1_id = $vcard->id;
	    		Community::getTable()->save($community);
	    		
	    		$user = new User;
	    		$user->instance_id = $context->getInstanceId();
	    		$user->username = $userImport->username;
	    		$user->contact_id = $vcard->id;
	    		$user->password = $userImport->password;
	    		$user->state = 1;
	    		User::getTable()->save($user);
			}
    	}
    }

    public static function import()
    {
    	$context = Context::getCurrent();

    	Account::getTable()->multipleDelete(array());
    	Commitment::getTable()->multipleDelete(array());
    	 
    	$select = StudentSportImport::getTable()->getSelect();
    	$cursor = StudentSportImport::getTable()->transSelectWith($select);
    	foreach ($cursor as $studentSportImport) {
    		$community = Community::get($studentSportImport->nom_famille.', '.$studentSportImport->prenoms, 'name');
    		if ($community) $account = Account::get($community->id, 'customer_community_id');
    		else $account = null;
    		if (!$account) {
	    		$account = new Account;	
				$account->opening_date = $studentSportImport->date_inscription;
				$account->closing_date = substr($studentSportImport->annee_scolaire, 5).'-08-31';
				$account->place_id = Place::getTable()->get($studentSportImport->centre, 'name')->id;
				$account->property_1 = $studentSportImport->sport;
	
				if ($studentSportImport->nom_repr_legal == $studentSportImport->pere_nom || $studentSportImport->email_repr_legal == $studentSportImport->pere_email) {
					$account->property_2 = ($studentSportImport->pere_num_portable) ? $studentSportImport->pere_num_portable : $studentSportImport->pere_num_fixe;
				}
				else {
					$account->property_2 = ($studentSportImport->mere_num_portable) ? $studentSportImport->mere_num_portable : $studentSportImport->mere_num_fixe;
				}

				$contact = Vcard::getTable()->get($studentSportImport->nom_famille.', '.$studentSportImport->prenoms, 'n_fn');
				if ($contact) {
					$contact->photo_link_id = 'eleve'.$studentSportImport->id.'.jpg';
					Vcard::getTable()->save($contact);
					$account->property_3 = $contact->id;
				}
				
				$account->property_4 = $studentSportImport->classe;
				$account->property_5 = $studentSportImport->specialite;
				$account->property_6 = $studentSportImport->internat;

				// Add the account
				if (!$community) {
					$community = new Community;
					Community::getTable()->save($community);
					
					$document = new Document;
					$document->parent_id = 0;
					$document->type = 'directory';
					$document->name = 'Documents';
					$document->acl = array('communities' => array($community->id => 'write'), 'contacts' => array());
					Document::getTable()->save($document);
					 
					$community->root_document_id = $document->id;
					
					$vcard = new Vcard;
					$vcard->community_id = $community->id;
					$vcard->n_first = $studentSportImport->prenoms;
					$vcard->n_last = $studentSportImport->nom_famille;
					$vcard->n_fn = $vcard->n_last.', '.$vcard->n_first;
					$vcard->email = $studentSportImport->email;
					Vcard::getTable()->save($vcard);

		    		$community->name = $vcard->n_fn;
		    		$community->contact_1_id = $vcard->id;
		    		Community::getTable()->save($community);
				}
				$account->customer_community_id = $community->id;
				Account::getTable()->save($account);
    		}
    		else {
				$closing_date = substr($studentSportImport->annee_scolaire, 5).'-08-31';
    			if ($account->closing_date < $closing_date) {
					$account->closing_date = substr($studentSportImport->annee_scolaire, 5).'-08-31';
    				$account->property_1 = $studentSportImport->sport;
    				
					if ($studentSportImport->nom_repr_legal == $studentSportImport->pere_nom || $studentSportImport->email_repr_legal == $studentSportImport->pere_email) {
						$account->property_2 = ($studentSportImport->pere_num_portable) ? $studentSportImport->pere_num_portable : $studentSportImport->pere_num_fixe;
					}
					else {
						$account->property_2 = ($studentSportImport->mere_num_portable) ? $studentSportImport->mere_num_portable : $studentSportImport->mere_num_fixe;
					}
					$account->property_4 = $studentSportImport->classe;
					$account->property_5 = $studentSportImport->specialite;
					$account->property_6 = $studentSportImport->internat;
    							
					$contact = Vcard::getTable()->get($studentSportImport->nom_famille.', '.$studentSportImport->prenoms, 'n_fn');
					if ($contact) {
						$contact->photo_link_id = 'eleve'.$studentSportImport->id.'.jpg';
						Vcard::getTable()->save($contact);
					}
    			}
				Account::getTable()->save($account);
   		}
			
    		$commitment = Commitment::instanciate('registration');
    		if ($studentSportImport->annee_scolaire == '2016-2017') $commitment->status = 'new';
    		else $commitment->status = 'closed';
    		$commitment->account_id = $account->id;
    		$commitment->identifier = $studentSportImport->id;
    		$commitment->caption = $studentSportImport->annee_scolaire;
    		$commitment->property_1 = $studentSportImport->classe;
    		$commitment->property_2 = $studentSportImport->specialite;
    		$commitment->property_3 = $studentSportImport->internat;
    		Commitment::getTable()->save($commitment);
    	}
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        throw new \Exception("Not used");
    }

    public static function getTable()
    {
    	if (!StudentSportImport::$table) {
    		$sm = Context::getCurrent()->getServiceManager();
    		StudentSportImport::$table = $sm->get('PpitStudies\Model\StudentSportImportTable');
    	}
    	return StudentSportImport::$table;
    }
}
