<?php

class ResponsablesController extends AppController {

    public function beforeFilter(){
      $this->Session->read();
      $this->Auth->allow();      
      $this->Session->write('Dossier.Exercice',2013);
      $this->Session->write('Dossier.Parent.ParentId',601);
      $this->Session->write('Dossier. Parent.InterlocuteurId',1738);
      $this->Session->write('Auth.User.ConseilFCPEId',5);
      $this->Session->write('Auth.User.InterlocuteurId',0);

    }
    
    public function e100() {
      $interlocuteur = $_SESSION['Dossier']['Parent']['InterlocuteurId'];
      $perso = $this->Responsable->find('all', array('conditions' => array('Responsable.InterlocuteurId' => $interlocuteur)));

      $perso = $perso[0]['Responsable'];      
       
      $teltmp = $this->Responsable->query("SELECT * FROM bal_vue_e100_tel WHERE InterlocuteurId = $interlocuteur;");
      foreach ($teltmp as $key => $value) {
        $tel[$key] = $value['bal_vue_e100_tel']['TelephoneNum'];
      }
   		$this->set('perso', $perso);
      $this->set('tel', $tel);

      if ($this->request->is('post')) {
          
        if ($this->request->data['Responsable']['ParentAppellation'] == '')
          $appellation = $perso['PersonneMasculin'];
        else if ($this->request->data['Responsable']['ParentAppellation'] == 0)
          $appellation = 1;
        else
          $appellation = 0;

        if ($this->request->data['Responsable']['AdNiveauDInformationNom'] == '')
          $ni = $perso['NiveauDInformationNom'];
        else if ($this->request->data['Responsable']['AdNiveauDInformationNom'] == 0)
          $ni = "Personnel";
        else if ($this->request->data['Responsable']['AdNiveauDInformationNom'] == 1)
          $ni = "Personnel & Association";
        else
          $ni = "Personnel & Association & Tutelles";

        
        $uperso[' PSessionId' ] = '';
        $uperso[' PConseilFCPEId' ] = $_SESSION['Auth']['User']['ConseilFCPEId'];///////////////////////////////////////////////////////////////////
        $uperso[' PPersonneId' ] = $perso['PersonneId'];
        $uperso[' PPersonneNom' ] = $this->request->data['Responsable']['ParentNom'];
        $uperso[' PPersonnePrenom' ] = $this->request->data['Responsable']['ParentPrenom'];
        $uperso[' PPersonneMasculin' ] = $appellation;
        $uperso[' PInterlocuteurId' ] = $perso['InterlocuteurId'];
        $uperso[' PEMail' ] = $this->request->data['Responsable']['AdMail'];
        $uperso[' PEMailValide' ] = $perso['EMailValide'];
        $uperso[' PPassword' ] = $perso['Password'];
        $uperso[' PNiveauDInformationNom' ] = $ni;
        $uperso[' PEndroitId' ] = null;
        $uperso[' PNumEtVoie' ] = $this->request->data['Responsable']['AdNumEtVoie'];
        $uperso[' PLieuDit' ] = $this->request->data['Responsable']['AdLieuDit'];
        $uperso[' PVille' ] = $this->request->data['Responsable']['AdVille'];
        $uperso[' PPays' ] = $perso['Pays'];
        $uperso[' PCodePostal' ] = $this->request->data['Responsable']['AdCodePostal'];
        $uperso[' PApptBatResidence' ] = $this->request->data['Responsable']['AdApptBatResidence'];
        $uperso[' PBP' ] = $this->request->data['Responsable']['AdBP'];
        $this->makeCall("Save_BAL_Vue_E100_Personne", $uperso);

        $this->redirect(array('controller'=>'Responsables', 'action'=>'e100'));

      }

        
       

    }

