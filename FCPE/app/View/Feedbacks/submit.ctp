
<div class="container">
		 <div class="row">
		 	<p> 
		 		<h2>
	    		 	<div class="col-md-6">
	    				Retours sur le site 
	    			</div>
	    		</h2>	
    		</p>
    	</div>
    <div class="row">
	    <div class="col-md-8 col-md-offset-2">

	   			 <?php 	
	   			 	//note : je "force" le type, ce qui ne sera pas necessaire si le controller est relié a un modèle
					echo $this->Form->create('Feedback', array(
		        	'class' => 'form-horizontal',
		       	 	'inputDefaults' => array(
		            'div' => 'form-group',
		            'class' => 'form-control',
		            'format' => array('label', 'input'),
		            

		       		 )
		   			 ));
					echo $this->Form->input('panel', array(
		        	'class' => 'form-control',
		        	'placeholder' => 'la partie du site que vous commentez',
		        	'label' => 'Fonctionnalité testée'
		   			));
					echo $this->Form->input('path', array(
		        	'class' => 'form-control',
		        	'placeholder' => 'Nous vous prions de lister les pages traversées et les actions effectués',
		        	'label' => 'Actions effectués',
		        	'type'  => 'textArea'
		    		));
						echo $this->Form->input('returns', array(
		        	'class' => 'form-control',
		        	'placeholder' => 'Les choses qui vous ont plus , les choses qui vous dérangent, les bugs rencontrés en chemin ....',
		        	'label' => 'Retours',
		        	'type'  => 'textArea'
		    		));

					echo  ' <dd>';
					echo $this->Form->end('Soumettre le retour. Merci !');

				
				?>
    	 </div>	
	</div>
</div>