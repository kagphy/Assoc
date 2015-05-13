Installation
===============

Copier l'ensemble des fichiers et dossiers dans le répertoire courant du README vers le dossier cible.
Modifier les .htaccess en fonction de l'emplace de la racine des fichiers / dossiers.
Par exemple si le site est installé dans un sous dossier du répertoire racine de lecture d'apache, il faut le spécifier dans le .htaccess la base du site soit ajouter la ligne : "RewiteBase /Chemain de répertoire racine vers répertoire d'installation/".
Une fois cela effectué ce rendre dans le fichier app/Config/database.php, afin de spécifier les logs vers la base de données.
Le Module URLrewriting d'apache doit être lancé.
Si la configuration d'apache n'est pas correctement effectuée (message d'erreur apparaissant sur l'index) il faut effectuer le changement suivant:
Dans le fichier de configuration d'apache: "/etc/apache2/sites-available/default"

<Directory /var/www/>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
</Directory>

Le AllowOverride doit être à All et non pas à None.

L'application doit normalement être correctement installée, pour tout problème annexe nous contacter.