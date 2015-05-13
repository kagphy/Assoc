<?php  

/**
 * Classe permettant de gérer le panel admin
 */
class AdminsController extends AppController {

	public function beforeFilter() {
        $this->Auth->allow();
    }

}

?>