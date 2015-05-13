<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<a class="navbar-brand" href="<?php echo $this->Html->url('/'); ?>">Accueil</a>
			<li class="dropdown">
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Association<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<?php 
						if (isset($_SESSION['Association']['ConseilFCPENom'])) {
							echo "<li><a>" . $_SESSION['Association']['ConseilFCPENom'] . "</a></li>";
						} 
					?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E003')); ?>">Coordonnées</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E004')); ?>">Responsables</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E005')); ?>">Affiliations</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E006')); ?>">Etiquettes</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'associations', 'action'=>'E007')); ?>">Mailing</a></li>
					<li class="divider"></li>
					<li><a href="#">Nouveau</a></li>
					<li class="divider"></li>
					<li><a href="#">Supprimer</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Parent <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<?php 
						if (isset($_SESSION['Dossier']['Parent']['PersonneNom'], $_SESSION['Dossier']['Parent']['PersonnePrenom'])) {
							echo "<li><a>" . $_SESSION['Dossier']['Parent']['PersonneNom'] . " " . $_SESSION['Dossier']['Parent']['PersonnePrenom'] . "</a></li>";
						} 
					?>
					<li><a></a></li>
					<?php 
						if (isset($_SESSION['Exercice'])) {
							if (isset($_SESSION['Dossier']['Parent']['ParentId'])){
					?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'responsables', 'action'=>'E100')); ?>">Coordonnées</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'responsables', 'action'=>'E101')); ?>">Adhésion</a></li>
					<?php } ?>
					<li><a href="#">Nouveau</a></li>
					<?php 
						if (isset($_SESSION['Dossier']['Parent']['ParentId'])){ 
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
					<?php 
						if (isset($_SESSION['Dossier']['Eleve']['EleveNom'], $_SESSION['Dossier']['Eleve']['ElevePrenom'])) {
							echo "<li><a>" . $_SESSION['Dossier']['Eleve']['EleveNom'] . " " . $_SESSION['Dossier']['Eleve']['ElevePrenom'] . "</a></li>";
						} 
					?>
					<?php 
						if (isset($_SESSION['Dossier']['Parent']['ParentId'])){ 
					 ?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'eleves', 'action'=>'E200')); ?>">Coordonnées</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['Eleve']['EleveId'])){  
					?>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'eleves', 'action'=>'E201')); ?>">Scolarité</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['Parent']['ParentId'])){ 
					?>
					<li><a href="#">Nouveau</a></li>
					<?php 
						}
						if (isset($_SESSION['Dossier']['Eleve']['EleveId'])){ 
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
						if (isset($_SESSION['Dossier']['Eleve']['EleveId'])){ 
					?>
					<li class="dropdown-submenu">
						<a href="#">Opérations</a>
						<?php if (isset($bonoperation)) { ?>
						<ul class="dropdown-menu">
							<?php 
								for ($i=0; $i < sizeof($bonoperation); $i++) { 
									foreach ($bonoperation[$i] as $key => $value) {		
							?>
								<li><a href="<?php echo $this->Html->url(array('controller'=>'fournitures', 'action'=>'e300', $key)); ?>"><?php echo $value . " " . $key; ?></a></li>
							<?php 
									}
								}
							?>
                		</ul> 
                		<?php } ?>
					</li>
					<li><a href="#">Nouveau</a></li>
					<?php 
						}
						if (isset($_SESSION['Exercice'])){ 
					?>
					<li><a href="#">Supprimer</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'fournitures', 'action'=>'E301')) . "/" . "/" . $key; ?>">Etats de vétusté</a></li>
					<li><a href="<?php echo $this->Html->url(array('controller'=>'fournitures', 'action'=>'E302')); ?>">Produits</a></li>
					<li><a href="#">Fournisseur</a></li>
					<?php } ?>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Compta <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<?php 
						if (isset($_SESSION['Dossier']['Parent']['ParentId'])){ 
					?>
					<li><a href="#">Dossier en cours</a></li>
					<?php 
						}
						if (isset($_SESSION['Exercice'])){ 
					?>
					<li><a href="#">Opérations</a></li>
					<li><a href="#">Relevés fiscaux</a></li>
					<li><a href="#">Tableau de bord</a></li>
					<li><a href="#">Modes de règlement</a></li>
					<?php } ?>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Exercice</a></li>
					<li><a href="#">Utilisateurs</a></li>
					<?php 
						if (isset($_SESSION['Exercice'])){ 
					?>
					<li><a href="#">Exporter</a></li>
					<?php } ?>
					<li><a href="#">Sauvegarder</a></li>
				</ul>
			</li>
			<?php if (isset($_SESSION['Exercice'])){  ?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Instances <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Bureau association</a></li>
					<li><a href="#">CA association</a></li>
					<li><a href="#">Conseil d'école</a></li>
					<li><a href="#">Conseils de classes</a></li>
					<li><a href="#">Instances d'établissement</a></li>
				</ul>
			</li>
			<?php 
				}
				if (isset($_SESSION['Exercice'])){ 
			?>
			<ul class="nav navbar-nav navbar-right">
				<form class="navbar-form navbar-right" role="search">
			        <div class="form-group">
			          <input type="text" id="Recherche" class="form-control" placeholder="Rechercher">
			        </div>
			        <a class="btn btn-default" onclick='javascript:window.location = "<?php echo $this->Html->url(array('controller'=>'recherches', 'action'=>'recherche')); ?>/" + document.getElementById("Recherche").value;'>Rechercher</a>
			    </form>
			</ul>

			<?php } ?>
            <div class="navbar-brand">Exercice
               <select id="Exercice" class="span2" onchange='javascript:window.location = "<?php echo $this->Html->url(array('action'=>'changeExercice')); ?>/" + document.getElementById("Exercice").value;'>
                    <option value="2013" <?php if($_SESSION['Exercice']==2013) echo "selected"; ?>>2013</option>
                    <option value="2014" <?php if($_SESSION['Exercice']==2014) echo "selected"; ?>>2014</option>
                </select>
            </div>

            <div class="navbar-brand">
            	<?php 
            		echo "Dossier ouvert: ";
            		if (isset($_SESSION['Dossier']['PersonneNom'], $_SESSION['Dossier']['PersonnePrenom'])) {
            			echo "[Responsable - " . $_SESSION['Dossier']['PersonneNom'] . " " . $_SESSION['Dossier']['PersonnePrenom'] . "]";
            		}
            		if (isset($_SESSION['Dossier']['EleveNom'], $_SESSION['Dossier']['ElevePrenom'])) {
            			echo "[Eleve - " . $_SESSION['Dossier']['EleveNom'] . " " . $_SESSION['Dossier']['ElevePrenom'] . "]";
            		}
            		if (!isset($_SESSION['Dossier']['PersonneNom'], $_SESSION['Dossier']['PersonnePrenom'])) {
            			if (!isset($_SESSION['Dossier']['EleveNom'], $_SESSION['Dossier']['ElevePrenom'])) {
            				echo "[Aucun]";
            			}
            		}
            	?>
            </div>

            <div class="navbar-brand"><?php echo $_SESSION['Dossier']['EleveNom'] . " " . $_SESSION['Dossier']['ElevePrenom']; ?>
            </div>

            <?php if (isset($_SESSION['Auth']['User']['InterlocuteurId'])){ ?>
            <ul class="nav navbar-nav navbar-right">
				<form class="navbar-form navbar-right">
			        <a class="btn btn-default">Se déconnecter</a>
			    </form>
			</ul>
			<?php } ?>
		</ul><!-- /.nav navbar-nav -->
	</div><!-- /.navbar-collapse -->
	</div>
</nav><!-- /.navbar navbar-default -->