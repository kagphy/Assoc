
	
	<!--Vue qui représente l'interface de la page produit i.e une page qui liste les produit qui sont disponibles 
	en affichant leur nom, l'auteur, la marque/l'éditeur, leur année de parution, leur vetusté, le nombre de d'exemplaires qui sont
	encore en stock, le prix ainsi que le code barre.*/

	//Représentation de la varibale $queryNbProducts transmis par le controller FournituresController : 

	/*array(
		(int) 0 => array(
			(int) 0 => array(
				'COUNT(DISTINCT Designation)' => '331'
			)
		)
	)*/

	//echo $this->Html->link('Ajouter un produit', '/redirect/url', array('class' => 'button'));-->



<ul class="nav navbar-nav navbar-left">
	<form class="navbar-form navbar-right">
        <a class="btn btn-default" onclick='javascript:window.location = "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'logout')); ?>";'>Ajouter Produit
        </a>
    </form>
</ul>

<?php
	
	
	echo '<br/><br/><br/>';
	echo '<h3>Ressources</h3>';
	echo '<table border="1" cellpadding="10" cellspacing="1" width="100%">
				<tr>
					<th>Nom</th>
					<th>Auteur</th>
					<th>Marque/Editeur</th>
					<th>Année parution</th>
					<th>Code Barre</th>
					<th>Suppression/Edition</th>
				</tr>';

	
	//Pour chaque livre distinct trouvé				
	foreach($queryProducts as $value)
	{
		echo'<tr>';
			//On écrit le nom du livre
			echo'<td>'.$value['bal_vue_e302_produit']['Designation'].'</td>';
			echo'<td>'.$value['bal_vue_e302_produit']['Auteur'].'</td>';
			echo'<td>'.$value['bal_vue_e302_produit']['MarqueOuEditeur'].'</td>';
			echo'<td>'.$value['bal_vue_e302_produit']['AnneeParution'].'</td>';
			echo'<td>'.$value['bal_vue_e302_produit']['CodeBarre'].'</td>';
			
			//Création d'un formulaire caché dont les champs sont déjà prépremplis (l'utilisateur ne doit pas le voir).
			echo'<td>'.$this->Form->create().$this->Form->hidden('designation', array('value'=> $value['bal_vue_e302_produit']['Designation']));
			echo $this->Form->hidden('auteur', array('value'=>$value['bal_vue_e302_produit']['Auteur']));
			echo $this->Form->hidden('marqueouediteur', array('value'=>$value['bal_vue_e302_produit']['MarqueOuEditeur']));
			echo $this->Form->end('Supprimer/Editer').'</td>';

		echo'</tr>';
	}

	echo '</table>';
?>