<?php 
	/*
	* @Module: Eleves
	* @Objectif: Gestion des élèves qui rejoignent l'association
	*/

	class StudentsController extends AppController
	{

		public function beforeFilter(){
			$this->Auth->allow();
			$this->Session->read();

			/*
			*     SESSION
			*/
			$_SESSION['Exercice']=2013;
			debug($_SESSION);
		}

		public function E200Update($id = null){

			if(!isset($_SESSION['Dossier']['Eleve']['EleveId'])){
				$this->Session->setFlash('Aucun dossier élève selectionné', 'flash/success');
				$this->redirect(array('controller'=>'Students', 'action'=>'E200New'));
			}

			$telBis = array();
			$plaBis = array();
			$ele = $this->Student->find('first', array('conditions'=>array('Student.EleveId'=>$_SESSION['Dossier']['Eleve']['EleveId'])));
			$tel = $this->Student->query("SELECT * FROM bal_vue_e200_telephone WHERE InterlocuteurId=".$ele['Student']['InterlocuteurId']);
			$res = $this->Student->query("SELECT * FROM bal_personne INNER JOIN bal_estresponsablelegal ON bal_estresponsablelegal.PersonneId = bal_personne.PersonneId WHERE bal_estresponsablelegal.EleveId =".$ele['Student']['EleveId']);
			$pla = $this->Student->query("SELECT * FROM bal_personne INNER JOIN bal_habite ON bal_personne.PersonneId=bal_habite.PersonneId WHERE bal_habite.EndroitId=".$ele['Student']['EndroitId']);

			//Formatage
			foreach ($res as $key => $value) {
				$resBis[$value['bal_personne']['PersonneId']]=$value['bal_personne']['PersonneNom'].' '.$value['bal_personne']['PersonnePrenom'].' - '.$value['bal_personne']['PersonneId'];
			}

			foreach ($tel as $key => $value) {
				$telBis[]=$value['bal_vue_e200_telephone']['TelephoneNum'];
			}

			$cpt=0;
			foreach ($pla as $key => $value) {
				$plaBis[$pla[$cpt]['bal_habite']['EndroitId']]=$value['bal_personne']['PersonneNom'].' '.$value['bal_personne']['PersonnePrenom'].' - '.$value['bal_personne']['PersonneId'];
				$cpt++;
			}

			$ele['Student']['appellation'] = $ele['Student']['EleveNom'].' '.$ele['Student']['ElevePrenom'].' - '.$ele['Student']['EleveId'];


			//Liste responsable
			$this->set('setResp', $resBis);

			//Liste phone
			$this->set('setPhone', $telBis);

			//Liste Adresse
			$this->set('setPlace', $plaBis);

			//Info Eleve
			$this->set('eleve', $ele);
			

			if($this->request->is('post')){
				debug($this->request->data);
				$in['PSessionId']=$_SESSION['Config']['userAgent'];
				$in['PConseilFCPEId']=$_SESSION['Auth']['ConseilFCPEId'];
				$in['PEleveId']=$ele['Student']['EleveId'];
				$in['PEleveNom']=$this->request->data['Student']['nom'];
				$in['PElevePrenom']=$this->request->data['Student']['prenom'];
				$in['PEleveMasculin']=($this->request->data['Student']['sexe']=='H');
				$in['PRespLegalId']=$this->request->data['Student']['responsable_legal'];
				$in['PEndroitId']=$_SESSION['Dossier']['Parent']['EndroitID'];
				$in['PEMail']=$this->request->data['Student']['Email'];
				$in['PEMailValide']=0;
				$in['PPassword']='';
				$in['PNumEtVoie']=$this->request->data['Student']['num'];
				$in['PLieuDit']=$this->request->data['Student']['lieudit'];
				$in['PVille']=$this->request->data['Student']['ville'];
				$in['PPays']='France';
				$in['PCodePostal']=$this->request->data['Student']['code_postal'];
				$in['PApptBatResidence']=$this->request->data['Student']['appbatres'];
				$in['PBP']=$this->request->data['Student']['BP'];

				/* Make caal et redirection */
				$this->makeCall('Save_BAL_Vue_E200_Eleve', $in);
				$this->Session->setFlash('Eleve enregistré', 'Flash/success');
				$_SESSION['Dossier']['Eleve']['EleveId']= $ele['Student']['EleveId'];
				$_SESSION['Dossier']['Eleve']['EleveNom']= $in['PEleveNom'];
				$_SESSION['Dossier']['Eleve']['ElevePrenom']= $in['PElevePrenom'];
				$this->redirect(array('controller'=>'Students', 'action'=>'E200Update', $EleveID));

			}

		}


		public function E200New(){

			if(!isset($_SESSION['Dossier']['Parent']['ParentId'])){
				$this->Session->setFlash('Aucun dossier parent trouvé', 'flash/success');
				$this->redirect(array('controller'=>'Responsable', 'action'=>'nouveau', 2));
			}

			if($this->request->is('post')){
				
				$in['PSessionId']=$_SESSION['Config']['userAgent'];
				$in['PConseilFCPEId']=$_SESSION['Auth']['ConseilFCPEId'];
				$in['PEleveId']=null;
				$in['PEleveNom']=$this->request->data['Student']['nom'];
				$in['PElevePrenom']=$this->request->data['Student']['prenom'];
				$in['PEleveMasculin']=($this->request->data['Student']['sexe']=='H');
				$in['PRespLegalId']=$_SESSION['Dossier']['Parent']['ParentId'];
				$in['PEndroitId']=null;
				$in['PEMail']=$this->request->data['Student']['Email'];
				$in['PEMailValide']=0;
				$in['PPassword']='';
				$in['PNumEtVoie']=$this->request->data['Student']['num'];
				$in['PLieuDit']=$this->request->data['Student']['lieudit'];
				$in['PVille']=$this->request->data['Student']['ville'];
				$in['PPays']='France';
				$in['PCodePostal']=$this->request->data['Student']['code_postal'];
				$in['PApptBatResidence']=$this->request->data['Student']['appbatres'];
				$in['PBP']=$this->request->data['Student']['BP'];


				/* Make call et redirection */
				$this->makeCall('Save_BAL_Vue_E200_Eleve', $in);
				$EleveID = $this->Student->query("SELECT SUIVIIDLastId FROM bal_suiviid WHERE SUIVIIDSessionId ='".$_SESSION['Config']['userAgent']."' AND SUIVIIDTableNom='BAL_Eleve' ORDER BY SUIVIIDId DESC LIMIT 0,1");
				$this->Session->setFlash('Eleve enregistré', 'Flash/success');
				$this->Session->write('Dossier.Eleve.EleveId',$EleveID[0]['bal_suiviid']['SUIVIIDLastId']);
				$this->Session->write('Dossier.Eleve.EleveNom',$in['PEleveNom']);
				$this->Session->write('Dossier.Eleve.ElevePrenom',$in['PElevePrenom']);
				$this->redirect(array('controller'=>'Students', 'action'=>'E200Update'));
			}
		}

		public function addTel($tel = null, $id=null, $int=null){
			if(!is_numeric($id) || $tel==null || $id==null || $int==null || !is_numeric($int)){
				$this->Session->setFlash('Eleve non reconnu', 'flash/error');
				$this->redirect('/');
			}
			

			$in['PSessionId']=$_SESSION['Config']['userAgent'];
			$in['PEleveId']=$id;
			$in['PInterlocuteurId']=$int;
			$in['PTelephoneNum']="$tel";

			$this->makeCall('Save_BAL_Vue_E200_Telephone', $in);
			$this->Session->setFlash('Numéros ajouté', 'flash/success');
			$this->redirect(array('controller'=>'Students', 'action'=>'E200Update'));
		}

		public function delTel($tel = null, $id=null){
			$this->Session->read();
			if( $tel==null || $id==null || !is_numeric($id)){
				$this->Session->setFlash('Eleve non reconnu', 'flash/error');
				$this->redirect('/');
			}
			
			$in['PSessionId']=$_SESSION['Config']['userAgent'];
			$in['PInterlocuteurId']=$id;
			$in['PTelephoneNum']="$tel";
			$this->Student->query('call Delete_BAL_Vue_E200_Telephone ("'.$_SESSION['Config']['userAgent'].'", '.$id.', "'.$tel.'") ;');
			$this->Session->setFlash('Numéros supprimé', 'flash/success');
			$this->redirect(array('controller'=>'Students', 'action'=>'E200Update'));
		}

		public function E201(){

			$eleve = $this->Student->find('first', array('conditions'=>array('Student.EleveId'=>$_SESSION['Dossier']['Eleve']['EleveId'])));
			$classe = $this->Student->query("SELECT ClasseId,ClasseDesignation,Fin FROM bal_vue_e201_classe WHERE EtablissementId=".$_SESSION['Dossier']['Eleve']['EtablissementId']);
			$res = $this->Student->query("SELECT * FROM bal_personne INNER JOIN bal_estresponsablelegal ON bal_estresponsablelegal.PersonneId = bal_personne.PersonneId WHERE bal_estresponsablelegal.EleveId =".$eleve['Student']['EleveId']);

			$classeBis[0]='';
			foreach ($classe as $key => $value) {
				$fin = $value['bal_vue_e201_classe']['Fin'];
				if($fin === NULL || $fin > date('Y'))
				$classeBis[$value['bal_vue_e201_classe']['ClasseId']]=$value['bal_vue_e201_classe']['ClasseDesignation'];
			}


			$this->set('setClasse', $classeBis);
			$this->set('eleve', $eleve);
			$this->set('resp', $res);
		

			if($this->request->is("post")){
				
				$in['PSessionId']=$_SESSION['Config']['userAgent'];
				$in['PEleveId']=$_SESSION['Dossier']['Eleve']['EleveId'];
				$in['PEtablissementId']=$_SESSION['Dossier']['Eleve']['EtablissementId'];
				$in['PClasseId']=$this->request->data['classe'];
				$in['POptionNom']=null;
				$in['PCoursId']=null;
				$in['PExercice']=$_SESSION['Exercice'];

				debug($this->request->data);
				foreach ($this->request->data['Student'] as $key => $value) {
					if($value != 0){
						$in['POptionNom']=$key;
						$in['PCoursId']=$value;
						$this->makeCall('Save_BAL_Vue_E201_EleveInscrit', $in);
					}
				}

				if(isset($this->request->data['Option']))
				foreach ($this->request->data['Option'] as $key => $value) {
					$in['POptionNom']=$value;
					$in['PCoursId']=$key;
					$this->makeCall('Save_BAL_Vue_E201_EleveInscrit', $in);
				}

				$this->Session->setFlash('PArcours enregistré.', 'flash/success');
				$this->redirect(array('action'=>'E201'));
			}
		}


		public function ajaxE201($id=null){
			
			
			if ( $this->request->is( 'ajax' ) ) {
				$langues = $this->Student->query('SELECT CoursId,CoursNom FROM bal_vue_e201_option WHERE EtablissementId='.$_SESSION['Dossier']['Eleve']['EtablissementId'].' AND ClasseId='.$id);
				$res[]='';
				foreach ($langues as $key => $value) {
					$res[$value['bal_vue_e201_option']['CoursId']]=$value['bal_vue_e201_option']['CoursNom'];
				}

				echo json_encode($res);
	 
				exit();
			}
		}

		public function ajaxE201b($id=null, $sk=null){
			
			
			if ( $this->request->is( 'ajax' ) && ($sk=="LV1" || $sk=="LV2" || $sk=="LV3")) {
				$langues = $this->Student->query('SELECT * FROM bal_vue_e201_option WHERE EtablissementId='.$_SESSION['Dossier']['Eleve']['EtablissementId'].' AND ClasseId='.$id.' AND OptionNom="'.$sk.'"');
				$res[]='';
				foreach ($langues as $key => $value) {
					$res[$value['bal_vue_e201_option']['CoursId']]=$value['bal_vue_e201_option']['CoursNom'];
				}

				echo json_encode($res);
				exit();
			}
		}

		public function ajaxE201c($id=null){
			
			$cpt=0;
			if ( $this->request->is( 'ajax' ) ) {
				$langues = $this->Student->query('SELECT CoursId,CoursNom,OptionNom FROM bal_vue_e201_option WHERE EtablissementId='.$_SESSION['Dossier']['Eleve']['EtablissementId'].' AND ClasseId='.$id.' AND OptionNom not in (\'LV1\', \'LV2\', \'LV3\')');
				foreach ($langues as $key => $value) {
					$res[$cpt]['nom']=$value['bal_vue_e201_option']['CoursNom'];
					$res[$cpt]['optionNom']=$value['bal_vue_e201_option']['OptionNom'];
					$res[$cpt]['id']=$value['bal_vue_e201_option']['CoursId'];
					$cpt++;
				}

				echo json_encode($res);
	 
				exit();
			}
		}

		public function ajaxRecherche($name = null){
			$_SESSION['Dossier']['Eleve']['EtablissementId']=4;
			$id=17;

			if ( $this->request->is( 'ajax' ) ) {
				$langues = $this->Student->query('SELECT CoursId,CoursNom FROM bal_vue_e201_option WHERE EtablissementId='.$_SESSION['Dossier']['Eleve']['EtablissementId'].' AND ClasseId='.$id.' AND OptionNom!="LV1" AND OptionNom!="LV2" AND OptionNom!="LV3"');
				foreach ($langues as $key => $value) {
					$res[$value['bal_vue_e201_option']['CoursId']]=$value['bal_vue_e201_option']['CoursNom'];
				}

				echo json_encode($res);
	 
				exit();
			}
		}		
	}

?>