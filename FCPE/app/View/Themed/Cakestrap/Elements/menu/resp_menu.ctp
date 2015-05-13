<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<a class="navbar-brand" href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'login_asso_e001')); ?>">Accueil</a>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Association <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a>Identification de l'association</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E005')); ?>">Affiliations</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E006')); ?>">Etiquettes</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E007')); ?>">Mailing</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Parent <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a>Identification du parent</a></li>
					<?php 
						if (isset($_SESSION['Dossier']['Exercice'])) {
							if (isset($_SESSION['Dossier']['ParentId'])){
					?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'responsables', 'action'=>'E100')); ?>">Coordonnées</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'responsables', 'action'=>'E101')); ?>">Adhésion</a></li>
					<?php } ?>
					<li><a href="#">Nouveau</a></li>
					<?php 
						if (isset($_SESSION['Dossier']['ParentId'])){ 
					?>
					<li><a href="#">Supprimer</a></li>
					<?php 	
							}
						}
					?>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Eleve <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a>Identification de l'élève</a></li>
					<?php 
						if (isset($_SESSION['Dossier']['ParentId'])){ 
					 ?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'eleves', 'action'=>'E200')); ?>">Coordonnées</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['EleveId'])){  
					?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'eleves', 'action'=>'E201')); ?>">Scolarité</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['ParentId'])){ 
					?>
					<li><a href="#">Nouveau</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['EleveId'])){ 
					?>
					<li><a href="#">Supprimer</a></li>
					<?php
						}
					?>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Fournitures <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<?php  
						if (isset($_SESSION['Dossier']['EleveId'])){ 
					?>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'fournitures', 'action'=>'E300')); ?>">Opérations
						</a>
					</li>
					<li><a href="#">Nouveau</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['Exercice'])){ 
					?>
					<li><a href="#">Supprimer</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'fournitures', 'action'=>'E301')); ?>">Etats de vétusté</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'fournitures', 'action'=>'E302')); ?>">Produits</a></li>
					<li><a href="#">Fournisseur</a></li>
					<?php } ?>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Compta <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<?php 
						if (isset($_SESSION['Dossier']['ParentId'])){ 
					?>
					<li><a href="#">Dossier en cours</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['Exercice'])){ 
					?>
					<li><a href="#">Opérations</a></li>
					<li><a href="#">Tableau de bord</a></li>
					<li><a href="#">Modes de règlement</a></li>
					<?php } ?>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<?php 
						if (isset($_SESSION['Dossier']['Exercice'])){ 
					?>
					<li><a href="#">Exporter</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php 
				}
				if (isset($_SESSION['Dossier']['Exercice'])){ 
			?>
			<ul class="nav navbar-nav navbar-right">
				<form class="navbar-form navbar-right" role="search">
			        <div class="form-group">
			          <input type="text" class="form-control" placeholder="Rechercher">
			        </div>
			        <button type="submit" class="btn btn-default">Rechercher</button>
			    </form>
			</ul>
			<?php } ?>
		</ul><!-- /.nav navbar-nav -->
	</div><!-- /.navbar-collapse -->
	</div>
</nav><!-- /.navbar navbar-default -->