    public function e100new(){
      $user = $_SESSION['Auth']['User']['InterlocuteurId'];//operateur
      if ($this->request->is('post')) {
        if ($this->request->data['Responsable']['ParentAppellation'] == 0)
          $appellation = 1;
        else
          $appellation = 0;

        if ($this->request->data['Responsable']['AdNiveauDInformationNom'] == 0)
          $ni = "Personnel";
        else if ($this->request->data['Responsable']['AdNiveauDInformationNom'] == 1)
          $ni = "Personnel & Association";
        else
          $ni = "Personnel & Association & Tutelles";


        $uperso[' PSessionId' ] = '';
        $uperso[' PConseilFCPEId' ] = $_SESSION['Auth']['User']['ConseilFCPEId'];
        $uperso[' PPersonneId' ] = null;
        $uperso[' PPersonneNom' ] = $this->request->data['Responsable']['ParentNom'];
        $uperso[' PPersonnePrenom' ] = $this->request->data['Responsable']['ParentPrenom'];
        $uperso[' PPersonneMasculin' ] = $appellation;
        $uperso[' PInterlocuteurId' ] = null;
        $uperso[' PEMail' ] = $this->request->data['Responsable']['AdMail'];
        $uperso[' PEMailValide' ] = null;
        $uperso[' PPassword' ] = null;
        $uperso[' PNiveauDInformationNom' ] = $ni;
        $uperso[' PEndroitId' ] = null;
        $uperso[' PNumEtVoie' ] = $this->request->data['Responsable']['AdNumEtVoie'];
        $uperso[' PLieuDit' ] = $this->request->data['Responsable']['AdLieuDit'];
        $uperso[' PVille' ] = $this->request->data['Responsable']['AdVille'];
        $uperso[' PPays' ] = "france";
        $uperso[' PCodePostal' ] = $this->request->data['Responsable']['AdCodePostal'];
        $uperso[' PApptBatResidence' ] = $this->request->data['Responsable']['AdApptBatResidence'];
        $uperso[' PBP' ] = $this->request->data['Responsable']['AdBP'];
        $this->makeCall("Save_BAL_Vue_E100_Personne", $uperso);

        $nouveauParentId = $this->Responsable->query("SELECT SUIVIIDLastId FROM bal_suiviid WHERE SUIVIIDSessionId = $user AND SUIVIIDTableNom = 'BAL_Personne' ORDER BY SUIVIIDId DESC LIMIT 0,1;");
        $nouveauParentInterlocuteurId = $this->Responsable->query("SELECT SUIVIIDLastId FROM bal_suiviid WHERE SUIVIIDSessionId = $user AND SUIVIIDTableNom = 'BAL_Interlocuteur' ORDER BY SUIVIIDId DESC LIMIT 0,1;");
        unset($_SESSION['Dossier']);
        $this->Session->write('Dossier.Parent.ParentId',$nouveauParentId);
        $this->Session->write('Dossier.Parent.InterlocuteurId',$nouveauParentInterlocuteurId);

        $this->redirect(array('controller'=>'Responsables', 'action'=>'e100'));
      }
     
    }
    public function e100del(){

      $interlocuteur = $_SESSION['Dossier']['InterlocuteurId'];
      $perso = $this->Responsable->find('all', array(
          'conditions' => array('Responsable.InterlocuteurId' => $interlocuteur)));

      $perso = $perso[0]['Responsable'];      
       
      $teltmp = $this->Responsable->query("SELECT * FROM bal_vue_e100_tel WHERE InterlocuteurId = $interlocuteur;");
      foreach ($teltmp as $key => $value) {
        $tel[$key] = $value['bal_vue_e100_tel']['TelephoneNum'];
      }
      $this->set('perso', $perso);
      $this->set('tel', $tel);
    
    }

    public function deletePerso(){

      $perso['PSessionId'] = '';
      $perso['PPersonneId'] = $_SESSION['Dossier']['Parent']['ParentId'];
      $this->makeCall("Delete_BAL_Vue_E100_Personne", $perso);
      unset($_SESSION['Dossier']);
      $this->redirect("/");
    }


    public function deletetel($num = null, $op = null){
      $usrInterlocuteur = $_SESSION['Auth']['User']['InterlocuteurId'];
      $interlocuteur = $_SESSION['Dossier']['Parent']['InterlocuteurId'];
      if($op == 0){
        $this->Responsable->query("call Delete_BAL_Vue_E100_Tel($usrInterlocuteur, $interlocuteur, '$num' )");
        $this->redirect(array('controller'=>'Responsables', 'action'=>'e100'));
      }
      else{
        $NewTel['PSessionId'] = $usrInterlocuteur;
        $NewTel['PInterlocuteurId'] =  $interlocuteur;
        $NewTel['PTelephoneNum'] = $num;
        $this->makeCall("Save_BAL_Vue_E100_Tel", $NewTel);
        $this->redirect(array('controller'=>'Responsables', 'action'=>'e100'));
      }
    }


