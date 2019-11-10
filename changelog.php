<meta charset="UTF-8" />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 19/02/2018             <br />
# @Version : 3.1.30     	 	 <br />
#################################<br />
<br />
<u>Notice:</u><br />
- Monitor: Page déplacé utiliser le nouveau lien présent dans les paramètres <br />

<u>Update:</u><br />
-  Paramètre: un nouveau paramètre permet de définir le timeout de la session (./index.php ./admin/parameters.php)<br />
-  Paramètre: un nouveau paramètre permet de forcer le fuseau horaire, sur une valeur différente de celle de php.ini(./index.php ./admin/parameters.php)<br />
-  Système: Mise à jour des informations de sécurité (./system.php)<br />
-  Barre utilisateur: Mise à jour des avatars des profils (./images/avatar/*)<br />
-  Composant: Mise à jour du composant WOL version 2.1 (./components/wol/*)<br />
-  Sauvegarde manuelle: Ajout d'un contrôle sur la présence du dump SQL (./admin/backup.php)<br />
-  Mail automatique: Nouveau paramètre envoi a l'utilisateur lors de l'ouverture d'un ticket par l'utilisateur (./core/auto_mail.php ./core/ticket.php ./mail2ticket.php)<br />
<br />
<br />
<u>Bugfix:</u><br />
-  Fiche utilisateur: Erreur sur la liste des vues (./admin/user.php) <br />
-  Mail: Certains mails automatique n'était pas envoyé à l'adresse de copie si le demandeur n'avait pas d'adresse mail (./core/auto_mail.php) <br />
-  Connecteur IMAP: Lors de la reception d'un mail non HTML, l'adresse mail de l'émetteur n'était pas affiché dans la description du ticket (./mail2ticket.php) <br />
-  Liste des tickets: la priorité aucune n'avait pas la couleur grise (SQL) <br />
-  Ticket: lors de la sélection du demandeur, la liste des priorité faisait apparaitre deux fois la valeur sélectionnée dans la liste (./ticket.php) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 20/01/2018             <br />
# @Version : 3.1.29     	 	 <br />
#################################<br />
<br />

<u>Update:</u><br />
- Composant PHPMailer: Nouvelle version 6.0.3 (./components/PhpMailer)<br />
- Passage en requêtes préparées: (./admin/* ./register.php ./login.php)<br />
- Connecteur LDAP: Compatibilité avec Samba4 (./core/ldap.php)<br />
- Connecteur LDAP: Amélioration de la prise en charge des connexion chiffrés (./core/ldap.php)<br />
- Connecteur LDAP: SSO disponible cf FAQ (./index.php)<br />
- Système: Contrôle de version de PHP pour respecter les prérequis serveur (./system.php)<br />
- Paramètre: Augmentation de la taille maximal des adresses mail en copie à 150 char. (SQL)<br />
- Tickets: Insertion de lien possible dans la description, click droit pour ouvrir. (./wysiwyg.php)<br />

<br />
<br />
<u>Bugfix:</u><br />
- Paramètres Généraux: La liste des états par défaut ne tenait pas compte du renommage dans la liste des états (./admin/parameters.php) <br />
- Connecteur IMAP: erreur lors de la réception des mails (./mail2ticket.php) <br />
- Connecteur LDAP: Erreur de modification d'une fiche utilisateur suite synchro AD(./login.php) <br />
- Mail: Message d'erreur si le champ début de mail était à vide et l'utilisateur avec la langue anglaise (./core/mail.php) <br />
- Mail: Erreur lors de l'utilisation du bouton mail par l'utilisateur (./core/mail.php) <br />
- Mail: Erreur lors de l'envoi de mail avec le profile utilisateur avec pouvoir sur les mails d'ouverture automatique  (./core/auto_mail.php) <br />
- Variable non initialisée: La variable user_agent n'était pas initialisée, si non transmise par le navigateur (./index.php) <br />
- Ticket: Le champ résolution pouvaient ne pas être pris en compte sur les nouveaux tickets (./core/ticket.php) <br />
- Enregistrement utilisateurs: Certains message d'erreur n'était pas traduit si une langue différente du français était détectée (./register.php) <br />
- Liste des réseaux des équipements: Sur certaines colonnes la traduction n'était pas correct (./admin/list.php) <br />
- Équipements: Gestion de la date de derniers ping par interface (./core/asset_network_scan.php) <br />
- Fonction sondage: erreur SQL sur l'utilisation du lien présent dans le mail (./survey.php) <br />
- Statistiques: Le filtre par technicien n'était pas appliqué sur certains tableaux (./stat/tables.php) <br />
- Installation: Erreur de détection de l'HTTPS (./install/index.php) <br />
- Liste des équipements: La liste des équipements pouvait être mélangé si aucune ip n'étaient renseignés (./asset_list.php) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 04/12/2017             <br />
# @Version : 3.1.28     	 	 <br />
#################################<br />
<br />
<u>Update:</u><br />
- Composant PHPImap: Nouvelle version 3.0.5 (./components/PHPImap/*)<br />
- Équipement: Le bouton ping sur l'équipement IP, lance désormais un ping sur toutes les interfaces et affiche le drapeau à coté (./core/ping.php, ./asset.php)<br />
- Ticket: L'ordre d'affichage de la liste des catégories peut être définie manuellement dans l'administration des listes (./admin/list.php, ./ticket.php)<br />
- Fiche utilisateur: La saisie de mot de passe vide n'est plus autorisé (./admin/user.php)<br />

<br />
<br />
<u>Bugfix:</u><br />
- Menu Société: l'ouverture des tickets du menu société s'affichait avec une page blanche (./index.php) <br />
- Variable non initialisée: Si User-Agent header est filtré par un firewall (./index.php) <br />
- Variable non initialisée: La variable imap_user n'était pas initialisée(./admin/parameters.php) <br />
- Ticket: Dans certains cas des erreurs s'affichaient dans la liste des demandeurs (./ticket.php) <br />
- Ticket: AccessKey du bouton "Enregistrer et fermer" est désormais F (./ticket.php) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 17/10/2017             <br />
# @Version : 3.1.27     	 	 <br />
#################################<br />
<br />
<u>Update:</u><br />
- Composant PHPMailer: Nouvelle version 6.0.1 (./components/PHPMailer/*)<br />
- Ticket: Pour les utilisateurs du champ "service du demandeur" ce dernier est désormais associé au service du demandeur par défaut, si il n'en dispose que d'un (./ticket.php)<br />
- Profile utilisateur: Si la gestion des agences est activé alors il est possible de visualiser et modifier les associations d'agences sur la fiche utilisateur (./ticket.php)<br />
- Système: Ajout d'une section sécurité. (./system.php)<br />
<br />
<br />
<u>Bugfix:</u><br />
- Procédure: Les fichiers de plus de 10MO n'étaient pas transférés (./procedure.php) <br />
- Mails: Erreur lors de l'envoi de mail automatique alors que l'utilisateur ne dispose pas de mail ou que le demandeur "Aucun" à été sélectionné (./core/auto_mail.php) <br />
- Ticket: Suppression de l'affichage du pourcentage d'avancement du ticket dans le titre si le temps passé et temps estimé ne sont pas affichés (./core/ticket.php) <br />
- Ticket: L'ordre de trie des criticité tien uniquement compte de l'ordre définit dans la gestion des listes. (./ticket.php) <br />
- Liste des tickets: L'icône horloge rouge indiquant un retard, ne s'affiche plus si le droit sur la date de résolution estimé du ticket est désactivé. (./dashboard.php) <br />
- Barre utilisateur: Suppression de l'affichage du bloc "Charge" si l'affichage du temps passé et estimé ne sont pas affichés (./index.php) <br />
- Ticket non lu: erreur lors de l'utilisation du bouton cloture de ticket sur un profil technicien ou administrateur (./core/ticket.php)<br />
- Système: Erreur de contrôle de la quantité de mémoire quand la valeur était en gigabytes. (./system.php)<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 14/09/2017             <br />
# @Version : 3.1.26     	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Ticket: Deux icônes sont présent dans la barre des titre si un ticket est planifié ou si une alerte est positionnée (./ticket.php)<br />
- Ticket: Possibilité d'activer ou de désactiver le cloisonnement par service du champ type via un nouveau droit ticket_type_service_limit  (./ticket.php)<br />
- Ticket: Lors de la suppression d'une entrée dans la liste des temps, la valeur était perdu sur le ticket lors d'un nouvel enregistrement (./ticket.php)<br />
- Équipements: Il est possible de cloisonner les équipements par société, jonction entre un équipement et une société via l'utilisateur associé cf droit asset_list_company_only et doc. (./menu.php ./asset_list.php)<br />
- Procédure: Il est possible de cloisonner les procédure par société cf droit procedure_list_company_only et procedure_company. (./procedure.php)<br />
- Procédure: Le bouton de création d'une nouvelle procédure est désormais dans le menu de gauche, afin de s'uniformiser avec les création de ticket ou d'équipement. (./menu.php ./procedure)<br />
- Statistique Équipements: Il est possible de filtrer les graphiques par société a l'aide d'un nouveau filtre. (./procedure.php)<br />
- Export CSV Équipements: Le fichier CSV exporté contenant les équipement dispose d'une nouvelle colonne société. (./core/export_asset.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Ticket: Les tickets supprimés était encore accessible via le lien sur un mail (./index.php) <br />
- Calendrier: Les tickets supprimés était encore visible (./planning.php) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 04/09/2017             <br />
# @Version : 3.1.25     	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Composant PHPImap: Nouvelle version 2.0.9 (./components/PHPimap/*)<br />
- Composant PHPMailer: Nouvelle version majeure 6.0.0 (./components/PHPMailer/*)<br />
- Mobile: Optimisation de l'affichage des tickets et des matériels sur mobile<br />
- Ticket et Fiche équipement: Il est possible d'utiliser les raccourcis clavier ALT+SHIFT+ X consulter les infos bulles des boutons pour connaitre les raccourcis (./ticket.php asset.php)<br />
- Ticket: Il est possible de cloisonner le type et la priorité par service (./ticket.php)<br />
- Liste des tickets: Sur les colonnes techniciens et utilisateur affichage du prénom et nom complet au survol de la souris (./dashboard.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Ticket: Nouveau droit permettant d'activer ou désactiver le verrouillage du champ technicien lorsque la limite par ticket est activée et qu'il déclare un ticket pour un autre service (./ticket.php) <br />
- Ticket: Lors de l'utilisation d'un modèle de ticket le lieu n'était pas dupliqué (./ticket_template.php) <br />
- Ticket: Lors de la modification d'un ticket, lors de la modification du service le premier changement n'était pas pris en compte (./ticket.php) <br />
- Liste des tickets: Dans la vue activité erreur lors de l'utilisation du filtre technicien deux fois de suite (./index.php ./dashboard.php) <br />
- Liste des tickets: Dans la vue activité lors de l'utilisation du bouton nouveau ticket si l'on envoi un mail la redirection n'était pas faites sur la vue activité (./menu.php ./core/ticket.php ./core/mail.php) <br />
- Mobile: Les champs d'auto complétion ne fonctionnait pas sur mobile (./asset.php ./ticket.php) <br />
- Statistique: La courbe des tickets résolu tenait compte uniquement de la date de résolution et pas de l'état(./stat/line_tickets.php ) <br />
- Système: Variable non initialisée si apache est en mode ServerTokens=Prod (./stat/system.php ) <br />
- Mails: Défaut d'affichage des mail dans outlook lors de l'impression liée à la taille du cadre (./core/mail.php ) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 04/08/2017             <br />
# @Version : 3.1.24     	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Composant PHPMailer: Nouvelle version disponible https://github.com/PHPMailer/PHPMailer/releases/tag/v5.2.24 (./components/PHPMailer/*)<br />
- Liste des tickets: Il est possible de personnaliser pour l'ensemble des utilisateurs de l'application l'état par défaut à la connexion de l'application (nouveau paramètre). (./parameters.php ./login.php)<br />
- Tickets: Dans les éléments de résolution si un texte type lien est détecté, il est alors convertit en lien hypertexte. (./threads.php)<br />
- Équipements: les champs demandeur et localisation ont été optimisés avec de l'auto-complétion (./index.php ./asset.php)<br />
- Équipements: Amélioration de la vue garantie (./index.php ./asset.php)<br />
- Gestion des agences: Un utilisateur peut faire partie d'un service et d'une agence (./index.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Liste des tickets: Un défaut d'affichage pouvait apparaitre lors l'affichage de la colonne société (./dashboard.php) <br />
- Liste des tickets: Sur la vue activité certains anciens tickets pouvaient s'afficher en non lus (./dashboard.php) <br />
- Ticket: le bouton impression ne lançait plus la fenêtre système d'impression (./ticket_print.php) <br />
- Composant: le fichier de version de php-gettext pouvais ne pas être présent (components\php-gettext\VERSION) <br />
- Impressions: Les impressions dans le navigateur n'affiche plus les URL de tous les liens (./template/bootstrap) <br />
- Mail automatique: Avec certaines combinaison de paramétrage dans les mail automatique, l'option d'envoi de mail au technicien lors de la modification d'un ticket par un utilisateur ne fonctionnait pas (./core/auto_mail.php) <br />
- Statistiques: Sur le premier graphique d'évolution des tickets, les compteurs sous le titre étaient erronés et la courbe des tickets avancés ne donnait pas le nombre de tickets distinct (./stat_line.php ./stat/line_ticket.php)<br  />
- Statistiques: Sur le premier graphique la courbe des tickets avancés tenait compte des tickets fermés également (./core/ticket_line.php )<br  />
- Statistiques: Sur le filtre par technicien les administrateurs n'était pas présent dans la liste si paramétré  (./ticket_stat.php)<br  />
- Administration: Dans la gestion des listes les nom des colonnes sont désormais traduite (./admin/list.php)<br />
- Connecteur LDAP: la mise a jour d'adresse mail avec des apostrophes posait problème (./core/ldap.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 18/07/2017             <br />
# @Version : 3.1.23     	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Liste des tickets: Nouveaux compteurs disponible dans la vue activité, avec les tickets ouverts, fermés et avancé dans la période (./dashboard.php)<br />
- Liste des tickets: Nouvelles couleurs sur les numéros de tickets voir le détail en passant le curseur sur le numéro, un nouvel indicateur de nouveau ticket est présent: une pastille rouge, les couleurs sont valables sur la vue activité prenant en compte la période sélectionnée et sur les listes des tickets (./dashboard.php)<br />
- Liste des tickets: Nouvelle colonne disponible "service du demandeur" activable par un nouveau droit "dashboard_col_user_service" (./dashboard.php)<br />
- Ticket: Sur le champ agence avec un profil technicien lors de l'enregistrement d'un ticket, la liste des agences reste limité à celles associées au demandeur (./ticket.php)<br />
- Statistiques: Changement des couleurs des courbes d'évolution des tickets, rouge ouverts, vert fermés, bleu avancés (./stats/line_ticket.php)<br />
- Statistiques: Ajout de deux nouvelles colonnes dans le tableau des répartition des temps par statuts (./stats/tables.php)<br />
- Gestion des agences: Un technicien peut faire partie d'une agence et d'un service (./admin/parameters.php ./core/mail.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Ticket: l'ordre de trie du champ criticité après validation était inversé (./ticket.php)<br />
- Ticket: Le champ équipement restait affiché si la fonction équipement était active et que le champ était désactivé dans les droits (./ticket.php)<br />
- Liste des tickets: le filtre par lieu avoir des dysfonctionnements dans certains cas (./dashboard.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 05/07/2017             <br />
# @Version : 3.1.22     	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Connecteur IMAP: Il est possible de supprimer l'indicateur "--- vous pouvez répondre..." dans les messages émit via une nouvelle option dans Administration > Paramètres > Connecteur IMAP "Gérer les réponses dans les mails" (./admin/parameters.php ./core/mail.php)<br />
- Connecteur IMAP: Possibilité d'activer ou désactiver la verification des certificats SSL du serveur de messagerie (./admin/parameters.php)<br />
- Connecteur LDAP: Il est possible de ne pas désactiver les utilisateurs crée manuellement dans GestSup lors d'une synchronisation LDAP cf paramètre du connecteur (./admin/parameters.php ./core/ldap.php)<br />
- Fonction sondage: L'adresse mail d'émission du mail de sondage est celle paramétré dans "Adresse de l'émetteur", si ce paramètre n'est pas renseigné alors c'est l'adresse mail du technicien qui est utilisé (./core/auto_mail.php)<br />
- Ticket: Il est possible d'associer un équipement à un ticket en fonction de l'utilisateur cf documentation et nouveaux droits (ticket_new_asset_disp,ticket_asset_disp,ticket_asset,ticket_asset_mandatory,dashboard_col_asset) (./ticket.php ./index.php ./core/ticket.php ./dashboard.php) <br />
- Ticket: Un nouveau droit permet d'afficher les techniciens sans les administrateurs dans la liste des techniciens cf "ticket_tech_admin" (./ticket.php) <br />
- Ticket: Un nouveau droit permet d'afficher les superviseurs dans la liste des techniciens cf "ticket_tech_super" (./ticket.php) <br />
- Ticket: Un nouveau paramètre permet de définir l'état par défaut des tickets (./ticket.php) <br />
- Menu: lors de l'utilisation du menu de gauche réduit avec le thème bleu affichage de l'icône de creation d'un nouveau ticket (./manu.php) <br />
<br /><br />
<u>Bugfix:</u><br />
- Lien nouvel onglet: Dans certains les liens sur nouveaux onglets ne fonctionnaient pas (./*) <br />
- Lien mail: Des anomalies pouvait être rencontré lors de l'utilisation du lien envoyé dans les mails aux utilisateurs (./login.php) <br />
- Fonction sondage: La date de résolution n'était pas automatiquement inséré lors de la cloture du ticket automatique par l'utilisateur (./survey.php) <br />
- Fonction sondage: Pas de mail de sondage à l'utilisateur si l'envoi de mail automatique à la création du ticket était paramétré et que le changement d'état avait lieu à l'ouverture du tickets (./survey.php) <br />
- Fonction sondage: L'utilisateur pouvait valider le sondage sans répondre à la dernière question (./survey.php) <br />
- Fonction sondage: Le mail à destination de l'utilisateur est émit avec l'adresse paramétré dans "Adresse de l'émetteur" dans les paramètres, si le champ est vide alors le mail sera émit avec l'adresse du technicien en charge du ticket (./core/auto_mail.php) <br />
- Fonction sondage: Le mail émit à destination de l'utilisateur gère désormais les serveurs de messagerie avec des certificats non vérifier (./core/messages.php) <br />
- Fonction disponibilité: La condition de prise en compte d'un ticket, ne fonctionnait pas avec le type (./ticket.php) <br />
- Fonction équipement: le WOL sur linux ne fonctionnait plus (./core/wol.php) <br />
- Fonction équipement: Dans l'administration de la liste des modèle lors de la création d'un nouvelle équipement la valeur IP n'était pas conservé. (./admin/list.php) <br />
- Fonction équipement: Augmentation du nombre de caractère disponible sur le champs numéro de prise de 10 à 50. (SQL) <br />
- Ticket: Erreur de changement automatique d'état lors de lors d'un transfert d'un technicien à un groupe de technicien (./survey.php) <br />
- Ticket: Lors de l'utilisation du paramètre "les utilisateurs ne voient que les tickets de leurs service" un technicien ne pouvait pas visualiser un ticket qu'il avait ouvert pour un autre service (./index.php ./dashboard.php ./menu.php) <br />
- Ticket: La liste des demandeurs n'affiche plus les utilisateurs n'ayant ni prénom ni nom.(./index.php ./dashboard.php ./menu.php) <br />
- Ticket: La liste priorité n'était pas trié par numéro.(./ticket.php) <br />
- Ticket: Erreur sur la priorité par défaut lors de la suppression des valeurs par défaut dans la liste des priorité.(./ticket.php) <br />
- Liste des tickets: Lorsque le demandeur ne possédait pas de prénom il n'était pas visible dans le filtre des demandeurs.(./dashboard.php) <br />
- Connecteur IMAP: Suppression de la gestion du protocole POP ne gérant pas les mails non lus (./admin/parameters.php) <br />
- Connecteur IMAP: Affichage des messages d'erreurs si le mode debug est activé (./mail2ticket.php) <br />
- Connecteur IMAP: erreur d'association avec l'utilisateur lorsque deux utilisateurs avait le même mail et l'un était désactivé (./mail2ticket.php) <br />
- Connecteur LDAP: Sur la synchronisation d'agences et de service le caractère Œ n'était pas gérée (./core/ldap_services.php, ./core/ldap_agencies.php) <br />
- Connecteur LDAP: Sur la synchronisation du champ société, gestion de la casse lors des mises à jours  (./ldap.php) <br />
- Connecteur LDAP: La liste des agences pour le déplacement n'était pas trié par ordre alphabétique (./admin/parameters.php) <br />
- Connecteur LDAP: Les unité d'organisations avec accents et espace sont géré (./core/ldap.php) <br />
- Statistique: Dans la répartition du temps par status, le temps total de traitement ne tenait pas compte du filtre par technicien (./stats/tables.php) <br />
- Statistique: Dans le tableau du top 10 demandeur la période sélectionnée n'était pas prise en compte (./stats/tables.php) <br />
- Enregistrement utilisateur: sur le formulaire d'enregistrement autonome des utilisateurs, les champs saisis étaient perdus en cas d'erreur (./register.php) <br />
- Utilisateur: Augmentation du nombre maximal de caractère pour le champ fonction, passage à 100 (SQL) <br />
- Service: Augmentation du nombre maximal de caractères pour le champ nom, passage à 100 (SQL) <br />
- Profil utilisateur: La liste des service ne tenait pas compte de la désactivation des services. (./admin/user.php) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 23/05/2017             <br />
# @Version : 3.1.21      	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Équipements: Les équipements IP qui n'ont pas de modèle IP mais possèdent une adresse IP disposent des boutons d'action IP et du drapeau de dernier ping (./asset.php)<br />
- Équipements: Module de scan réseau permettant de créer et mettre à jour des équipements IP, module en ligne de commande uniquement cf doc (./core/asset_network_scan.php)<br />
- Équipements: Nouveau paramètre dans la fonction équipement pour activer la prise de contrôle web VNC vers un équipement distant cf doc (./asset.php ./ticket.php ./admin/parameters.php)<br />
- Équipements: La fonction d'import des équipements gère les mises à jour dans GestSup si l'adresse MAC est renseigné, l'import peut être automatisé en ligne de commande cf doc (./core/import_asset.php)<br /> 
- Liste des équipements: Amélioration du tri par adresses ip avec INET_ATON (./asset_list.php)<br />
- Ticket: Les champs: technicien, titre et description peuvent remplit de manière obligatoire cf droits: ticket_tech_mandatory, ticket_title_mandatory, ticket_description_mandatory (./ticket.php ./core/ticket.php)<br /> 
- Ticket: Focus par défaut sur le champ demandeur (./ticket.php)<br />
- Mails: Possibilité de paramétrer le texte de fin de mail (Administration > Paramètres généraux), des balises sont disponibles pour le prénom, nom et téléphone du technicien (./core/mail.php ./admin/parameters.php)<br />
- Moniteur: Ajout de nouveaux compteurs (./monitor.php)<br />
- Statistiques: Fusion des graphiques sur les tickets ouverts et fermés et les résolutions (./monitor.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Ticket: Dans certains cas le champ service était affiché alors que le droit était désactivé (./ticket.php) <br />
- Ticket: Erreur de redirection avec les boutons annuler et clôturer ticket avec un profil utilisateur et le paramètre "Les utilisateurs peuvent voir tous les tickets de leur société" (./index.php) <br />
- Mail: le lien vers le ticket n'était pas inséré dans le mail lorsqu'un technicien n'avait pas de téléphone (./core/mail.php) <br />
- Statistiques: Dans certains cas une variable pouvait ne pas être initialisée sur le tableau d'évolutions des résolutions (./stat/line_tickets_activity.php)<br />
- Statistiques: Le filtre par agence ne fonctionnait pas pour certains profil (./stat.php)<br />
- Statistiques: Dans la répartition des temps par status certains états n'avait pas de valeurs (./stat.php)<br />
- Statistiques: Avec la gestion des agences et la restriction par service l'export des tickets en admin était vide (./core/export.php)<br />
- Équipement: Désactivation des interfaces associées à un équipement supprimé (./core/asset.php)<br />
- Ajout utilisateur: Lors de l'ajout d'un utilisateur ayant le profil technicien ou admin la selection d'une vue personnel effaçait les valeurs du formulaire saisie (./admin/user.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 03/05/2017             <br />
# @Version : 3.1.20        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Répertoire logs: nouveau repertoire logs disponible pour certaines fonction du connecteur LDAP (./logs/ldap_agencies.log ./logs/ldap_services.log) <br />
- Droits: le bouton de désactivation du droit admin est supprimé pour le profile administrateur (./admin/profile.php) <br />
- Mise à jour: Optimisation de la detection des droits d'écriture pour l'installation des mises à jour semi-automatique (./admin/update.php) <br />
- Paramètres: Augmentation du nombre de caractères de l'adresse mail d'envoi passage à 200 caractères (SQL) <br />
- Paramètre: Cloisonnement des utilisateurs par service<br />
- Paramètre: Cloisonnement des utilisateurs par agences<br />
- Utilisateur: Gestion de plusieurs service par utilisateur<br />
- Connecteur LDAP: Synchronisation des groupe LDAP de service ou d'agence<br />
- Connecteur IMAP: Gestion multi-bal par service<br />
- Fonction sondage: Nouvelle fonction sondage permettant de demander à l'utilisateur de remplir un questionnaire.(./survey.php ./core/export_surevey.php ./core/auto_mail.php ./core/ticket.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Administration: dans certains cas la liste des utilisateurs n'affichait pas la page 2 (./admin/user.php) <br />
- Liste des tickets: Avec certaines configurations apache les liens vers les tickets ne fonctionnaient pas (./dashboard.php) <br />
- Liste des tickets: lors de la selection des tickets en attente d'attribution dans le menu tous les tickets le menu vos tickets se dépliait (./menu.php) <br />
- Liste des tickets: Amélioration de l'affichage pour les résolution 1280*1024 (./dashboard.php) <br />
- Liste des tickets: Lors de l'utilisation du filtre par date de création ou date de résolution sur la vue activité redirection sur tous les ticket de la date sélectionnée (./core/ticket.php) <br />
- Liste des tickets: Lors de l'affichage de la colonne société avec un compte utilisateur un message d'erreur apparaissait (./dashboard.php) <br />
- Ticket: Lors de l'activation des champs obligatoire sur un ticket, les informations de changement d'état dans les éléments de résolution pouvaient être insérés en double (./core/ticket.php) <br />
- Ticket: Lors de la création d'un nouveau ticket le bouton impression est désactivé car aucune donnée n'est encore enregistrée (./ticket.php) <br />
- Ticket: Avec Internet explorer ajout de commentaire vide lors de l'enregistrement si l'on ajoute pas de texte (./core/ticket.php)<br />
- Ticket: Utilisateur Aucun était en double dans la liste des demandeurs lors de création d'un nouveau ticket dans certains cas (./ticket.php)<br />
- Ticket: Lorsqu'un utilisateur appuyai sur le bouton "clôture ticket" la date de résolution n'était pas inséré sur le ticket (./core/ticket.php)<br />
- Ticket: La liste des techniciens comportait aussi les administrateurs.(./ticket.php)<br />
- Ticket: La valeur par défaut du temps estimé sur un nouveau ticket crée par un utilisateur était d'un mois modification à 5 minutes.(./ticket.php)<br />
- Statistiques: Le chemin de fer de la sections statistique ne fonctionnait pas (./index.php)<br />
- Statistiques tickets: le tableaux top 10 des demandeurs de temps ne tenait pas compte du filtre global de service (./stats/tables.php)<br />
- Statistiques matériels: Le graphique d'évolution des équipements installés et recyclé ne tient plus compte des date d'installation à 0 sur la vue toutes les années  (./stats/line_asset.php)<br />
- Connecteur SMTP: Lors de l'envoi de mail contenant des images dans le corp quand elle n'était pas en base64 un message apparaissaient (./core/mail.php) <br />
- Connecteur IMAP: Lors de la reception d'un mail contenant une signature avec image en provenance d'un client outlook, défaut d'affichage dans les mail émit (./mail2ticket.php) <br />
- Connecteur LDAP: Suppression message d'avertissement T_() lors de l'utilisation de cron  (./core/ldap.php) <br />
- Connecteur LDAP: Gestion des adresses mail avec des apostrophes (./core/ldap.php) <br />
- Connecteur LDAP: Amélioration de la déconnexion serveur (./core/ldap*) <br />
- Rappel de ticket: Suppression de la description pouvant provoquer des difficultés d'affichage avec du code HTML (./event.php) <br />
- Rappel de ticket: L'ajout à la valeur demain ne fonctionnait pas (./event.php) <br />
- Rappel de ticket: L'utilisateur associé au rappel était le technicien du ticket, modification pour que ce soit l'utilisateur connecté.(./event.php) <br />
- Mise à jour automatique: un message d'avertissement apparaissait lors de l'installation de la mise à jour.(./admin/update.php) <br />
- Fonction disponibilité: un message d'erreur pouvait apparaitre avec certaines valeurs nulles.(./plugins/availability/core.php) <br />
- Administration liste modèle équipement: Sur l'ajout d'un nouvelle entrée les valeurs IP et WIFI n'était pas prises en compte.(./admin/list.php) <br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 06/04/2017             <br />
# @Version : 3.1.19        	 	 <br />
#################################<br />
<br />
<u>Notice:</u><br />Augmentation du prés-requis mémoire allouée à PHP, passage de 256MB à 512MB. <br />
<br />
<u>Update:</u><br />
- Liste des tickets: Le bouton ce jour devient activité, montrant tous les tickets ouverts, fermés et sur lequel un élément de résolution à été ajouté aujourd'hui. (./dashboard.php) <br />
- Liste des tickets: Modification de la couleur des numéro de tickets, vert pour les fermés du jour, orange pour les ouverts du jour, bleu pour les tickets sur lesquels un élément de résolution à été ajouté et rouge pour les tickets non lu par le technicien en charge (./dashboard.php) <br />
- Liste des tickets: Sur la vue activité une selection de période est possible (./dashboard.php) <br />
- Liste des tickets: Lorsque le droit d'affichage de l'heure est donnée à la colonne date de création les secondes n'apparaissent plus. (./dashboard.php) <br />
- Ticket: Lors de la création d'un ticket par un technicien, si ce dernier ajoute directement une résolution alors le ticket passe automatiquement à en cours. (./core/ticket.php) <br />
- Ticket: Lors de la création d'un ticket par un utilisateur ne disposant pas de droit de modification de l'état le ticket passai dans l'état en attente de PEC au lieu de en attente d'attribution. (./core/ticket.php) <br />
- Ticket: Lors de l'ajout d'une résolution par un technicien lorsque le ticket est dans l'état en attente de PEC, la modification automatique de statut vers l'état en cours ne crée pas automatiquement de balise de changement d'état dans le fil de résolution. (./core/ticket.php) <br />
- Statistique: L'export des tickets et des matériels tiennent compte du filtre global. (./core/export_asset.php ./core/export_tickets ./stat.php) <br />
- Statistique: Nouvelle courbe de l'évolution des éléments de résolution dans la list tickets. (./ticket_stat.php ./stat/line_tickets_activity.php) <br />
- Statistique: Nouveau tableau reflétant les temps par status sur les tickets. (./ticket_stat.php ./stat/table.php) <br />
- Liste des équipements: une nouvelle colonne localisation est disponible cf droit asset_list_col_location (./asset_list.php) <br />
- Équipements: Nouveau champ localisation disponible sur la fiche équipement voir droit asset_location_disp (./asset.php ./core/asset.php) <br />
- Équipements: Redimensionnement automatique de l'image associée au modèle si cette dernière dépasse 250px.  (./core/ticket.php) <br />
- Équipements: Possibilité d'ajouter plusieurs interfaces IP à un équipement, une gestion des rôles d'interface est disponible dans la gestion des listes. (./images/images/plug.png ./tasset_iface.php ./asset.php ./core/asset.php ./admin/liste.php) <br />
- Équipements: La liste des états est trié par ordre définit dans la liste des états. (./) <br />
- Équipements: Sur la recherche de nouvelle adresse IP disponible, il est possible de configurer les états d'équipement à exclure de la recherche, paramètre "block_ip_search" dans la liste des états des équipements . (./asset_findip.php ./core/asset.php) <br />
- Équipements: Gestion des équipements virtuels, voir la liste des types d'équipement pour l'activer sur un type et ajouter le droit asset_virtualization_disp, certains champs de la fiche équipement seront automatiquement masqué si l'équipement est marqué comme virtuel (./asset.php) <br />
- Équipements: sur la fonction ping des équipements IP une vérification de la bonne formation d'une IPv4 est réalisé avant de déclencher le ping (./core/ping.php) <br />
- Menu: Toutes les section du logiciel dispose d'un favicon(./index.php ./images/favicon*) <br />
- Mise à jour: Ajout d'un contrôle sur les droits d'écriture sur la page des mises à jour (./core/update.php) <br />
- SQL: Optimisation des requêtes et des index (SQL ./*) <br />
- Composants: Mise à jour de PHPMailer en version 5.2.23 (./components/PHPmailer/*) <br />
- Traduction: Amélioration des traductions (./locale/*) <br />
<br /><br />
<u>Bugfix:</u><br />
- Ticket: Lors de la désactivation d'un utilisateur ce dernier n'était pas conservé sur le ticket en cas de ré-enregistrement (./ticket.php) <br />
- Ticket: Lors de la création d'un ticket par un utilisateur, pour le technicien la liste déroulante technicien pouvait contenir deux fois la valeur aucun (./ticket.php) <br />
- Ticket: Lors de la création d'un nouveau ticket avec le navigateur edge un élément de résolution vide apparaissait (./core/ticket.php) <br />
- Liste des tickets: Certaines colonnes n'étaient pas centrées (./dashboard.php) <br />
- Liste des tickets: Sur le filtre des lieux la page 2 ne fonctionnai pas, perte du filtre.(./dashboard.php) <br />
- Liste des tickets: Les filtres de date affichaient la valeur au format SQL "YYYY-MM-DD" au lieu de "DD/MM/YYYY".(./dashboard.php) <br />
- Liste des tickets: Le filtre titre ne fonctionnai plus. (./dashboard.php) <br />
- Liste des tickets: Le champ filtre de la date de création pouvait ne pas être centré sur les grands écrans.(./dashboard.php) <br />
- Liste des équipements: Lors du changement de filtre du status le choix vide (tous) n'était pas conservé. (./asset_list.php) <br />
- Liste des équipements: la selection courante ou la recherche pouvait être perdu à la suite de la suite de certaines modification d'un ticket. (./index.php ./asset_list.php ./asset.php ./core/asset.php) <br />
- Recherche tickets: Lors de la recherche de ticket avec un mot clé égale à un nom d'utilisateur, la recherche pouvait être erronée si deux utilisateurs avec le même nom étaient présent. (./core/searchengine_ticket.php) <br />
- Recherche tickets: Perte de la recherche lors de l'utilisation d'un filtre. (./core/searchengine_ticket.php) <br />
- Recherche équipement: Perte de la recherche lors de l'utilisation d'un filtre. (./core/searchengine_asset.php) <br />
- Équipement import: le fichier modèle d'import CSV avait un défaut d'encodage avec Excel (./downloads/tassets_template.csv)<br />
- Connecteur SMTP: Les messages émit par l'application possédant des images intégré dans les champs description ou résolution n'apparaissaient dans certain clients de messagerie (./core/mail.php) <br />
- Connecteur SMTP: Erreur sur certains serveurs de messagerie lors de l'envoi de message, cf nouveau paramètre connecteur "Vérification SSL" (./core/mail.php ./admin/parameters.php) <br />
- Connecteur LDAP: Lors de la synchronisation LDAP les utilisateurs disposant de login possédant des simple quote bloquai la synchronisation (./core/ldap.php) <br />
- Système: Les valeurs de mémoire alloué à PHP n'était pas vérifié dans certains cas (./core/mail.php) <br />
- Statistiques: Le graphique de répartition des tickets par société pouvait être affiché même lorsqu'il y en avait aucune (./ticket_stat.php) <br />
- Administration: Dans la liste des utilisateurs un défaut d'affichage sur la fiche prouvai apparaitre dans certains cas (./admin/user.php) <br />
- Fonction disponibilité: certaines variable pouvaient être non initialisé en l'absence de tickets (./plugins/availability/core.php) <br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 01/03/2017             <br />
# @Version : 3.1.18        	 	 <br />
#################################<br />

<br />
<u>Notice:</u><br />Pour les utilisateurs du connecteur IMAP, sachez que pour une meilleur gestion de la récupération des réponses dans les mails deux nouvelles lignes sont présentes sur les messages émit. <br />
<br />
<u>Update:</u><br />
- Ticket: Lien hypertexte de type "tel:" sur le numéro de téléphone, pour jonction application IPBX (./ticket.php) <br />
- Ticket: Le nom de la société peut être affichée dans la liste déroulante des demandeurs cf droit "ticket_user_company" (./ticket.php) <br />
- Liste des tickets: Les colonnes catégorie et sous-catégorie peuvent être masquées cf droit dashboard_col_subcat et dashboard_col_category (./dashboard.php)<br />
- Liste des tickets: Une nouvelle colonne avec la société associé à l'utilisateur est disponible cf droit dashboard_col_company (./dashboard.php)<br />
- Liste des tickets: Il est possible d'affiche l'heure de création des ticket en plus de la date cf droit dashboard_col_date_create_hour (./dashboard.php)<br />
- Liste des tickets: Il est possible d'arriver directement sur l'état "Tous les tickets à traiter", via le paramètre personnel "État par défaut" (./login.php ./admin/user.php)<br />
- Liste des sociétés: Ajout du champ "Country" dans la liste des sociétés dans la gestion des listes de la partie administration (SQL) <br />
- Paramètre ticket: Un nouveau paramètre "Numéro d'incrémentation" permet d'initialiser le compteur de ticket à une valeur souhaitée (./admin/parameters.php)<br />
- Connecteur IMAP: Il est possible d'exclure des adresses mails ou des domaines lors l'import de message, cf paramètre du connecteur (./mail2ticket.php /admin/parameters.php) <br />
- Connecteur IMAP: Il est possible de supprimer le mail ou le déplacer dans un dossier une fois convertit en ticket, cf paramètre du connecteur (./mail2ticket.php /admin/parameters.php) <br />
- Mails: L'ordre d'affichage des éléments de résolution dans les mails peut être antéchronologique, cf paramètre des messages (./core/mail.php /admin/parameters.php) <br />

<br /><br />
<u>Bugfix:</u><br />
- Ticket: Lors de la désactivation d'un technicien perte du technicien sur l'édition d'un ticket lui appartenant (./ticket.php) <br />
- Ticket: Problème d'alignement du champ mail sur l'édition d'un utilisateur (./ticket_useradd.php) <br />
- Ticket: Suppression de l'édition de l'utilisateur "Aucun" dans la liste des demandeurs (./ticket.php) <br />
- Liste des tickets: Dans la liste déroulante des techniciens les groupes de techniciens ne comportaient pas le préfixe [G] (./dashboard.php) <br />
- Mail: Le lien intégré vers le ticket n'était pas présent quand un groupe de technicien était en charge du ticket. (./core/mail.php) <br />
- Ajout utilisateur: Lors de l'ajout d'un nouvel utilisateur via "Administration" > "Nouvel Utilisateur" dans le champ téléphone un simple quote apparaissait. (./admin/user.php) <br />
- Connecteur IMAP: Ajout de tag sur les mails envoyés pour une meilleur récupération de la réponse (./core/mail.php ./mail2ticket.php) <br />
- Connecteur SMTP: Erreur d'envoi de message error:14090086:SSL avec certaines ancienne version de PHP (./core/mail.php) <br />
- Connecteur LDAP: Une variable pouvait être non initialisée (./core/ldap.php) <br />
- Statistiques: Le filtre sur le service n'était pas pris en compte sur le graphique de l'évolution dans le temps  (./stats/line_tickets.php ./stats/asset_stat.php) <br />
- W3C: Correction HTML balise font size obsolète<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 10/02/2017             <br />
# @Version : 3.1.17        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Ticket: Les messages de résolution peuvent être masqués pour les utilisateurs cf droit ticket_thread_private ticket_thread_private_button (./core/ticket.php ./core/auto_mail.php ./ticket.php) <br />
- Affichage: Optimisations de l'affichage pour smart phones. (./index.php ./ticket.php)<br />
- Procédure: Ajout de fichiers joint.(./procedure.php)<br />
- Équipements: Possibilité de gérer des équipements non IP uniquement, cf paramètre de la fonction équipements.<br /> 

<br /><br />
<u>Bugfix:</u><br />
- Import d'équipement: Le répertoire d'import est automatiquement crée si il n'existe pas (./admin/parameters.php) <br />
- Ticket: La modification d'état ne fonctionnait pas si un technicien venait d'être attribué (./core/ticket.php) <br />
- Ticket: lors de la création d'un ticket issue du connecteur IMAP, si l'utilisateur est connu de l'application il devient le créateur du ticket (./mail2ticket.php)<br />
- Liste tickets: couleur grise sur la priorité aucune au lieu de la blanche qui n'était pas visible.<br />
- Liste tickets: Erreur sur l'affichage de la catégorie ou sous-catégorie aucune lors de l'affichage avec une autre langue (./dashboard.php).<br />
- Liste tickets: Le titre des vues n'apparaissaient pas (./dashboard.php).<br />
- Impression ticket: La traduction n'était pas prise en compte. (./ticket_print.php).<br />
- Statistique: Erreur d'affichage sur la charge par technicien (./stat_histo.php)<br />
- Statistique: Lors de la selection du service dans le filtre global une valeur erroné restai sélectionner (./ticket_stat.php)<br />
- Mail: Dans le contenu du mail, le tableau n'était pas bien dimensionné par certains clients de messagerie (./core/mail.php)<br />
- Menu: La sélection de l'état est conservé depuis un ticket ou la fiche d'un équipement (./ticket.php ./menu.php) <br />
- Menu: Le menu vos tickets restai ouvert même si l'on était sur la vue tous les tickets (./menu.php)<br />
- Paramètre: Le logo était supprimée lors de la modification d'un paramètre de l'onglet général (./parameters.php)<br />
- Moniteur: La page n'était pas traduite (./parameters.php ./monitor.php)<br />
- Fiche utilisateur: Lors du rattachement d'un utilisateur à un technicien, le nom de l'utilisateur apparaissait toujours dans la liste des utilisateurs  (./admin/user.php)<br />
- LDAP: Lors de la synchronisation LDAP le compte admin GestSup était désactivé si non présent dans l'annuaire AD (./core/ldap.php)<br />
- LDAP: Lors de la synchronisation LDAP erreur lors de la création d'utilisateur possédant des apostrophe dans le nom de leur ville (./core/ldap.php)<br />
- Connecteur IMAP: Défaut de nettoyage html dans certains mails émit par les clients outlook (./mail2ticket.php)<br />
- Administration: Dans la liste des utilisateurs si il y avait 31 utilisateurs alors une page 3 était affiché (./admin/user.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 30/01/2017             <br />
# @Version : 3.1.16        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Logo: Les logo trop grands sont automatiquement redimensionnés sur la page de login et dans le logiciel (./login.php ./index.php).<br />
- Procédure: un nouveau droit nommé "procedure_modify" permet de bloquer l'accès en modification sur les procédures à certains profils (./procedures.php)<br />
- PhpMailer: mise à jour 5.2.22 (./components/phpmailer/*.php)<br />
- Liste des tickets: Les colonnes "Type" et "Criticité" sont activables ou désactivables via les droits "dashboard_col_type" et dashboard_col_criticality (./dashboard.php ./index.php)<br />
- Équipement: Bouton retour liste disponible sur la fiche d'un équipement (./index.php)<br />
- Import de équipements depuis un csv: (./admin/parameters.php ./downloads/tassets_template.csv ./upload/asset ./core/import_assets.php)<br />
- Équipement: changement de nom des matériels pour équipements, car ils peuvent être virtuels (./locale/* ./stat/* ./asset*)<br />

<br /><br />
<u>Bugfix:</u><br />
- Ticket: Les noms des états dans la résolution lors d'un changement d'état n'étaient pas traduit. (./thread.php)<br />
- Ticket: Lors de la planification d'une intervention, l'utilisateur associé était celui connecté et non le technicien rattaché au ticket . (./thread.php)<br />
- Ticket: Le bouton retour liste ne conservait pas le numéro de la page. (./index.php ./dashboard.php ./core/ticket.php)<br />
- Liste tickets: Perte du filtre lors de l'appui sur le bouton "annuler" ou "Enregistrer fermer"  sur le ticket.(./core/ticket.php ./index.php ./dashboard.php)<br />
- Liste tickets: Perte de l'ordre de trie sur retour d'un ticket.(./core/ticket.php ./index.php ./dashboard.php)<br />
- Login: La langue du navigateur était affiché. (./localization.php)<br />
- Planning: la liste déroulante des techniciens ne conservait pas la selection. (./planning.php)<br />
- Planning: la liste déroulante des techniciens pouvait contenir des techniciens désactivés (./planning.php)<br />
- Planning: Variables numérique non initialisé avec PHP 7 (./planning.php)<br />
- Statistiques: Le camembert concernant la répartition de la charge de travail par catégorie restait à 100% sur une catégorie (./stats/pie_load.php)<br />
- Connecteur IMAP: Certaines images dans le contenu du message ne n'affichai pas correctement (./mail2ticket.php)<br />
- Connecteur IMAP: Lors de l'intégration de réponse depuis des clients Outlook certaines informations était en trop (./mail2ticket.php)<br />
- Login: La variable de langue pouvait ne pas être initialisé (./localization.php)<br />
- Écran de supervision: variable non initialisé au premier lancement (./monitor.php)<br />
- Rappel: variable non initialisé dans certains cas(./event.php)<br />
- Système: l'icône de MySQL ou MariaDB pouvait dans certain cas ne pas s'afficher (./images/MySQL.png ./images/MariaDB.png)<br />
- Équipement: sur la fiche équipement perte des données saisie avec le bouton "Enregistrer et Fermer" en cas d'erreur de l'adresse MAC (./core/asset.php)<br />
- Équipement: sur la fiche équipement sur le champ date de fin de garantie il n'était plus possible de le remettre à vide si les années de garantie était spécifié sur le modèle (./asset.php)<br />
- Équipement: Lors de la création d'un nouveau équipement la date d'installation n'apparaissait pas (./asset.php ./core/asset.php)<br />
- Liste équipements: Perte du numéro de la page en cours lors de l'utilisation du bouton "Annuler" ou "Enregistrer et Fermer" sur la fiche d'un équipement (./asset_list.php ./core/asset.php)<br />
- Liste équipements: Le trie par numéro n'affichait pas les flèches de trie (./asset_list.php)<br />
- Liste équipements: Perte du trie lors de l'utilisation de la flèche retour sur une fiche équipement (./core/asset.php)<br />
- Administration des listes: Les listes n'étaient pas triées par ordre alphabétique des noms (./admin/list.php)<br />
- Administration des groupes: variable type pouvait ne pas être initialisée (./admin/group.php)<br />
- Thème: L'arrière plan du thème gris possédait des rayures horizontales. (./template/assets/css/ace-skins.min.css)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 05/01/2017             <br />
# @Version : 3.1.15        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Traduction: L'application est complètement disponible en Anglais, Allemand, Espagnol  (./*).<br />
- Composant: Mise à jour de PHPMailer version 5.2.21(./components/phpmailer/*).<br />
- Système: Affichage de la version des composants dans l'état système(./system.php ./components/*).<br />
- Mail2ticket: Diminution de l'affichage des informations d'import lorsque le mode debug n'est pas activé(./mail2ticket.php).<br />
- Ticket: Les changements d'état sont enregistrés dans la résolution (./core/ticket.php ./thread.php)<br />
- Équipement: Il est possible de planifier un ping pour tous les équipements réseau afin de remonter les équipements obsolète cf FAQ (./asset.php ./core/export_asset.php ./core/ping.php)<br />
- SGBDR: Compatibilité avec MariaDB, port personnalisable à l'installation de la version 3.1.15 (./images/mariadb.png ./images/mysql.png ./install/index.php ./system.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Menu des équipements: les compteurs par état ne fonctionnai pas. (./menu.php)<br />
- WOL: Le réveil des équipements par le réseau ne fonctionnai pas avec un serveur Windows quand le répertoire de l'application possédait un espace. (./core/wol.php)<br />
- Statistiques: Dans l'onglet équipement le comptage des équipements recyclés n'étaient pas correcte. (./stat/line_assets.php)<br />
- Statistiques: Sur le filtre la liste déroulante concernant les années n'était pas trié. (./asset_stat.php ./ticket_stat.php)<br />
- Liste des tickets: lors de la modification en lot vers l'état résolu, la date de résolution n'était pas renseignée sur le ticket (./dashboard.php)<br />
- Connecteur LDAP: Pas de création d'utilisateur GestSup si la valeur de l'identifiant retourné est vide (./core/ldap.php)<br />
- Planning: Erreur de numérotation de certains jours de la semaine en janvier 2017 (./planning.php)<br />
- Ticket: Erreur lors de la planification d'une intervention la date de début était à 0 (./event.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 21/12/2016             <br />
# @Version : 3.1.14        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Interface utilisateur uniquement: Langue Anglaise disponible, detection automatique de la langue sur la page de login, puis personnalisation dans les paramètres utilisateur dans le logiciel (./admin/user.php ).<br />
- Nouveau ticket: Optimisation des requêtes sur l'ouverture d'un nouveau ticket (./thread.php ).<br />
- Paramètres: Message d'avertissement sur détection du dossier d'installation de l'application (./admin/parameters.php ).<br />
- Groupe d'utilisateurs: Dans l'édition d'un groupe, la liste des membres à ajouter ne contiens que les utilisateurs ayant le profil technicien ou utilisateur. (./admin/group.php ).<br />
- Mail: Le groupe de technicien en charge du ticket est automatiquement en copie. (./admin/group.php ).<br />
- Liste des tickets: Les techniciens associés à des groupes peuvent voir directement les tickets des groupes les concernant, voir le droit "side_your_tech_group". (./menu.php ./dashboard.php )<br />
- Liste des tickets: Via la liste déroulante il est possible de sélectionner tous les tickets de la selection courante. (./dashboard.php )<br />
- Mail2ticket: Mise à jour du composant PHPImap. (./components/phpimap)<br />

<br /><br />
<u>Bugfix:</u><br />
- Liste des tickets: Avec l'option voir les tickets de ma société, les filtres pouvaient affichés d'autres tickets. (./dashboard.php)<br />
- Liste des tickets: Le trie sur le lieu ne fonctionnai pas si l'option gestion des lieux était activée. (./dashboard.php)<br />
- Fiche utilisateur: Si le droit "user_profil_company" était désactivé pour les utilisateurs la valeur de la company était ré-initialisée sur la validation de la fiche par l'utilisateur. (./admin/user.php)<br />
- Session: Gestion de plusieurs applications GestSup sur un même serveur ayant le même URL d'accès, cookie unique par installation de GestSup. (./index.php)<br />
- Statistiques: Le graphique de la répartition des tickets par service pouvait être erroné si à l'ouverture du ticket il était dans l'état résolu. (./core/ticket.php)<br />
- Nouveau ticket: L'utilisateur par défaut est aucun. (./ticket.php)<br />
- Affichage: Désactivation du mode de compatibilité Internet Explorer . (./index.php)<br />
- Synchronisation LDAP: affichage systématique de la création et la mise à jour de société vide (./core/ldap.php)<br />
- Synchronisation LDAP: Gestion dynamique du chemin vers connect.php lors de la synchronisation automatique via cron (./core/ldap.php)<br />
- Ticket: Sur les popup d'ajout de rappel ou de planification les mois avec accents avait parfois des défaut d'encodage (./event.php)<br />
- Ticket: Le champ lieu est désormais vérrouillable en modification pour les utilisateurs cf droit ticket_place (./ticket.php)<br />
- Ticket: Le type du ticket pouvait dans certains cas être ré-initialisé par l'utilisateur (./ticket.php)<br />
- Ticket: Pas d'affichage des équipements associés si l'utilisateur sélectionné est "Aucun" (./ticket.php)<br />
- Ticket: Dans la partie pièce jointe lorsque l'extension est inconnue alors une icône par défaut est affiché (./attachement.php ./images/icon_file/default.png)<br />
- Ticket: Intégration de l'icône des fichiers de type JPEG pour les pièces jointes. (./images/icon_file/jpeg.png)<br />
- Mail: Les groupes de techniciens en charge ou d'utilisateurs demandeur n'étaient pas affichés. (./preview_mail.php ./mail.php) <br /> 
- Mail: Les transferts de ticket avec les groupes de techniciens n'étaient pas affichés. (./mail.php) <br /> 
- Mail2ticket: Un répertoire était systématiquement crée dans /upload même si le mail ne contenait pas de pièce jointe. (./mail2ticket.php) <br /> 
- Mail2ticket: Seule la dernière pièce jointe du mail était intégrée. (./mail2ticket.php) <br /> 
- Mail2ticket: Les mails reçus au format Plain/Text n'étaient pas intégrés. (./mail2ticket.php) <br /> 
- Mail2ticket: Les images contenus dans les mails ne s'affichait pas à l'intérieur de la description du tickets. (./mail2ticket.php) <br /> 
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 05/12/2016             <br />
# @Version : 3.1.13        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Favicons: Mise à jour des favicons (./index.php ./images/favicon_ticket.png ./images/favicon_asset.png ./images/windows.png)<br />
- Liste de tickets: Les listes déroulantes des filtres sont conditionnées à leurs résultats (./dashboard.php)<br />
- Liste de tickets: Les liens vers les différentes pages sont limités en nombre (./dashboard.php)<br />
- Liste de équipements: Les liens vers les différentes pages sont limités en nombre (./asset_list.php)<br />
- Liste de équipements: La recherche des équipements par utilisateur associé est disponible (./asset_list.php ./core/searchengine_asset.php.php)<br />
- Équipements: Lors de l'ajout en lot de équipements la date de fin de garantie est automatiquement renseignée si elle est définie sur le modèle (./asset_stock.php)<br />
- Liste des utilisateurs: Une nouvelle colonne avec le nom de la société apparait quand la gestion des utilisateurs avancées est activé (./admin/user.php)<br />
- Mail: Intégration dans les mails du lieu quand celui-ci est renseigné (./core/mail.php )<br />

<br /><br />
<u>Bugfix:</u><br />
- Ticket: Dans la liste déroulante des demandeurs, des utilisateurs pouvaient apparaître en double dans certains cas.(./ticket.php)<br />
- Ticket: Dans la résolution lors d'un transfert d'un groupe de technicien à un autre groupe de technicien un défaut d'affichage apparaissait.(./thread.php)<br />
- Ticket: Lors de la selection d'une catégorie, plus de message de warning si aucune sous-catégorie n'est renseignée.(./ticket.php)<br />
- Équipement: Erreur PHP lors de la validation d'un équipement possédant une date de fin de garantie.(./asset.php)<br />
- Modèle de ticket: Lors de l'utilisation d'un modèle de ticket, la fenêtre de selection revenait sur chaque modification de champ avant l'enregistrement. (./ticket_template.php)<br />
- Liste des tickets: Si paramètre "voir tous les tickets de ma société" été activé le lien vers la page 2 ne fonctionnai pas.(./dashboard.php)<br />
- Liste des équipements: la liste déroulante du filtre pour les utilisateurs, ne contenait pas les utilisateurs désactivés (./asset_list.php)<br />
- Liste des équipements: Le compteur d'équipements sous garantie dans le menu pouvait afficher une valeur erronée (./menu.php)<br />
- Liste des lieux: Suppression de la possibilité de supprimer la ligne "Aucune", ce qui crée une perte de visibilité des nouveaux tickets du connecteur IMAP. (./admin/list.php ./core/ticket.php ./mail2ticket.php)<br />
- Liste des utilisateurs: Suppression de l'icône de la possibilité de supprimer les utilisateurs, désactivation uniquement pour éviter les tickets et équipements orphelins. (./admin/user.php)<br />
- Liste des utilisateurs: la recherche d'utilisateurs désactivés ne fonctionnait pas. (./admin/user.php)<br />
- Statistiques: La liste des statistique des tickets par tableau dépassait du cadre. (./stat/tables.php)<br />
- Impression ticket: Si l'on imprime deux fois de suite un ticket, un message d'erreur de droit apparaissait. (./ticket_print.php ./ticket.php)<br />
- Groupe d'utilisateurs: Le lien vers la fiche utilisateur depuis la liste des membre d'un groupe ne fonctionnait pas. (./admin/group.php)<br />
- Export des tickets: Le fichier exporté n'incluait pas le nom des groupes d'utilisateurs ou de techniciens. (./core/export_tickets.php)<br />
- Variables non initialisées: (./assets_stock.php ./register.php ./admin/parameters.php ./modalbox.php ./planning.php)<br />
- Menu de gauche: Le lien vers les vues pointai vers une page inexistante. (./menu.php)<br />
- Statistiques: Les tableaux de bas de page ne tenait pas compte des filtres. (./stats/tables.php)<br />
- Statistiques: Le camembert de répartition par société ne tien plus compte des tickets ou la société n'est pas renseignée. (./stats/pie_company.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 17/11/2016             <br />
# @Version : 3.1.12        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Administration des listes: Ajout d'une fenêtre de confirmation lors de la demande de suppression d'une ligne (./admin/list.php)<br />
- Paramètre connecteur LDAP: Augmentation de la taille du champ "emplacement des utilisateurs" afin que celui ci ne soit pas tronqué (./admin/parameters.php)<br />
- Identifiant: Augmentation de la taille maximum du champ login à 200 caractères (SQL)<br />
- Équipement: Nouveau champ date de fin de garantie sur l'équipement (./assets.php ./core/assets.php ./core/export_tickets.php ./admin/parameters.php)<br />
- Équipement: liste des équipements en fin de garantie (./assets.php ./core/assets.php ./core/export_tickets.php ./admin/parameters.php)<br />
- Équipement: champ date de fin de garantie automatiquement remplit lorsque le model dispose d'une garantie  (./assets.php)<br />
- Équipement: Les dates affichées sur les fiches des équipements sont au format dd/mm/YYYY (./assets.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Modèle de tickets: Les utilisateurs et utilisateurs avec pouvoir ayant les droits d'utiliser les modèles de tickets avait un message d'erreur de droit (./index.php)<br />
- Équipement: Erreur d'affichage dans la date de fin de garantie inversion mois et jour (./asset.php)<br />
- Équipement: Sur la création de nouveaux équipements, la liste des installateurs était peuplée même par les techniciens désactivés. (./asset.php)<br />
- Équipement: La recherche d'équipement sur 2 ou 3 mots clés ne fonctionnait pas. (./core/searchengine_asset.php)<br />
- Ticket: Pour les utilisateurs lorsque le droit de modification du type était donné sur l'ouverture des ticket, la modification était possible en éditant le ticket (./ticket.php)<br />
- Ticket: Erreur d'affichage de la liste des criticités lors de changement de criticité valeur en double dans la liste  (./ticket.php)<br />
- Ticket: Lors de l'ajout d'une pièce jointe pour un utilisateur, si il n'avait pas remplit la résolution elle n'était plus modifiable  (./attachement.php)<br />
- Mail: Le commentaire n'était pas affiché' dans les mails automatique à destination des techniciens lorsque les utilisateurs ajoute un commentaire (./core/auto_mails.php)<br />
- Fiche utilisateur: Page blanche dans certains cas lors de l'ajout d'une fonction à un utilisateur (./admin/user.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 21/10/2016             <br />
# @Version : 3.1.11        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Augmentation de la longueur maximale du nom des sociétés à 100 caractères (SQL)<br />

<br /><br />
<u>Bugfix:</u><br />
- Liste des tickets: la colonne date résolution n'était pas affiché, quand le droit était positionné et que la vue "tous les états" était sélectionnée (./dashboard.php)<br />
- Liste des tickets: Certains tickets n'était pas visible (SQL ./admin/user.php)<br />
- Liste des tickets: Les utilisateurs et utilisateurs avec pouvoir utilisant le paramètre "voir tous les tickets de ma société", ont leur liste d'utilisateurs limité à leur compagnie (./dashboard.php)<br />
- Modèle de tickets: Les utilisateurs et utilisateurs avec pouvoir ayant les droits d'utiliser les modèles de tickets avait un message d'erreur de droit (./ticket_template.php)<br />
- Menu: Erreur d'affichage du menu sur le profil administrateur, lors de l'activation du paramètre de visualisation de tous les tickets de sa société (./menu.php ./dashboard.php ./index.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 08/10/2016             <br />
# @Version : 3.1.10        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Les utilisateurs peuvent voir tous les tickets de leur société, paramètres et droits à configurer (./menu.php ./dashboard.php ./admin/user.php /admin/parameters.php) <br />
- Une limite de ticket pour une durée à partir d'une date est possible, paramétrage dans la liste des sociétés + dans paramètres (./index.php ./core/ticket.php) <br />
- Ticket: le équipement associés à l'utilisateur sont les équipements IP (./ticket.php) <br />
- LDAP: augmentation à 500 caractères sur le champ d'emplacement des utilisateurs (SQL) <br />
- Mises à jour: l'installation des mises à jour peuvent être complètement automatisées, consulter la documentation (./admin/update.php ./core/install_update.php) <br />

<br /><br />
<u>Bugfix:</u><br />
- Équipement: redirection sur la page des tickets lors de la recherche de nouvel adresse IP disponible. (./core/asset.php ./asset.php ./asset_findip* ./modalbox.php)<br />
- Équipement: lors de la fermeture d'une fiche équipement le retour sur la liste avec conservation du filtre ne fonctionnai plus. (./core/asset.php)<br />
- Liste des équipements: la page ne revenait pas à zéro sur le changement de filtre. (./asset_list.php)<br />
- Rappel: l'affichage du rappel était systématique. (./core/asset.php ./asset.php ./asset_findip* ./modalbox.php)<br />
- Disponibilité: Dans les calcul de temps si le cumul des minutes était égale à 60 l'heure n'était pas incrémenté. (./plugins/availability/core.php)<br />
- Utilisateur: La limite de 20 caractères pour les identifiants des utilisateurs à été passée à 100 caractères. (SQL)<br />
- Icône Fichier: Intégration de l'icône vsdx mise a jour des logos office. (./images/icon_file/*.png)<br />
- Planning: deux variables n'était pas initialisés. (./planning.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 25/07/2016             <br />
# @Version : 3.1.9        	 	 <br />
#################################<br />
 
<br />
<u>Update:</u><br />
- LDAP: Synchronisation multi OU, syntaxe OU=TEST;OU=TEST2 (./core/ldap.php ./admin/parameters.php)<br />
- Tickets: Suppression des trois saut de ligne sur les champs description et résolution (./ticket.php ./threads.php)<br />
- Gestion de limite de ticket par utilisateur: Bloque la création de ticket pour les utilisateurs si le seuil est dépassé dans un laps de temps. (./index.php ./admin/user.php ./core/ticket.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Enregistrement utilisateurs: Ajout de contrôle sur les champs identifiants et adresse mail.<br />
- Barre utilisateurs: le bouton ce jour ne fonctionnait plus (./dashboard.php)<br />
- Ticket: La modification de date de création de ticket ne fonctionnai plus (./ticket.php)<br />
- Ticket: affichage de dateres lors de l'enregistrement (./core/ticket.php)<br />
- Ticket: Plus de génération de 3 retours a la ligne dans le champs description (./ticket.php)<br />
- Ticket: le bouton rappel ne fonctionnait plus (./ticket.php)<br />
- Ticket: les boites modal restaient affichées un fois fermer lors du rechargement de la page (./modalbox.php)<br />
- LDAP: Erreur lors de la synchronisation des société le champ service était mise a jour(./core/ldap.php)<br />
- Mail: Les adresses mail de plus de 50 caractères étaient tronqués.(SQL)<br />
- liste des équipements: conservation du filtre de recherche lorsque que l'on va sur la fiche de l'équipement puis retour à la liste(./asset_list.php)<br />
- liste des équipements: Pas de sélection des modèles associé au type dans la liste des équipements(./asset_list.php)<br />
- Statistiques: La liste des années n'était pas exhaustive pour les équipement(./asset_stats.php, ./tickets_stats.php, ./stats/*)<br />
- Statistiques: Lors de la sélection du mois de toutes les années un pourcentage apparaissait(./asset_stats.php, ./tickets_stats.php, ./stats/*)<br />
- Statistiques: Dans la liste des années une ligne zéro pouvait apparaitre(./asset_stats.php, ./tickets_stats.php, ./stats/*)<br />
- Fiche utilisateur: Les apostrophes du champ fonction n'était pas pris en compte(./admin/user.php)<br />
- IMAP: Intégration de la gestion des pièce jointe dans les tickets(./components/PhpImap/*)<br />
- IMAP: correction des défaut d'encodage sur les mails rédigé depuis les clients outlook ou OWA (./mail2ticket.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 31/05/2016             <br />
# @Version : 3.1.8        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Système: vérification de l'activation de l'extension php_ftp pour les mises à jour. (system.php)<br />
- Ticket: Affichage des dates au format JJ/MM/AAAA HH:MM:SS.(ticket.php ./core/ticket.php)<br />

<br /><br />
<u>Bugfix:</u><br />
- Barre utilisateurs: le bouton ce jour ne fonctionnait plus (./dashboard.php)<br />
- Liste des tickets: le filtre par date de création ne fonctionnai plus (./dashboard.php)<br />
- Liste des tickets: lors d'une recherche sur le méta état a traiter recherche uniquement dans les tickets ouverts (./core/searchengine_ticket.php)<br />
- Liste des tickets: la sélection d'un page n'est plus conservé en cas de changement de technicien (./dashboard.php)<br />
- Liste des tickets: le trie sur les titres ne fonctionnait pas (./dashboard.php)<br />
- Liste des tickets: le filtre concernant la priorité ne fonctionnait pas (./dashboard.php)<br />
- Ticket: Les titres possédant des guillemet sont coupés a l'enregistrement (./ticket.php)<br />
- Ticket: Suppression de la redirection sur la liste des tickets si l'on clic sur le bouton enregistrer fermer, avec champs obligatoire (./core/ticket.php)<br />
- Ticket: lors de la connexion d'un utilisateur via le lien d'un mail, la redirection aboutissait sur un erreur de droit(./index.php)<br />
- Ticket: lors de la sélection de la criticité un rechargement de la page été réalisé alors qu'il n'était pas nécessaire sans le module disponibilité (./ticket.php)<br />
- Ticket: Lorsque qu'un utilisateur avec pouvoir crée un ticket contenant des retours chariot et des guillemets deux caractères spéciaux étaient affichés (./ticket.php)<br />
- Ticket: Incompatibilité avec le navigateur edge sur les nouveaux tickets (./ticket.php)<br />
- Ticket: La touche retour ne fonctionnait pas lorsque l'on était sur une vue (./menu.php)<br />
- Ticket: l'ajout de nom ou de prénom possédant des apostrophes ne fonctionnait pas depuis le menu du ticket (./ticket_useradd.php)<br />
- Ticket: Ajout du bouton ajouter sur nouveau ticket (./thread.php)<br />
- Liste des équipements: lors d'une recherche par nom netbios, le clic sur la page 2 perdais la recherche(./asset_list.php)<br />
- Planning: la page n'était pas entièrement lors que certain événements était crées(./planning.php)<br />
- Équipement: Lors de l'ajout d'un lot dans les stocks, le modèle n'était pas conservé dans la liste déroulante(./asset_stock.php)<br />
- Équipement: incompatibilité avec le navigateur edge sur les nouveaux équipements(./asset.php)<br />
- Statistiques: Sur le filtre les administrateur apparaissent aussi dans la liste des techniciens(./ticket_stat.php ./asset_stat.php)<br />
- Utilisateurs: Dans le rattachement d'utilisateurs a un technicien, la liste n'était pas affiché en entière.  (./admin/users.php)<br />
- Mise a jour: Conformité avec la rfc959 (./admin/update.php)<br />
- Connecteur IMAP: erreur lorsque le sujet contient certains des chiffres (./mail2ticket.php)<br />
- Paramètre: Complément d'information sur le connecteur IMAP et gmail (./admin/parameters.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 10/03/2016             <br />
# @Version : 3.1.7        	 	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Connecteur IMAP: lors de la réponse a une notification alors mise a jour du ticket existant. (bêta)<br />
- Ticket: Ajout d'un bouton retour pour revenir a la liste. (./index.php ./dashboard.php)<br />
- Paramètres: Ajout d'exemples dans les informations sur les paramètres du connecteur SMTP. (./admin/parameters.php )<br />

<br /><br />
<u>Bugfix:</u><br />
- Global: Incompatibilité avec les versions récente de MySQL, comme WAMP 3. (./index.php)<br />
- Connecteur IMAP: récupération du corps du message erronée et problème d'encodage de caractères. (./mail2ticket.php)<br />
- Mail: Adresse de réponse est celle définit dans l'émetteur dans les paramètres. (./core/mail.php)<br />
- Liste ticket: Le warning était affiché si la date de résolution et obligatoire et si l'état est a résolu ou rejeté. (./dashboard.php)<br />
- Liste ticket: Le filtre sur les états ne fonctionnai plus. (./dashboard.php)<br />
- Liste ticket: Le filtre sur les dates ne fonctionnai plus. (./dashboard.php)<br />
- Liste ticket: lorsqu'un filtre est activé la valeur sélectionné est conservée dans la barre des filtres. (./dashboard.php)<br />
- Statistiques: Erreur de nommage dans le nombre affiché sur les camemberts. (./stat/pie_assets_type.php ./stat/pie_assets_service.php)<br />
- Statistiques: Filtre tous les types en double si la gestion des type de ticket est activé. (./asset_stat.php)<br />
- Statistiques: Par défaut les statistique sont pour le mois en cours et plus l'année. (./stat.php.php)<br />
- Ticket: Erreur d'enregistrement de thread sur l'enregistrement de ticket avec champ obligatoire non renseignés. (./core/ticket.php)<br />
- Ticket: Message d'erreur sur la suppression de pièce jointe avec le profil poweruser. (./attachment.php)<br />
- Mail2ticket: erreur lors de la récupération du nom de l'utilisateur associé a l'adresse mail. (./mail2ticket.php)<br />
- Barre technicien: le lien vers les tickets a traiter est rediriger vers le méta état a traiter, si l'option est activée. (./index.php)<br />
- Gestion des Lieux: Attribution du lieu par défaut (aucun) si aucune donnée n'est renseignée. (./core/ticket.php)<br />
- Mise a jour: Correction du lien vers le nouveau site pour les mises a jours manuelles. (./admin/update.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 13/02/2016             <br />
# @Version : 3.1.6           	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Statistiques: Disponibilité des statistiques des équipements.(./menu.php ./stat.php ./ticket_stat.php ./asset_stat.php ./stat_line.php ./stat/pie_assets_service.php ./stat/pie_assets_type.php).<br />
- Recherche Tickets: gestion de 5 mots clés au lieu de 3.(./core/searchengine_ticket.php).<br />
- Export: Les équipements sont exportable depuis la page statistique.(./core/export_*.php).<br />
- Liste des tickets: Affiche un warning si la date de résolution estimé n'est pas renseigné et si elle est obligatoire.(./dashboard.php).<br />
- Tickets: Sur un nouveau ticket perte de la résolution si champ obligatoire non renseigné.(./ticket.php ./thread.php ./core/ticket.php).<br />
- Tickets: Sur un nouveau ticket caractères spéciaux dans le champs titre si champ obligatoire non renseigné.(./ticket.php ./thread.php ./core/ticket.php).<br />
<br /><br />
<u>Bugfix:</u><br />
- Planning: La lors de l'arrivée sur la page on arrivai sur la semaine -1. (./planning.php).<br />
- Équipements: Lors de la modification vers l'état recycler la date n'était pas renseigné. (./asset.php ./core/asset.php).<br />
- Équipements: Lors de la modification d'état le contrôle d'IP dupliqué n'était pas réalisé. ( ./core/asset.php).<br />
- Recherche tickets: Blocage sur la recherche multi-critère avec de grosses bases de données. ( ./core/searchengine_ticket.php).<br />
- Administration: Faute orthographe. ( ./admin/user.php).<br />
- Statistiques: Sur le camembert de la répartition des tickets par techniciens, les tickets été groupés par prénoms. ( ./stat/pie_tickets_tech.php).<br />
- Liste tickets: Les cases a cochés ne fonctionnaient plus. ( ./dashboard.php).<br />
- Tickets: Sur l'édition d'une sous-catégorie pas de retour a la ligne sur la liste déroulante des catégorie. ( ./ticket_catadd.php).<br />
- Tickets: Variable non définie. ( ./ticket.php).<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 23/01/2016             <br />
# @Version : 3.1.5           	 <br />
#################################<br />

<br />
<u>Update:</u><br />
- Ticket: changement automatique de l'état de "Attente de PEC" à "En cours", si le technicien ajoute une résolution.(./core/ticket.php).<br />
<br /><br />
<u>Bugfix:</u><br />
- Paramètres: Vérification du paramètre de condition de prise en compte d'un ticket dans la fonction disponibilité(./plugins/../parameters.php).<br />
- favicon: Correction URL(./register.php print.php monitor.php ./install/index.php ./favicon.ico).<br />
- Procédure: Initialisation de variables(./procedure.php).<br />
- Planning: Jeudi date incorrecte(./planning.php).<br />
- Installation: Les bases de données avec tirets sont autorisés(./install/index.php).<br />
- Liste des tickets: La liste de sélection des actions était parfois trop basse.(./dashboard.php).<br />
- Liste des tickets: lors du clic sur la case numéro hors chiffre la redirection vers le ticket ne fonctionnai pas.(./dashboard.php).<br />
- Ticket: Sur l'édition des utilisateurs le champ téléphone n'était pas à la ligne.(./ticket_useradd.php).<br />
- Ticket: Le droit d'affichage uniquement ne fonctionnait pas sur les listes déroulantes.(./ticket.php).<br />
- Ticket: Le équipement associé a l'utilisateur n'affiche que ceux qui sont actifs.(./ticket.php).<br />
- Ticket: Conservation du technicien associé si celui si est désactivé.(./ticket.php).<br />
- Liste des Équipements: Lors de l'accès en visualisation par un utilisateur avec internet explorer le lien revenait sur la liste des tickets.(./asset_list.php).<br />
- Droits: Les utilisateurs avait le droit par défaut de supprimer leurs tickets.(SQL).<br />
- Disponibilité: Variables non initialisés.(./plugins/availability/core.php ./plugins/availability/median.php).<br />
- Mail: Envoi de mail était impossible sur hébergement 1&1, nouveau paramètre connecteur SMTP.(./admin/parameters.php ./core/mail.php ./core/message.php).<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 16/01/2016             <br />
# @Version : 3.1.4          	 <br />
#################################<br />
<br />

<u>Update:</u><br />
- Ticket: Affiche le nom du dernier équipement associé a l'utilisateur(./ticket.php)<br />
- Export: Affiche le nom de la société(./core/export.php)<br />
- Mail: Envoi de mail automatique a l'utilisateur lors de l'ajout d'un élément de résolution par le technicien cf paramètres(./admin/parameters.php ./ticket.php ./core/auto_mail.php ./core/mail.php ./core/message.php)<br />
- Mail: Envoi de mail automatique au technicien lors de l'ajout d'un élément de résolution par l'utilisateur cf paramètres(./admin/parameters.php ./ticket.php ./core/auto_mail.php ./core/mail.php ./core/message.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Ticket: Trouve le dernier équipement associé à l'utilisateur(./ticket.php).<br />
- Procédure: Variable non initialisé(./procedure.php).<br />
- Équipement: Le ping ne fonctionnait pas lorsqu'il n'y avait qu'une seule IP WLAN(./asset.php ./core/asset.php ./core/ping.php).<br />
- Équipement: La détection d'adresse ip utilisé ne se base plus que sur les équipement actif(./core/asset.php  ./asset_findip2.php).<br />
- Équipement: La recherche des ip disponible ne tenait pas compte des adresse IP WLAN(./asset_findip.php ./asset_findip2.php).<br />
- LDAP: Les utilisateurs ayant des caractères spéciaux dans leurs login étaient désactivés(./core/ldap.php).<br />
- Stat: Deux variables étaient non initialisés.(./stat.php).<br />
- Stat: Les statistiques ne s'affichaient pas lorsque la fonction utilisateur avancé était sélectionné.(./stats.php).<br />
- Export CSV: Les accents dans le fichier csv n'était pas gérés.(./core/export.php).<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 10/01/2016             <br />
# @Version : 3.1.3         	 <br />
#################################<br />
<br />

<u>Update:</u><br />
- Ticket: Si un équipement est associé a un utilisateur il apparait alors sur le ticket a coté de son nom (./ticket.php)<br />
- Liste équipement: Affichage de la seconde ip si elle existe (./asset_list.php)<br />
- Droits: Ajout de nouveaux droits pour avoir une visualisation de la liste des équipements (./menu.php ./asset_list.php ./index.php)<br />
- Statistique: Nouveau filtre par service disponible (./stat.php ./stat/*)<br />
- Mise a jour: suppression de l'avertissement de temps (./core/install_update.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Ticket: le droit d'interdiction de modification des date ne fonctionnait pas (./ticket.php).<br />
- Paramètres: Les fonctions disparaissent lors de l'activation du module de disponibilité (./plugins/.../parameters.php).<br />
- Liste ticket: La liste des actions pour une sélection, n'intégrait pas les nouveaux états (./dashboard.php).<br />
- Liste ticket: Le trie sur la colonne priorité ne fonctionnai plus (./dashboard.php).<br />
- Liste Équipement: Les filtre sur IP ne fonctionnait plus sur page 2 (./asset_list.php).<br />
- Liste Équipement: Pas de sélection d'un second filtre (./asset_list.php).<br />
- Recherche équipement: la recherche sur l'IP WLAN ne fonctionnait pas (./core/searchengine_asset.php).<br />
- Équipement: La recherche d'adresse IP disponible ne tenait pas compte des IP WLAN (./asset_findip.php ./asset_findip2.php).<br />
- Équipement: L'adresse IP des équipement sans modèle n'apparaissait pas (./asset.php).<br />
- Équipement: La corrélation entre le type le modèle le fabriquant et le modèle n'était pas toujours fonctionnel (./asset.php).<br />
- Équipement: redirection vers les équipement installé sur ajout nouveau équipement (./core/asset.php).<br />
- Système: plus de vérification de la version l'extension php_mysql(./system.php).<br />
- Paramètres: Défaut de nommage (./admin/parameters.php).<br />
- Utilisateur: Dans la liste le lien depuis la colonne profil ne fonctionnait pas (./admin/user.php).<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 05/01/2016             <br />
# @Version : 3.1.2          	 <br />
#################################<br />
<br />

<u>Update:</u><br />
- Équipement: Gestion des équipements IP(bêta).<br />
<br /><br />
<u>Bugfix:</u><br />
- Ticket: lors de l'impression les caractères spéciaux apparaissent codés (./ticket_print.php)<br />
- Mail: Lors des événements d'attribution ou de transfert il y avait deux points en trop (./core/mail.php)<br />
- Liste des tickets: Sur une recherche si le rafraichissement automatique est lancé alors la recherche était ré-initialisée (./index.php)<br />
- Installation: création de la base de données uniquement si elle n'existe pas déjà (./install/index.php)<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 10/12/2015             <br />
# @Version : 3.1.1          	 <br />
#################################<br />
<br />
<u>Update:</u><br />
- Liste des tickets: Lorsque l'application est paramétrée pour l'ordre par numéro de ticket alors l'ordre est décroissant (./dashboard.php)<br />
- Liste des tickets: Sur le bouton "ce jour" on affiche les tickets ouverts et fermés du jours pour tous les techniciens(./dashboard.php ./index.php)<br />
- Liste des tickets: Colonne date personnalisable dans la gestion des droits(./admin/parameters.php ./dashboard.php)<br />
- Sécurité: sécurisation de l'impression des tickets par token. (./ticket.php ./ticket_print.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Connecteur IMAP: certains messages apparaissent codés (./mail2ticket.php)<br />
- Https: gestion de la détection https (./index.php ./core/ticket.php ./asset.php)<br />
- Groupes: erreur lors de l'enregistrement d'un groupe si l'on ajoute pas de nouveaux membres(./admin/group.php)<br />
- Ticket: sur les nouveaux ticket la selection du demandeurs recharge la page en bas(./core/ticket.php)<br />
- Ticket: La sélection d'un groupe de demandeur n'etait pas conservé (./ticket.php)<br />
- Ticket: Dans la liste des autres tickets de l'utilisateur certain n'étaient pas affichés (./ticket.php)<br />
- Liste ticket: Liste vide si les lieux n'était pas renseignés (SQL)<br />
- Mail automatique: Les mails automatique ne partaient plus (./core/auto_mail.php)<br />
- Menu: le lien vers les nouveaux tickets ne fonctionnaient plus (./menu.php)<br />
- Mise a jour: page blanche sur lancement de l'installation (./core/install_update.php)<br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 24/11/2015             <br />
# @Version : 3.1.0          	 <br />
#################################<br />
<br />
<u>Notice:</u><br />
- Le fichier ./connect.php doit être modifié manuellement avec vos paramètres de base de données.<br />
<br /><br />
<u>Update:</u><br />
- Base de donnée: Nouveaux mode de connexion en PDO remplaçant le mysql_query obsolète, modification des 1167 requêtes de l'application<br />
- LDAP: La synchronisation des annuaires LDAP peut être automatiser en appelant la page: gestsup_3.0.12/core/ldap.php (Linux: echo "0 0 * * * php /var/www/site/ldap.php" | crontab)<br />
- Mail: Intégration de la dernière version de PHPmailer 5.2.13<br />
- Liste des tickets: Lorsque la date affiché est date estimé, sur les états résolus et rejeté la date et désormais date de résolution. (./dashboard.php)<br />
- Liste des tickets: la colonne priorité peut être masqué. (./dashboard.php)<br />
<br /><br />
<u>Bugfix:</u><br />
- Liste des tickets: les filtres n'était pas conserver lors de la touche retour depuis un ticket<br />
- Liste des tickets: taille de certains filtres ne remplissaient pas la colonne sur les grand écrans <br />
- Liste des tickets: l'accès depuis un compte superviseur, ne permettait plus de voir tous les tickets<br />
- Liste des tickets: Avec certains configuration serveur un défaut avec la colonne lieu apparaissait<br />
- Liste des tickets: Le titre de la colonne lieu était erroné. <br />
- Liste des tickets: l'horloge indiquant un retard de traitement, s'affichait sur les tickets résolus aussi. <br />
- Tickets: Lors de l'appui sur le bouton enregistrer quitter, conservation de la page ticket sur . <br />
- Tickets: Les autres tickets en attente de retour s'affiche, désormais a coté du demandeur. <br />
- Sécurité: upload de fichier. (./core/upload.php) <br />
<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 17/06/2015             <br />
# @Patch : 3.0.11            	 <br />
#################################<br />
<br />

Update:<br />
- Ticket: les champs date de fin estimé, criticité, priorité, peuvent être obligatoire, si le droit est positionné. (./ticket.php, ./core/ticket.php)<br /> 
- Mails: Copie Multiple - Ajout de la détection du ; dans la liste des copies des paramètres $rparameters['mail_cc'], et donc envoi en copie multiple (./core/mail.php)<br /> 
- Meta-état: un nouvel état regroupant les état en attente de pec, en cours, en attente de retour, est activable pour les techniciens dans la partie administration.(./menu.php, .parameters.php ./:dashboard.php)<br />
- Export: Ajout du service du demandeur(./core/export.php)<br />
- Liste ticket: Ajout de la visualisation des lieux (tplaces) dans le dashboard et prise en compte totale SI le paramètre "gestion des lieux" est coché (./dashboard.php)<br /> 
- Liste ticket: Une préférence utilisateur de trie est possible pour les administrateurs et techniciens configurable dans le profil utilisateur (./admin/user.php ./dashboard.php)<br /> 
- Liste ticket: Une préférence utilisateur d'arriver sur un état données est configurable dans le profil utilisateur  (./admin/user.php ./login.php)<br /> 
- Liste ticket: La date création peut être remplacer par la date de résolution estimée, paramétrable dans la section administration.  (./dashboard.php ./admin/parameters.php)<br /> 
- Module de disponibilité: ajout de la gestion des tx cible (./plugins/availability/admin/parameters.php, ./admin/parameters.php ./plugins/availability/index.php ./plugins/availability/core.php ./plugins/availability/median.php)
<br /><br />
Bugfix:<br />
- Upload: certains caractères n'étaient pas remplacés (./core/upload.php) <br />
- Stat: certains critères globaux n'était pas pris en compte(./stats/* ) <br />
- Modèle de ticket: incohérence lorsque qu'un utilisateur avec pouvoir l'utilise (./ticket_template.php) <br />
- Export: les exports sont désormais au format csv pour gérer les grosses bases (./core/export.php) <br />
- Module disponibilité: les bornes de dates était basé sur la date de création du ticket, et non du début de l'indisponibilité (./plugins/availability/index.php) <br />
- Module disponibilité: Les catégories n'était pas affichées tant qu'un ticket n'avait pas été crée (./plugins/availability/index.php ./admin/parameters.php) <br />
- Correction faute (./login.php)<br />
- Calendrier le mercredi était décalé (./planning.php)<br />
- Utilisateurs: les utilisateurs ne pouvaient plus modifier leurs profil.<br />
- Paramètres: Défaut saut de ligne sur une option (./admin/parameters.php).<br />
- Sécurité: Renforcement de la sécurité sur l'édition des tickets  (./index.php, index_auth.php, dashboard.php, /admin/user.php)<br />
- Liste des tickets: suppression de la colorisation des tickets anciens pour gain de performance  (./dashboard.php, ./admin/parameters.php)<br />
- Liste des tickets: sur la vue tous les ticket lors qu'un filtre est appelé les états sont conservés  (./dashboard.php)<br />
- Connexion: La redirection vers un ticket depuis un lien mail fonctionne aussi avec les authentification LDAP  (./login.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 30/10/2014             <br />
# @Patch : 3.0.10            	 <br />
#################################<br />
<br />

Notice:
- Mail: Pour les utilisateurs du connecteur SMTP en SSL, si vous avez modifié votre nom d'hôte en préfixant "ssl://" vous devez le supprimer.<br />
<br />
Update:<br />
- Procédure: Organisation par catégorie (./procedure.php)<br />
- Stat: une seul ligne de filtre (./stat_line.php stat.php ./stat/*)<br />
- Stat: affichage du nombre total de demande en cours. (./stat_line.php ./stats/lien_tickets.php)<br />
- mail: Ajout de 2 destinataire en plus. (./preview_mail.php ./core/mail.php)<br />
<br />
Bugfix:<br />
- Stat: Conserver le service de l'utilisateur dans le ticket, afin d'associé un ticket à un service et plus à un utilisateur. (./core/ticket.php ./stats.pie_services.php)</br />
- Stat: Export Excel modification de la limite de 7MB par fichier passage à 30MB. (./components/../class.writeexcel_olewriter.inc.php)</br />
- Mail: Pour les mails sécurisé SSL l'ajout de ssl:// en préfixe n'est plus nécessaire (./core/mail.php ./admin/parameters.php)<br />
- Connecteur IMAP: la page de test gère les accents.(./mail2ticket.php)<br />
- Modèle: la duplication de ticket intègre le type (./ticket_template.php)<br />
- Users: dans l'ajout modification des values des cases option pour changer le mot de passe. (./users.php)<br />
- Dashboard: Choix 'supprimer' bloqué par le droit. (./dashboard.php)<br />
- Dashboard: Changement de nom pour les ticket du jour dans le menu pour ne pas décaler sur deux lignes. (./index.php)<br />
- Dashboard: Problème de sécurité en modifiant le userid dans la barre d'URL (./index.php)<br /> 
- Liste: la touche retour du navigateur n'affiche plus de message d'erreur suite à un $_POST (./index.php) <br /> 
- Disponibilité: manque des variables non initialisés dans certains cas (./plugins/availability/index.php)<br /> 
- Ticket: Les pièces-jointes avec caractères spéciaux été mal renommées (./core/upload.php)<br />
- Ticket: Ajout de commentaires sur les flèches suivant et précédent (./ticket.php)<br />
- Ticket: Ajout de slash sur le titre lors de l'ajout d'un thread (./core/ticket.php)<br />
- Ticket: Les noms des pièces jointe peuvent aller jusqu'à 500 caractères (SQL)<br />
- Ticket: Ré-initialisation des champs lieu et criticité sur modification de la criticité (./ticket.php)<br />
- Mails: la troisième personne en copie du message ne reçoit pas le mail (./preview_mail.php)<br />
- Moniteur: Correction des pluriels (./monitor.php)<br />
- Moniteur: Le son fonctionne tout le temps lors d'un nouveau ticket(./monitor.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 09/07/2014             <br />
# @Patch : 3.0.9            	 <br />
#################################<br />
<br />

Update:<br />
- Liste utilisateurs: la recherche par le numéro de téléphone est possible (./admin/user.php)<br />
- Gestion de la disponibilité:  (./admin/parameters.php ./menu.php ./ticket.php ./core/ticket.php ./stat_bar_stacked.php ./index.php)<br />
- Système: ajout informations installation openssl Linux (./system.php)

<br />

Bugfix:<br />
- Tickets: Les images dans les champs description et résolution ne s'affiche pas (SQL)<br />
- Tickets: Doublon dans la liste des types sur certaines actions (./ticket.php)<br />
- Tickets: Lors de la modification du l'état résolus, si la date de résolution est anti-daté, la date n'est pas prise en compte (./core/ticket.php)<br />
- Déconnexion: Suppression de la redirection lors de la déconnexion (./index.php)<br />
- Mail: Sur la prévisualisation l'icône de la pièce jointe lorsqu'elle était en majuscule ne s'affichait pas(./preview_mail.php)<br />
- Liste des tickets: Le titre de la page est correcte lors de la sélection d'une vue(./dashboard.php)<br />
- Liste des tickets: lorsque l'on sélectionne la vue aujourd'hui la page 2 ne fonctionne pas. (./dashboard.php) <br />
- Fiche utilisateur: Lors de l'ajout d'un utilisateur les champs service et fonction sont conservées (./admin/user.php)<br /> 
- Mails: Les messages automatiques n'était pas envoyés si l'état lors de l'ouverture était en attente de prise en charge. (./core/auto_mail.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 09/04/2014             <br />
# @Patch : 3.0.8            	 <br />
#################################<br />
<br />

Update:<br />
- Utilisateur: Lors de la création ou modification d'utilisateur depuis un ticket le champ Société est accessible (./ticket_useradd.php)<br />
- Utilisateur: Inscription des utilisateurs, nouveau paramètre (./login.php ./admin/parameters.php ./register.php)<br />
- Utilisateur: Sur la liste des utilisateurs la pagination, le trie, et la recherche est disponible (./admin/user.php ./index.php)<br />
- Moniteur: Affichage des ticket résolus du jour (./monitor.php)<br />
- Export Excel: Ajout de la colonne société (./core.export.php)<br />
- Ticket: Ajout du champs date de résolution (./ticket.php ./core/ticket.php)<br />
- Paramètres: Possibilité de changer le port SMTP sans utiliser sans sécurisé le protocole (./admin/parameters.php ./core/mail.php ./core/message.php)<br />

<br />

Bugfix:<br />
-Utilisateur: Lors de la création d'un nouvel utilisateur on peut sélectionner la société. (./admin/user.php) <br />
-Utilisateur: Sur la fiche utilisateur le service était présent deux fois dans la liste. (./admin/user.php) <br />
-Utilisateur: Sur la liste des utilisateurs avec firefox les icônes d'actions était sur deux lignes. (./admin/user.php) <br />
-Statistiques: Erreur de définition de variable. (./stat.php) <br />
-Statistiques: Répartition par catégorie, vu toutes les catégories fonctionnel. (./stat/pie_cat.php) <br />
-Statistiques: Les noms long sur les camemberts ne sont plus coupés<br />
-Sécurité: Injection de code depuis les champs utilisateurs. (./admin/user.php) <br />
-Sécurité: Les utilisateurs pouvait modifier les informations d'autres utilisateurs. (./admin/user.php) <br />
-Sécurité: Les utilisateurs ne peuvent plus consulter le dossier de sauvegarde. (./backup/.htaccess) <br />
-Listes: Ré-organisation par ordre alphabétique. (./admin/list.php) <br />
-Listes: Les nouvelles entrées de la liste état sont supprimables. (./admin/list.php) <br />
-Mail: Variable non définit. (./core/mail.php) <br />
-Mail: Lorsque le paramètre envoi de mail automatique à l'utilisateur est activé, sur le ticket lors d'un ajout de catégorie un message était envoyé. (./core_ticket.php) <br />
-Liste tickets: Lors d'une recherche le nombre de ticket compté été faux. (./dashboard.php) <br />
-Ticket: Le droit de modification du demandeur sur l'édition d'un ticket ne fonctionnait pas. (./ticket.php) <br />
-Liste des tickets: Les utilisateurs et utilisateurs avec pouvoir ayant le droit d'afficher tous les tickets affiche la colonne Demandeur. (./dashboard.php) <br />
-Sociétés: Les codes postaux des sociétés qui commence par un 0 sont gérés. (SQL) <br />
-LDAP: La synchronisation des sociétés est fonctionnel . (SQL) <br />

<br />

#################################<br />
# @Name : GestSup Release Notes  <br />
# @Date : 14/03/2014             <br />
# @Patch : 3.0.7            	 <br />
#################################<br />
<br />

Update:<br />
- Statistiques: Ajout de la courbe d'évolution du nombre de ticket fermé (./stats_line.php, ./stats/*.php)<br />
- Statistiques: Ajout de nouveaux critères de sélection (./stats.php, ./stats/*.php)<br />
- Statistiques: Ajout de camemberts pour les services et société si il y en as (./stat.php ./stats/pie_company.php ./stats/pie_services.php)<br />
- Moniteur: Joue un son si un nouveau ticket est crée par un utilisateur (./monitor.php)<br />
- Utilisateurs: Gestion des sociétés le paramètre "utilisateurs avancés" doit être activé (./admin/list.php ./admin/user.php ./ticket_useradd.php ./ticket.php)<br />
- Barre utilisateur: Distinction des demandes en attente de retour (./index.php ./menu.php) <br />
<br />

Bugfix:<br />
- Export Excel: Plus de timeout sur les grosses bases de données, ajout du type de ticket (./core/export.php)<br />
- Ticket: changement d'état automatique à en attente de PEC lors de la création du ticket si l'on précise état résolu (./core/ticket.php)<br />
- Logo: si aucun logo n'est choisi alors c'est le logo par défaut qui s'affiche. (./index.php ./login.php)<br />
- Sécurité: suppression de l'html dans les champs texte affichés (./admin/parameters.php ./core/ticket.php)<br />
- Sécurité: le chargements des fichiers php est désormais impossible (./admin/parameters.php ./core/upload.php)<br />
- Utilisateur: lors de l'insertion d'adresse avec apostrophes.(./admin/user.php)<br />
- Utilisateur: la recherche sur les tickets crées avec la version 2 des utilisateurs à rattacher à un technicien est vide.(./admin/user.php)<br />
- Statistiques: le menu utilisateur ne s'affiche pas lorsque l'on se trouve sur la page statistique (./stat_line.php)<br />
- Sauvegarde: lors du lancement de la sauvegarde manuel les anciennes sauvegardes sont exclues (./admin/backup.php)<br />
- Calendrier: numéro de jour incorrectes les mardis (./planning.php)<br />
- Droits: Certains nommage sont incorrecte. <br />
- Listes: Les nom avec apostrophe ne fonctionne pas. (./admin/list.php) <br />

<br />

#################################<br />
# @Name : GestSup Release Notes <br />
# @Date : 15/02/2014            <br />
# @Patch : 3.0.6               <br />
#################################<br />
<br />
Update:<br />
- Ticket: l'ordre d'affichage des autres tickets du demandeur est décroissant (./ticket.php)<br />
- Mails: Possibilité d'envoyer ou non la pièce jointe (./preview_mail.php ./core/mail.php)<br />
- Impression: Ajout de la date de résolution si elle existe (./ticket_print.php ./core/ticket.php)<br />
- Supervision: Écran des supervision indiquant le nombre de tickets en attente d'attribution et du jour. (./monitor.php ./admin/parameters.php)<br />
- Statistiques: Export Excel des tickets. (./stats.php ./core/export.php ./components/php_writeexel/*)<br />

<br />
Bugfix:<br />
- Mails: redirection automatique vers le ticket lors de l'utilisation du lien du mail. (./login.php)<br />
- Mails: Les utilisateurs désactivés n'apparaissent plus dans les liste des destinataire en copie (./preview_mail.php)<br />
- LDAP: La synchronisation des noms et prénoms fonctionne. (./core/ldap.php)<br />
- Statistiques: Le nom des états n'était plus affichés(./stat.php)<br />
- Boutons: liens crée depuis les boutons en haut à gauche(./index.php)<br />
- Système: Valeurs du phpinfo non récupéré sous CentOS (./system.php ./install/index.php)<br />
- Ticket: La date de résolution n'était plus enregistrée (./core/ticket.php)<br />
- Recherche: Lenteurs de recherche sur les bases avec beaucoup d'utilisateurs (./searchengine.php)<br />
- Fiche utilisateur: Les boutons changement de mot passe s'affiche mal avec IE8 (./admin/user.php)<br />

<br />
#################################<br />
# @Name : GestSup Release Notes <br />
# @Date : 23/01/2014            <br />
# @Patch : 3.0.5               <br />
#################################<br />
<br />
Update:<br />
- Ticket: pouvoir anti-daté un ticket (./ticket.php)<br />
- Liste des tickets: lors du clic sur aujourd'hui les tickets du jour de l'ensemble des techniciens s'affichent (./index.php ./dashboard.php)<br />
- Statistiques: Le nombre de tickets sur camemberts sont affichés. (./stat.php ./stat_pie.php)<br />
<br />
Bugfix:<br />
- Mails: certains mails ne s'affichent pas correctement sur certains webmails (./core/mail.php)<br />
- Recherche: la recherche sur les utilisateurs possédant le même nom fonctionne (./searchengine.php)<br />
- Tickets Procédure: Les icônes de la barre d'édition de texte, sont tous en français (./wysiswyg.php)<br />
- Système: Les valeurs étaient vide sous CentOS (./system.php ./install/index.php)<br />
- Paramètres connecteur: Le bouton de test ldap perd les paramètres du connecteur. (./admin/parameters.php ./core/ldap.php)<br />
- Impression ticket: des variables n'étaient pas initialisées. (./ticket_print.php)<br />
- Fichiers manquants: fichiers de police glyphicons introuvables. (./template/assets/font/gly* ./template/assets/css/uncompressed/bootstrap.css ./template/assets/css/bootstrap.min.css)<br />
- Mails: sur certains webmail le titre était affiché en petit (./core/mail.php)<br />
- LDAP: L'affichage de la ligne d'activation des utilisateurs n'est plus décalé (./core/ldap.php)<br />
- Mails: Le message envoyé à l'administrateur lors de la déclaration d'un ticket par l'utilisateur n'a plus de slash en trop(./core/ticket.php)<br />
- Tickets: les copiés collés depuis word avec firefox s'affiche mal (./core/ticket.php)<br />
<br />
#################################<br />
# @Name : GestSup Release Notes <br />
# @Date : 09/01/2014            <br />
# @Patch : 3.0.4               <br />
#################################<br />
<br />
Update:<br />
- Paramètres: de Gestion des types de ticket, demande, incident... (./admin/parameters.php ./admin/list.php ./ticket.php ./core/ticket.php ./stat.php)<br />
<br />
Bugfix:<br />
- Tickets: les titres ne sont plus coupés avec des points (./ticket.php)<br />
- Tickets: des retours a lignes sont envoyé sur le mail, en trop(./core/mail.php ./core/ticket.php)<br />
- Liste tickets: le changement de page sur un filtre par date fonctionne. (./dashboard.php)<br />
<br />
#################################<br />
# @Name : GestSup Release Notes <br />
# @Date : 07/01/2014            <br />
# @Patch : 3.0.3                <br />
#################################<br />
<br />
Update:<br />
- <br />
Bugfix:<br />
- Install: problème d'encodage avec le squelette en UTF-8 à l'installation(./install/index.php)<br />
- Changelog: problème d'affichage des caractères spéciaux du changelog.(./changelog.php ./admin/infos.php ./index.php) </br>
- Rattachement utilisateur: le transfert automatique fonctionne. (./admin/user.php ./ticket.php) <br />
- Ticket: sélection automatique d'un technicien lorsque le technicien n'est pas renseigné. <br />
- Ticket: la description est perdu sur modification de la catégorie.(./ticket.php ./thread.php) <br />
- Ticket: agrandissement des champs texte (./ticket.php ./thread.php ./core/ticket.php)<br />
- Ticket: message d'erreur lors de la validation de ticket sans demandeur(./core/ticket.php)<br />
- Liste tickets: le filtre par date fonctionne (./dashboard.php)<br />
- Liste des utilisateurs: l'utilisateur connecté ne peut plus se désactiver (./admin/user.php)<br />
<br />
<br />
#################################<br /> 
# @Name : GestSup Release Notes<br /> 
# @Date : 28/12/2013<br />           
# @Patch : 3.0.2<br />                
#################################<br />
<br />
Update:<br />
- Ticket: rattachement d'un utilisateur à un technicien (./admin/user.php, ./core/ticket.php)<br />
- Ticket: ajout du bouton sauvegarder et quitter dans la barre du ticket (./ticket.php)<br />
- Intégration du lien vers le site sur la page de login (./index.php)<br />
<br />
Bugfix:<br />
- profil: la modification du thème est prise en compte.<br />
- Liste tickets: les titres non lus et non attribué ne s'affichait pas. (./dashboard.php)<br />
- Liste tickets: lors de la sélection multiple plus de redirection vers un ticket (./dashboard.php)<br />
- Liste tickets: le filtre sur l'état fonctionne.<br />
- Menu: correction procédures avec un s (./menu.php)<br />
- Mails: depuis certains clients de messagerie, erreur d'affichage des caractères spéciaux (./core/mail.php)<br />
- Ticket: perte titre sur changement de catégorie (./ticket.php)<br />
<br />
<br />
#################################<br /> 
# @Name : GestSup Release Notes #<br />
# @Date : 27/12/2013            #<br />
# @Patch : 3.0.1                #<br />
#################################<br />
<br />
Bugfix:<br />
- LDAP: les utilisateurs ayant un UAC de 512 sont gérés (/core/ldap.php)<br />
- Mail: Charset par défaut est UTF-8 (/core/mail.php , /core/message.php)<br />
- Affichage: désactivation automatique du mode de compatibilité du navigateur Internet explorer (./index.php)<br />
- Affichage: colonne fixe pour les actions dans la liste des utilisateur dans administration (./admin/user.php)<br />
- Statistique: sur la courbe des ticket il n'y plus qu'une seul fois le jour. (./stat.php)<br />
- Nouveau Ticket: un message d'erreur SQL s'affiche (./thread.php)<br />
<br />
<br />
#################################################################################<br />
# @Name : GestSup Release Notes                                                 #<br />
# @Date : 26/12/2013                                                            #<br />
# @Version : 3.0.0                                                              #<br />
#################################################################################<br />
<br />
<br />
Notice:<br />
- Merci de sélectionner votre canal de mise à jour  dans administration, mise à jour.<br />
- Pour les utilisateurs de CentOS, vous devez modifier votre fichier de configuration apache et définir l'encodage à "AddDefaultCharset UTF8"<br />
<br />
Update:<br />
- Interface graphique<br />
- Admin / listes: la gestion des catégories est simplifié<br />
- Admin / droits: gestion de nouveaux droits<br />
- Mise à jour: Gestion de deux canaux stable et bêta<br />
- Mise à jour: l'installation automatique des patchs et intégré<br />
- L'encodage des fichiers passe en UTF-8<br />
<br />
Bugfix:<br />
- Liste tickets: l'ordre de trie défini dans les paramètres n'est pas pris en compte sur les états en cours...<br />
- Liste tickets: Le tri des tickets ne fonctionne pas sur la section en cours<br />
- Liste tickets: lors du passage sur une autre page le nom de la catégorie sélectionnée n'était plus affichée.<br />
- ticket: les utilisateurs ne pouvaient pas indiquer le lieu dans la création de ticket.<br />
- ticket: sur les nouveaux tickets le technicien n'était pas conservé en cas de changement<br />
- Recherche: les tickets n'ayant pas de résolution son intégré dans les recherches<br />
- mails: le message invalid address n'apparait plus quand le champ adresse en copie est vide<br />
- LDAP: la synchronisation des caractères spéciaux est géré<br />
- LDAP: les utilisateurs issus des synchronisations ne peuvent plus se connecter avec un mot de passe vide<br />
- LDAP: ré-encodage UTF-8 des informations récupérées dans l'annuaire LDAP<br />
- LDAP AD: Gestion de la désactivation de l'utilisateur invité, et des comptes possédant aucune expiration de mot de passe.<br />
