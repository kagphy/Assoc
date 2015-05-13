<?php 

	class RecherchesController extends AppController {


		public function beforeFilter(){
			$this->Auth->allow();
			$this->loadModel('Recherche');
			$this->Session->read();

			/*
			*     SESSION
			*/
			$_SESSION['Exercice']=2013;
			debug($_SESSION);
		}

		public function seek(){
			unset($_SESSION['Dossier']);
			
			if($this->request->is('post')){
				$this->redirect(array('controller'=>'recherches', 'action'=>'find', $this->request->data['form']['rech']));
			}
		}


		public function find($var=null){	
			
			if(empty($var)){
				$this->Session->setFlash('Recherche vide.', 'flash/error');
				$this->redirect('/');
			}	
			$var = str_replace(' ', '%', $var);
			$res = $this->Recherche->query("select * from BAL_Vue_Recherche where Exercice=EXERCICE and (Texte like '%$var%' or TelParent like '%$var%' or TelEleve like '%$var%' or BonOperationId='$var' or FactureId='$var')");

			if(!empty($res)){
				/* Filtrage données */
				foreach ($res as $key => $value) {
					$personne[$value['BAL_Vue_Recherche']['PersonneId']]='';
					$eleve[$value['BAL_Vue_Recherche']['EleveId']]='';

				}

				foreach ($personne as $key => $value) {
					$p = $this->Recherche->query('SELECT * FROM `bal_vue_e100_personne` WHERE PersonneId='.$key);
					$parentBis[] = $p[0];
				}

				foreach ($eleve as $key => $value) {
					$e = $this->Recherche->query('SELECT * FROM `bal_vue_e200_eleve` WHERE EleveId='.$key);
					$eleveBis[] = $e[0];
				}
			}
			else{
				$eleveBis=array();
				$parentBis=array();
			}

			$this->set('eleve', $eleveBis);
			$this->set('parent', $parentBis);

		}


		public function cloreDossier(){
			unset($_SESSION['Dossier']);
			$this->redirect(array('controller'=>'recherches', 'action'=>'seek'));
		}

		public function dossierEleve($EleveId = null){
			if(empty($EleveId) || !is_numeric($EleveId)){
				$this->redirect(array('action'=>'seek'));
			}

			$eleve=$this->Recherche->query('SELECT * FROM bal_vue_e200_eleve WHERE EleveId='.$EleveId);
			$parent = $this->Recherche->query("SELECT * FROM bal_vue_e100_personne NATURAL JOIN bal_estresponsablelegal WHERE EleveId =".$EleveId);
			$_SESSION['Dossier']['Parent']['InterlocuteurId']= $parent[0]['bal_vue_e100_personne']['InterlocuteurId'];
			$_SESSION['Dossier']['Parent']['ParentId']= $parent[0]['bal_vue_e100_personne']['PersonneId'];
			$_SESSION['Dossier']['Parent']['EndroitId']=  $parent[0]['bal_vue_e100_personne']['EndroitId'];
			$_SESSION['Dossier']['Parent']['PersonneNom']= $parent[0]['bal_vue_e100_personne']['PersonneNom'];
			$_SESSION['Dossier']['Parent']['PersonnePrenom']= $parent[0]['bal_vue_e100_personne']['PersonnePrenom'];

			$_SESSION['Dossier']['Eleve']['EleveId']= $eleve[0]['bal_vue_e200_eleve']['EleveId'];
			$_SESSION['Dossier']['Eleve']['EleveNom']= $eleve[0]['bal_vue_e200_eleve']['EleveNom'];
			$_SESSION['Dossier']['Eleve']['ElevePrenom']= $eleve[0]['bal_vue_e200_eleve']['ElevePrenom'];
			
			$this->redirect(array('controller'=>'Students', 'action'=>'E200Update'));
		}


		public function dossierPersonne($PersonneID = null){
			if(empty($PersonneID) || !is_numeric($PersonneID)){
				$this->redirect(array('action'=>'seek'));
			}

			$parent = $this->Recherche->query("SELECT * FROM bal_vue_e100_personne WHERE PersonneId=".$PersonneID);
			
			$_SESSION['Dossier']['Parent']['InterlocuteurId']= $parent[0]['bal_vue_e100_personne']['InterlocuteurId'];
			$_SESSION['Dossier']['Parent']['ParentId']= $parent[0]['bal_vue_e100_personne']['PersonneId'];
			$_SESSION['Dossier']['Parent']['EndroitId']=  $parent[0]['bal_vue_e100_personne']['EndroitId'];
			$_SESSION['Dossier']['Parent']['PersonneNom']= $parent[0]['bal_vue_e100_personne']['PersonneNom'];
			$_SESSION['Dossier']['Parent']['PersonnePrenom']= $parent[0]['bal_vue_e100_personne']['PersonnePrenom'];
			$eleve=$this->Recherche->query('SELECT DISTINCT EleveId,EleveNom,ElevePrenom FROM bal_vue_e200_eleve NATURAL JOIN bal_estresponsablelegal WHERE PersonneId ='.$PersonneID);
			
			$cpt = count($eleve);
			if($cpt == 0){
				$this->redirect(array('controller'=>'Student', 'action'=>'E200New'));
			}
			else if($cpt == 1){
				$eleve=$this->Recherche->query('SELECT DISTINCT EleveId,EleveNom,ElevePrenom FROM bal_vue_e200_eleve NATURAL JOIN bal_estresponsablelegal NATURAL JOIN bal_estinscriten WHERE Exercice='.$_SESSION['Exercice'].' AND PersonneId ='.$PersonneID);
				$_SESSION['Dossier']['Eleve']['EleveId']= $eleve[0]['bal_vue_e200_eleve']['EleveId'];
				$_SESSION['Dossier']['Eleve']['EleveNom']= $eleve[0]['bal_vue_e200_eleve']['EleveNom'];
				$_SESSION['Dossier']['Eleve']['ElevePrenom']= $eleve[0]['bal_vue_e200_eleve']['ElevePrenom'];
			}
			else{
				$this->set('eleve', $eleve);
			}
		}

	}
?>
