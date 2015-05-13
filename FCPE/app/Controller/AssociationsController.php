<?php

class AssociationsController extends AppController {
   
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    /**
     * Fonction permettant de créer une association
     */
    public function E012(){
        $tutelleQuery = $this->Association->query("SELECT ConseilFCPEId, ConseilFCPELabel FROM bal_vue_e001_assoc;");
        for ($i=0; $i < sizeof($tutelleQuery); $i++) { 
            foreach ($tutelleQuery[$i]['bal_vue_e001_assoc'] as $key => $value) {
                $tutelle[$tutelleQuery[$i]['bal_vue_e001_assoc']['ConseilFCPEId']] = $tutelleQuery[$i]['bal_vue_e001_assoc']['ConseilFCPELabel'];
            }
        }
        $this->set('tutelle', $tutelle);

        if ($this->request->is('post')) {
            if ($this->request->data['Association']['PConseilFCPENom']=='' ||
                $this->request->data['Association']['PSCNumEtVoie']=='' ||
                $this->request->data['Association']['PSCVille']=='' ||
                $this->request->data['Association']['PSCPays']=='' ||
                $this->request->data['Association']['PSCCodePostal']=='' ||
                $this->request->data['Association']['PEtablissementNom']=='' ||
                $this->request->data['Association']['PTypeDEtablissementNom']=='' ||
                $this->request->data['Association']['PEtabNumEtVoie']=='' ||
                $this->request->data['Association']['PEtabVille']=='' ||
                $this->request->data['Association']['PEtabCodePostal']=='' ||
                $this->request->data['Association']['PEtabPays']=='') {
                $this->Session->setFlash('Veuillez compléter les champs obligatoires.', 'flash/error');
                $this->redirect($this->referer());
            }
            else {
                $this->request->data['Association']['PInterlocuteurId'] = 1729;
                $this->request->data['Association']['PConseilFCPEId'] = null;
                $this->request->data['Association']['PEtablissementId'] = null;
                $this->request->data['Association']['PSCEndroitId'] = null;
                $this->request->data['Association']['PEtabEndroitId'] = null;
                if ($this->request->data['Association']['PTutelleId']=='') {
                    $this->request->data['Association']['PTutelleId'] = null;
                }
                $this->makeCall("Save_BAL_Vue_E012_Assoc", $this->request->data['Association']);
                $this->Session->setFlash('Association enregistré avec succès!', 'flash/success');
                $this->redirect(array('action' => 'asso'));
            }
        }
    }

    public function E003() {
    }
    public function E004(){
         //provisoire
            //
            $annee =$_SESSION['Exercice'];
             if ($this->request->is('post')) {      
                    $this->makeCall("Save_BAL_Vue_E004_Users",array($_SESSION["Auth"]["PSessionId"],$this->request->data["NewStaff"]["Identite"],$_SESSION["Auth"]["ConseilFCPEId"],$annee,$this->request->data["NewStaff"]["Poste"]) );
                    $this->redirect( array('controller' => 'Tests', 'action' => 'E004',$annee));
             }
            $request_role =$this->Test->query("SELECT RoleBureauNom FROM bal_vue_e004_roles;");
            $request_member = $this->Test->query("SELECT * FROM bal_vue_e101_adhesion LEFT JOIN bal_vue_e004_users ON bal_vue_e101_adhesion.PersonneId = bal_vue_e004_users.PersonneId WHERE  bal_vue_e101_adhesion.ConseilFCPEId =  ".  $_SESSION["Auth"]["ConseilFCPEId"]  . " AND bal_vue_e101_adhesion.Exercice = ". $annee  . ";");
            $request_staff = $this->Test->query("SELECT * FROM bal_vue_e004_users WHERE  ConseilFCPEId =  ".  $_SESSION["Auth"]["ConseilFCPEId"]  . " AND Exercice = ". $annee  . ";");     
            $role = array();
            $member = array();
            $staff = array();

            foreach($request_role as $r)
            {
                $role[$r["bal_vue_e004_roles"]["RoleBureauNom"]] = $r["bal_vue_e004_roles"]["RoleBureauNom"]; 

            }

            foreach($request_member as $r)
            {
                $member[ $r["bal_vue_e101_adhesion"]["PersonneId"]] = $r["bal_vue_e004_users"]["Identite"]; 

            }
            $i =0;
            foreach($request_staff as $r)
            {
                $staff[$r["bal_vue_e004_users"]["PersonneId"]] = array("id"=>$r["bal_vue_e004_users"]["PersonneId"],"identite"=>$r["bal_vue_e004_users"]["Identite"],"role"=>$r["bal_vue_e004_users"]["RoleBureauNom"]);
                $i++;
            
            }
            $this->set("roles",$role);
            $this->set("staff",$staff);
            $this->set ("members",$member);
            $this->set ("annee",$annee);
        }
     public function deleteStaff($idPersonne,$exercice,$role)
    {
        if(isset($idPersonne)&&isset($exercice)&&isset($role))
        {
            $this->makeCall("Delete_BAL_Vue_E004_Users",array($idPersonne,$_SESSION["Auth"]["ConseilFCPEId"],$exercice,$role));
            $this->Session->setFlash('Responsable supprimé !', 'flash/success');
        }
        $this->redirect( array('controller' => 'Associations', 'action' => 'E004',$exercice));
     }

    public function updateStaff($idPersonne,$role,$exercice,$previous_id,$previous_role)
     {
        if(isset($idPersonne)&&isset($exercice)&&isset($role))
            {
                $this->makeCall("Delete_BAL_Vue_E004_Users",array($previous_id,$_SESSION["Auth"]["ConseilFCPEId"],$exercice,$previous_role));
                $this->makeCall("Save_BAL_Vue_E004_Users",array($_SESSION["Auth"]["PSessionId"],$idPersonne,$_SESSION["Auth"]["ConseilFCPEId"],$exercice,$role));
            }   
            $this->redirect( array('controller' => 'Associations', 'action' => 'E004',$exercice));
  
        }





    public function E005() {
        $assocCourranteNom = "FCPE 86";
        $assocCourranteId = 2;
        $this->set('assocCourranteNom',$assocCourranteNom);

        $tutelle = $this->Association->query("SELECT AffilieId,AffilieNom,EMail FROM bal_vue_e005_assoc WHERE TutelleId = $assocCourranteId AND StatutDemande = 1");
        $this->set('tutelle', $tutelle);

        $demandes = $this->Association->query("SELECT AffilieId,AffilieNom,EMail,StatutDemande FROM bal_vue_e005_assoc WHERE TutelleId = $assocCourranteId AND StatutDemande != 1");
        $this->set('demandes', $demandes);
        
    }

    public function gestionTutelle($assocId = null, $op = null){

        $gt['PSessionId'] = '';
        $gt['PAffilieId'] = $assocId;
        $gt['PStatutDemande'] = $op;


        $this->makeCall("Save_BAL_Vue_E005_Assoc", $gt);
        $this->redirect(array('controller'=>'Associations', 'action'=>'e005'));
    }
}

?>