    public function e101() {
      $exercice = $_SESSION['Dossier']['Exercice'];
      $interlocuteur = $_SESSION['Dossier']['Parent']['ParentId'];///////////////////////////////////////////////////////////////////////////////
      $perso = $this->Responsable->find('all', array(
        'conditions' => array('Responsable.PersonneId' => $interlocuteur)));

      $perso = $perso[0]['Responsable'];     
      $this->set('perso', $perso);


      $assocCouranteID = $_SESSION['Auth']['User']['ConseilFCPEId'] ;//////////////////////////////////////////////////////////////////////////////////////////////////
      $assocCourante = $this->Responsable->query("SELECT * FROM bal_conseilfcpe WHERE ConseilFCPEId = $assocCouranteID");
      $assocCourante = $assocCourante[0]['bal_conseilfcpe']['ConseilFCPENom'];      
      $this->set('assocCourante', $assocCourante);

      $tutelle[0] = $this->Responsable->query("SELECT * FROM bal_vue_e101_tutelle WHERE AffilieeId = $assocCouranteID;");
      $i = 1;      
      while (count($tutelle) == $i){
        $tmp = $i-1;

        $tuId = $tutelle[$tmp][0]['bal_vue_e101_tutelle']['TutelleId'];
        $tu = $this->Responsable->query("SELECT * FROM bal_vue_e101_tutelle WHERE AffilieeId = $tuId;");

          if(count($tu) == 1)
            $tutelle[$i] = $tu;
  
        $i++;
      }

      foreach ($tutelle as $key => $value) {
        $tuId = $tutelle[$key][0]['bal_vue_e101_tutelle']['TutelleId'];
        $assocTu = $this->Responsable->query("SELECT * FROM bal_conseilfcpe WHERE ConseilFCPEId = $tuId");
        $assocTu = $assocTu[0]['bal_conseilfcpe']['ConseilFCPENom']; 
        $assoc[$key] = $assocTu;
      }
      $this->set('assoc', $assoc);

      $adhesionTmp = $this->Responsable->query("SELECT * FROM bal_vue_e101_adhesion WHERE PersonneId = $interlocuteur AND ConseilFCPEId = $assocCouranteID AND Exercice = $exercice");
      if($adhesionTmp != null){
          $factureId = $adhesionTmp[0]['bal_vue_e101_adhesion']['FactureId'];
          $facture = $this->Responsable->query("SELECT * FROM bal_facture WHERE FactureId = $factureId");
          $adhesion[0] = $facture[0]['bal_facture']['CreationFact'];
      }
      else
         $adhesion[0] = null;

      foreach ($tutelle as $key => $value) {
        $tuId = $tutelle[$key][0]['bal_vue_e101_tutelle']['TutelleId'];

        $adhesionTmp = $this->Responsable->query("SELECT * FROM bal_vue_e101_adhesion WHERE PersonneId = $interlocuteur AND ConseilFCPEId = $tuId AND Exercice = $exercice");
        
        if($adhesionTmp != null){
          $factureId = $adhesionTmp[0]['bal_vue_e101_adhesion']['FactureId'];
          $facture = $this->Responsable->query("SELECT * FROM bal_facture WHERE FactureId = $factureId");
          $adhesion[$key+1] = $facture[0]['bal_facture']['CreationFact'];
        }
        else
          $adhesion[$key+1] = null;
      }
      $this->set('adhesion', $adhesion);
    }

    public function gestionAdhesion($assoc = null, $op = null) {
      $interlocuteur = $_SESSION['Dossier']['Parent']['ParentId'];
      $exercice = $_SESSION['Dossier']['Exercice'];
      $fcpe = $this->Responsable->query("SELECT * FROM bal_conseilfcpe");
      
      foreach ($fcpe as $key => $value) {
        if ($value['bal_conseilfcpe']['ConseilFCPENom'] == $assoc)
            $assocCouranteID = $value['bal_conseilfcpe']['ConseilFCPEId'];
      }

      $tutelle[0] = $this->Responsable->query("SELECT * FROM bal_vue_e101_tutelle WHERE AffilieeId = $assocCouranteID;");
      $i = 1;      
      while (count($tutelle) == $i){
        $tmp = $i-1;

        $tuId = $tutelle[$tmp][0]['bal_vue_e101_tutelle']['TutelleId'];
        $tu = $this->Responsable->query("SELECT * FROM bal_vue_e101_tutelle WHERE AffilieeId = $tuId;");

          if(count($tu) == 1)
            $tutelle[$i] = $tu;
  
        $i++;
      }

      $addAdhesion['PSessionId'] = '';
      $addAdhesion['PPersonneId'] = $interlocuteur;
      $addAdhesion['PConseilFCPEId'] = $assocCouranteID;
      $addAdhesion['PExercice'] = $exercice;

      if($op == 1){
      $this->makeCall("Save_BAL_Vue_E101_Adhesion", $addAdhesion);
        foreach ($tutelle as $key => $value) {
          $addAdhesion['PConseilFCPEId'] = $value[0]['bal_vue_e101_tutelle']['TutelleId'];
          $this->makeCall("Save_BAL_Vue_E101_Adhesion", $addAdhesion);
        }

      }
      else{
        $this->makeCall("Delete_BAL_Vue_E101_Adhesion", $addAdhesion);
        foreach ($tutelle as $key => $value) {
          $addAdhesion['PConseilFCPEId'] = $value[0]['bal_vue_e101_tutelle']['TutelleId'];
          $tuId = $addAdhesion['PConseilFCPEId'];

          $bla = $this->Responsable->query("SELECT * FROM bal_vue_e101_tutelle INNER JOIN bal_vue_e101_adhesion ON bal_vue_e101_tutelle.AffilieeId = bal_vue_e101_adhesion.ConseilFCPEId WHERE bal_vue_e101_adhesion.PersonneId = $interlocuteur AND bal_vue_e101_tutelle.TutelleId = $tuId AND Exercice = $exercice;");
          debug($value);
          debug($bla);
          if(count($bla)<1)
            $this->makeCall("Delete_BAL_Vue_E101_Adhesion", $addAdhesion);
        }
      }

    $this->redirect(array('controller'=>'Responsables', 'action'=>'e101'));
    }

}


?>