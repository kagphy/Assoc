<?php  

/**
 * Classe permettant de gérer la compta
 */
class ComptasController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function E400($cheque=null) {
        $personneId = $_SESSION['Dossier']['Parent']['PersonneId'];
        $conseilfcpeId = $_SESSION['Association']['ConseilFCPEId'];
        $queryEntete = $this->Compta->query("SELECT * FROM bal_vue_e400_entete NATURAL JOIN bal_facture WHERE PersonneId=$personneId AND ConseilFCPEId=$conseilfcpeId;");
        for ($i=0; $i < sizeof($queryEntete); $i++) { 
            $entete['FactureId'] = $queryEntete[$i]['bal_vue_e400_entete']['FactureId'];
            $entete['CreationFact'] = $queryEntete[$i]['bal_vue_e400_entete']['CreationFact'];
            $entete['Montant'] = $queryEntete[$i]['bal_vue_e400_entete']['Montant'];
            $entete['Reglement'] = $queryEntete[$i]['bal_vue_e400_entete']['Reglement'];
        }
        $this->set('entete', $entete);

        $queryMoyen = $this->Compta->query("SELECT MoyenDePaiementNom FROM bal_vue_e400_modespaiement;");
        for ($i=0; $i < sizeof($queryMoyen); $i++) { 
            $moyendepaiement[$queryMoyen[$i]['bal_vue_e400_modespaiement']['MoyenDePaiementNom']] = $queryMoyen[$i]['bal_vue_e400_modespaiement']['MoyenDePaiementNom'];
        }
        $this->set('moyendepaiement', $moyendepaiement);

        $factureId = $entete['FactureId'];
        if (!is_empty($factureId)) {
            $queryOperations = $this->Compta->query("SELECT * FROM bal_vue_e400_operations WHERE FactureId=$factureId;");
            for ($i=0; $i < sizeof($queryOperations); $i++) { 
                foreach ($queryOperations[$i]['bal_vue_e400_operations'] as $key => $value) {
                    $operations[$i][$key] = $value;
                }
            }
            $this->set('operations', $operations);
        }

        if ($this->request->is('post')) {
            debug($this->request->data);
            if (!isset($this->request->data['Compta']['MontantRendu'], $this->request->data['Compta']['MontantPaiement'])) {
                $this->Session->setFlash('Exactement une valeur instanciée parmi Débit et Crédit.', 'flash/error');
                $this->redirect($this->referer());
            }
            else if (!empty($this->request->data['Compta']['MontantRendu']) &&  !empty($this->request->data['Compta']['MontantPaiement'])) {
                $this->Session->setFlash('Exactement une valeur instanciée parmi Débit et Crédit.', 'flash/error');
                $this->redirect($this->referer());
            }
            else if (!empty($this->request->data['Compta']['BanqueNom']) && $this->request->data['Compta']['MoyenDePaiementNom']!="Chèque") {
                $this->Session->setFlash('Le nom de la banque doit être seulement spécifié pour un paiement par chèque bancaire.', 'flash/error');
                $this->redirect($this->referer());
            }
            else {
                $saveOperation['PSessionId'] = $this->Session->read('Auth.User.SessionId');
                if ($this->request->data['Compta']['ComptabiliteId']=="") {
                    $saveOperation['PComptabiliteId'] = null;
                }
                else {
                    $saveOperation['PComptabiliteId'] = $this->request->data['Compta']['ComptabiliteId'];
                }
                $saveOperation['PFactureId'] = $this->request->data['Compta']['FactureId'];
                $saveOperation['PMoyenDePaiementNom'] = $this->request->data['Compta']['MoyenDePaiementNom'];
                if ($this->request->data['Compta']['BanqueNom']=="") {
                    $saveOperation['PBanqueNom'] = null;
                }
                else {
                    $saveOperation['PBanqueNom'] = $this->request->data['Compta']['BanqueNom'];
                }
                $saveOperation['PIdentifiantTransaction'] = $this->request->data['Compta']['IdentifiantTransaction'];
                if ($this->request->data['Compta']['MontantPaiement']=="") {
                    $saveOperation['PMontantPaiement'] = null;
                }
                else {$saveOperation['PMontantPaiement'] = $this->request->data['Compta']['MontantPaiement'];
                    $saveOperation['PMontantPaiement'] = $this->request->data['Compta']['MontantPaiement'];
                }
                if ($this->request->data['Compta']['MontantRendu']=="") {
                    $saveOperation['PMontantRendu'] = null;
                }
                else {$saveOperation['PMontantRendu'] = $this->request->data['Compta']['MontantRendu'];
                    $saveOperation['PMontantRendu'] = $this->request->data['Compta']['MontantRendu'];
                }
                $this->makeCall("Save_BAL_Vue_E400_Operations", $saveOperation);
                $this->Session->setFlash('Opération ajoutée/modifiée.', 'flash/success');
                $this->redirect($this->referer());
            }
        }
    }

    public function saveOperation($comptabiliteId=null, $factureId=null, $moyendepaiement=null, $banquenom=null, $identifiantTransaction=null, $montantPaiement=null, $montantRendu=null) {
        $saveOperation['PSessionId'] = $this->Session->read('Auth.User.SessionId');
        $saveOperation['PComptabiliteId'] = $comptabiliteId;
        $saveOperation['PFactureId'] = $factureId;
        $saveOperation['PMoyenDePaiementNom'] = $moyendepaiement;
        $saveOperation['PBanqueNom'] = $banquenom;
        $saveOperation['PIdentifiantTransaction'] = $identifiantTransaction;
        $saveOperation['PMontantPaiement'] = $montantPaiement;
        $saveOperation['PMontantRendu'] = $montantRendu;
        $this->makeCall("Save_BAL_Vue_E400_Operations", $saveOperation);
        $this->Session->setFlash('Opération modifiée.', 'flash/success');
        $this->redirect($this->referer());
    }

    public function deleteOperation($comptaid) {
        $deleteOperation['PSessionId'] = $this->Session->read('Auth.User.SessionId');
        $deleteOperation['PComptabiliteId'] = $comptaid;
        $this->makeCall("Delete_BAL_Vue_E400_Operations", $deleteOperation);
        $this->Session->setFlash('Opération supprimée.', 'flash/success');
        $this->redirect($this->referer());
    }

 
