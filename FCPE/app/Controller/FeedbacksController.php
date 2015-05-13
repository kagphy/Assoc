<?php

class FeedbacksController extends AppController {


        public function beforeFilter() {
            $this->Auth->allow();
        }

        public function submit(){
            if ($this->request->is('post')) {
            $this->Feedback->create();
            if ($this->Feedback->save($this->request->data)) {
                $this->Session->setFlash('Formulaire de retour envoyé avec succès', 'flash/success');
                return $this->redirect('/');
            } else {
                $this->Session->setFlash('Erreur. Merci de réessayer.', 'flash/error');
            }
        }
        }
}

?>