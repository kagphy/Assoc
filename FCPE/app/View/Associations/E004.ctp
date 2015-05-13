
<!-- onglet Recherche --> 
<div class="container">
		 <div class="row">
	    <div class="col-md-8 col-md-offset-2">	    	
	    		<h2> Responsables </h2>

	    		<?php
	    			$années = array(2011=>2011,2012=>2012,2013=>2013,2014=>2014);
	    		    $this->Form->create('Staff');
	    		 echo $this->Form->input('Exercice : ',array("options"=> $années ,"selected"=> $annee,"id"=>"ann", "onChange"=>
   					 	"   var select = document.getElementById('ann' );
    						var valeur = select.options[select.selectedIndex].value;
    		  				document.location.href = '". $this->Html->url(array("controller" => "Associations","action" => "E004"))."'+'/'+valeur;"));

	    		?>
	    		 <!-- table des responssables -->
	    		 
	    		<TABLE BORDER="0"> 
 					<TR> 
 						<TH> <a href="#" class="btn btn-lg btn-success"
  								data-toggle="modal"
   								data-target="#basicModal">Ajouter
   							</a> 
						<TH> Personne </TH> 
 						<TH> Rôle </TH> 
 					</TR> 

  			 <?php
  		      
  			 		    		 
	    			
	   			 	$idC = 0;
	   			 	foreach ($staff as $k => $s) {

	   			 		
	   			 		echo "<TR>";
	   			 		echo '<TD> '. $this->Html->link('<span class="btn btn-danger">Supprimer</span>',array("controller"=>"Associations","action"=>"deleteStaff",$s["id"],$annee,$s["role"]),array('escape' => false)) .'</TD> '; 
	   			 			echo "<TD> ". $this->Form->input('', array(
      				 'options' =>$members,'selected'=>$s[ "id"],'id'=> 'I'.$idC,'onChange'=>'update('.$idC.','.$s[ "id"].',"'.$s["role"].'");')). "  </TD> ";
	   			 		echo "<TD> ". $this->Form->input('', array(
      				  'options' =>$roles,'selected' =>$s["role"],'id'=> 'R'.$idC,'onChange'=> 'update('.$idC.','.$s[ "id"].',"'.$s["role"].'");')) . "  </TD> ";
	   			 		echo "</TR>";
	   			 		$idC++;

	   			 	}
				?>
				
				</TABLE>
			 <?php  echo $this->Form->end();
				  ?>

    	 </div>	
	</div>
</div>


   <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<?php echo $this->Form->create('NewStaff');	?>
            <div class="modal-header">
        
            <h4 class="modal-title" id="myModalLabel">Ajouter un membre</h4>
            </div>
            <div class="modal-body">
                <?php
	    			
	    		 	
	    			 echo $this->Form->input('Identite', array(
      				 'options' =>$members, "class"=>"form-control","class"=>"form-control")); 
	    			 echo $this->Form->input('Poste', array(
      				 'options' =>$roles,"class"=>"form-control","class"=>"form-control")); 
	    		?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <?php  echo $this->Form->submit('enregistrer',array("class"=>"btn btn-primary"));
                 echo $this->Form->end();
              
                 ?>
        </div>
    </div>
  </div>
</div>
<script> function update(idChamp,previous_id,previous_role)
		{
			 var selectI = document.getElementById('I'+idChamp );
			 var selectR = document.getElementById('R'+idChamp );
    		 var valeurI = selectI.options[selectI.selectedIndex].value;
		     var valeurR = selectR.options[selectR.selectedIndex].value;	
			document.location.href =  <?php echo "'". $this->Html->url(array("controller" => "Associations","action" => "updateStaff"))."'";?> +'/'+valeurI +'/'+valeurR+'/'+<?php echo "'". $annee."'";?>+'/'+previous_id+'/'+previous_role;
		}
</script> 

document.getElementById('form').submit(); 