function E401($decalage =0){                
                 if ($this->request->is('post')) {                  
                    if (($this->request->data["maj_op"]["date Depot"] != "")||($this->request->data["maj_op"]["date Valeur"] != ""))
                    {
                         foreach ($this->request->data["maj_op"] as $key => $value) {
                            if (substr($key, 0,1)=='Z')
                            {
                                if (substr($key, 1,1)=='a') $provi = $this->request->data["maj_op"]["date Depot"]; else $provi =$this->request->data["maj_op"]["date Valeur"];
                                if ($value == 0) {
                                    $fields[substr($key, 2)][substr($key, 1,1)]= null;
                                }elseif ($value == 1) {
                                    $fields[substr($key, 2)][substr($key, 1,1)]= $provi;
                                    $fields[substr($key, 2)]["update"] = true;
                                }else
                                {
                                    $fields[substr($key, 2)][substr($key, 1,1)]= $value;
                                }
                            }
                        }

                        foreach ($fields as $key => $value) {
                            if (isset($value["update"]))
                            {
                                $this->makeCall("Save_BAL_Vue_E401_Operations",
                                        array($_SESSION["Auth"]["PSessionId"] ,$key, $value["a"],$value["b"]));
                            }
                        }
                    }
                          $this->redirect(
                         array('controller' => 'Comptas', 'action' => 'E401',$decalage + $this->request->data["maj_op"]["test"]));

                }            
                $size = 20;
                $requestOperations =$this->Compta->query("SELECT * FROM bal_vue_e401_operations Where ConseilFCPEId= ".$_SESSION["Auth"]["ConseilFCPEId"]." order by Libelle ;");
                 $nb_rep = count($requestOperations);
                if ($decalage<0) { $this->redirect(
                         array('controller' => 'Comptas', 'action' => 'E401',0));}
                if ($decalage>$nb_rep) { $this->redirect(
                         array('controller' => 'Comptas', 'action' => 'E401', ((int) ($nb_rep/$size))*$size));}
                $this->set("op",$requestOperations);
                $this->set("decal",$decalage);
                $this->set("size",$size);
                $this->set("nb_rep",$nb_rep);
        }


        function deleteOp($id,$decal)
        {
            $this->makeCall("Delete_BAL_Vue_E401_Operations",array($_SESSION["Auth"]["PSessionId"],$id));
             $this->redirect(
                         array('controller' => 'Comptas', 'action' => 'E401', $decal));

        }

}

?>