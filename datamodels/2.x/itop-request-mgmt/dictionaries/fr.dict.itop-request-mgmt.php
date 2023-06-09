<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: UserRequest
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:RequestManagement' => 'Gestion des demandes',
	'Menu:RequestManagement+' => 'Gestion des demandes utilisateurs',
	'Menu:RequestManagementProvider' => 'Gestion des demandes fournisseurs',
	'Menu:RequestManagementProvider+' => '',
	'Menu:UserRequest:Provider' => 'Demandes transférées à un fournisseur',
	'Menu:UserRequest:Provider+' => '',
	'Menu:UserRequest:Overview' => 'Vue d\'ensemble',
	'Menu:UserRequest:Overview+' => 'Vue d\'ensemble des demandes utilisateurs',
	'Menu:NewUserRequest' => 'Nouvelle demande utilisateur',
	'Menu:NewUserRequest+' => 'Créer un nouveau ticket de demande utilisateur',
	'Menu:SearchUserRequests' => 'Rechercher des demandes utilisateur',
	'Menu:SearchUserRequests+' => 'Rechercher parmi les demandes utilisateur',
	'Menu:UserRequest:Shortcuts' => 'Raccourcis',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Demandes utilisateurs qui me sont assignées',
	'Menu:UserRequest:MyRequests+' => '',
	'Menu:UserRequest:MySupportRequests' => 'Mes appels Support',
	'Menu:UserRequest:MySupportRequests+' => 'Les appels que j\'ai passés',
	'Menu:UserRequest:EscalatedRequests' => 'Demandes en escalade',
	'Menu:UserRequest:EscalatedRequests+' => 'Demandes utilisateurs en escalade',
	'Menu:UserRequest:OpenRequests' => 'Demandes en cours',
	'Menu:UserRequest:OpenRequests+' => 'Toutes les demandes utilisateurs en cours',
	'UI:WelcomeMenu:MyAssignedCalls' => 'Demandes utilisateurs qui me sont assignées',
	'UI-RequestManagementOverview-RequestByType-last-14-days' => 'Requêtes des 14 derniers jours par type',
	'UI-RequestManagementOverview-Last-14-days' => 'Requêtes des 14 derniers jours',
	'UI-RequestManagementOverview-OpenRequestByStatus' => 'Requêtes ouvertes par statut',
	'UI-RequestManagementOverview-OpenRequestByAgent' => 'Requêtes ouvertes par agent',
	'UI-RequestManagementOverview-OpenRequestByType' => 'Requêtes ouvertes par type',
	'UI-RequestManagementOverview-OpenRequestByCustomer' => 'Requêtes ouvertes par organisation',
	'Class:UserRequest:KnownErrorList' => 'Erreurs connues',
	'Class:UserRequest:KnownErrorList+' => 'Erreurs connues liées à des éléments de configuration impactés par ce ticket',
	'Menu:UserRequest:MyWorkOrders' => 'Tâches qui me sont assignées',
	'Menu:UserRequest:MyWorkOrders+' => '',
	'Class:Problem:KnownProblemList' => 'Problèmes connus',
	'Tickets:Related:OpenIncidents' => 'Incidents en cours',
));

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//
// Class: UserRequest
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:UserRequest' => 'Demande Utilisateur',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:status' => 'Statut',
	'Class:UserRequest/Attribute:status+' => '',
	'Class:UserRequest/Attribute:status/Value:new' => 'Nouveau',
	'Class:UserRequest/Attribute:status/Value:new+' => '',
	'Class:UserRequest/Attribute:status/Value:escalated_tto' => 'Escalade tto',
	'Class:UserRequest/Attribute:status/Value:escalated_tto+' => '',
	'Class:UserRequest/Attribute:status/Value:assigned' => 'Assignée',
	'Class:UserRequest/Attribute:status/Value:assigned+' => '',
	'Class:UserRequest/Attribute:status/Value:escalated_ttr' => 'Escalate ttr',
	'Class:UserRequest/Attribute:status/Value:escalated_ttr+' => '',
	'Class:UserRequest/Attribute:status/Value:waiting_for_approval' => 'En attente d\'approbation',
	'Class:UserRequest/Attribute:status/Value:waiting_for_approval+' => '',
	'Class:UserRequest/Attribute:status/Value:approved' => 'Approuvée',
	'Class:UserRequest/Attribute:status/Value:approved+' => '',
	'Class:UserRequest/Attribute:status/Value:rejected' => 'Rejetée',
	'Class:UserRequest/Attribute:status/Value:rejected+' => '',
	'Class:UserRequest/Attribute:status/Value:pending' => 'En attente',
	'Class:UserRequest/Attribute:status/Value:pending+' => '',
	'Class:UserRequest/Attribute:status/Value:resolved' => 'Résolue',
	'Class:UserRequest/Attribute:status/Value:resolved+' => '',
	'Class:UserRequest/Attribute:status/Value:closed' => 'Fermée',
	'Class:UserRequest/Attribute:status/Value:closed+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Type de Requête',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:incident' => 'incident',
	'Class:UserRequest/Attribute:request_type/Value:incident+' => 'Déclarer un incident ou une panne utilisateur',
	'Class:UserRequest/Attribute:request_type/Value:service_request' => 'demande de service',
	'Class:UserRequest/Attribute:request_type/Value:service_request+' => 'Demander la mise en place d\'une nouvelle fonctionalité',
	'Class:UserRequest/Attribute:impact' => 'Impact',
	'Class:UserRequest/Attribute:impact+' => 'Impact indique la séverité de la demande, souvent estimé par le nombre de personnes impactées',
	'Class:UserRequest/Attribute:impact/Value:1' => 'Un département',
	'Class:UserRequest/Attribute:impact/Value:1+' => '',
	'Class:UserRequest/Attribute:impact/Value:2' => 'Un service',
	'Class:UserRequest/Attribute:impact/Value:2+' => '',
	'Class:UserRequest/Attribute:impact/Value:3' => 'Une personne',
	'Class:UserRequest/Attribute:impact/Value:3+' => '',
	'Class:UserRequest/Attribute:priority' => 'Priorité',
	'Class:UserRequest/Attribute:priority+' => 'Ordre dans lequel les demandes doivent être traitées',
	'Class:UserRequest/Attribute:priority/Value:1' => 'Critique',
	'Class:UserRequest/Attribute:priority/Value:1+' => 'Priorité la plus haute',
	'Class:UserRequest/Attribute:priority/Value:2' => 'Haute',
	'Class:UserRequest/Attribute:priority/Value:2+' => '',
	'Class:UserRequest/Attribute:priority/Value:3' => 'Moyenne',
	'Class:UserRequest/Attribute:priority/Value:3+' => '',
	'Class:UserRequest/Attribute:priority/Value:4' => 'Basse',
	'Class:UserRequest/Attribute:priority/Value:4+' => 'Priorité la plus basse',
	'Class:UserRequest/Attribute:urgency' => 'Urgence',
	'Class:UserRequest/Attribute:urgency+' => 'Avec quelle célérité la demande doit être traitée',
	'Class:UserRequest/Attribute:urgency/Value:1' => 'Critique',
	'Class:UserRequest/Attribute:urgency/Value:1+' => '',
	'Class:UserRequest/Attribute:urgency/Value:2' => 'Haute',
	'Class:UserRequest/Attribute:urgency/Value:2+' => '',
	'Class:UserRequest/Attribute:urgency/Value:3' => 'Moyenne',
	'Class:UserRequest/Attribute:urgency/Value:3+' => '',
	'Class:UserRequest/Attribute:urgency/Value:4' => 'Basse',
	'Class:UserRequest/Attribute:urgency/Value:4+' => '',
	'Class:UserRequest/Attribute:origin' => 'Origine',
	'Class:UserRequest/Attribute:origin+' => 'Canal par lequel la demande est arrivée',
	'Class:UserRequest/Attribute:origin/Value:in_person' => 'En personne',
	'Class:UserRequest/Attribute:origin/Value:in_person+' => 'Demande créée suite à une discussion en face à face',
	'Class:UserRequest/Attribute:origin/Value:chat' => 'Chat',
	'Class:UserRequest/Attribute:origin/Value:chat+' => 'Demande créée suite à une discussion sur un chat',
	'Class:UserRequest/Attribute:origin/Value:mail' => 'Email',
	'Class:UserRequest/Attribute:origin/Value:mail+' => 'Demande créée suite à la réception d\'un email',
	'Class:UserRequest/Attribute:origin/Value:monitoring' => 'Supervision',
	'Class:UserRequest/Attribute:origin/Value:monitoring+' => 'Demande créée suite à une alerte d\'un systéme de supervision',
	'Class:UserRequest/Attribute:origin/Value:phone' => 'Téléphone',
	'Class:UserRequest/Attribute:origin/Value:phone+' => 'Demande créée suite à un appel téléphonique',
	'Class:UserRequest/Attribute:origin/Value:portal' => 'Portail',
	'Class:UserRequest/Attribute:origin/Value:portal+' => 'Demande créée via un portail utilisateur',
	'Class:UserRequest/Attribute:approver_id' => 'Approbateur',
	'Class:UserRequest/Attribute:approver_id+' => '',
	'Class:UserRequest/Attribute:approver_email' => 'Email Approbateur',
	'Class:UserRequest/Attribute:approver_email+' => '',
	'Class:UserRequest/Attribute:service_id' => 'Service',
	'Class:UserRequest/Attribute:service_id+' => '',
	'Class:UserRequest/Attribute:service_name' => 'Nom du service',
	'Class:UserRequest/Attribute:service_name+' => '',
	'Class:UserRequest/Attribute:servicesubcategory_id' => 'Sous catégorie de service',
	'Class:UserRequest/Attribute:servicesubcategory_id+' => '',
	'Class:UserRequest/Attribute:servicesubcategory_name' => 'Nom Sous catégorie de service',
	'Class:UserRequest/Attribute:servicesubcategory_name+' => '',
	'Class:UserRequest/Attribute:escalation_flag' => 'Ticket à surveiller',
	'Class:UserRequest/Attribute:escalation_flag+' => '',
	'Class:UserRequest/Attribute:escalation_flag/Value:no' => 'Non',
	'Class:UserRequest/Attribute:escalation_flag/Value:no+' => '',
	'Class:UserRequest/Attribute:escalation_flag/Value:yes' => 'Oui',
	'Class:UserRequest/Attribute:escalation_flag/Value:yes+' => '',
	'Class:UserRequest/Attribute:escalation_reason' => 'Raison de surveillance',
	'Class:UserRequest/Attribute:escalation_reason+' => '',
	'Class:UserRequest/Attribute:assignment_date' => 'Date d\'assignation',
	'Class:UserRequest/Attribute:assignment_date+' => '',
	'Class:UserRequest/Attribute:resolution_date' => 'Date de résolution',
	'Class:UserRequest/Attribute:resolution_date+' => '',
	'Class:UserRequest/Attribute:last_pending_date' => 'Dernière date de suspension',
	'Class:UserRequest/Attribute:last_pending_date+' => '',
	'Class:UserRequest/Attribute:cumulatedpending' => 'Temps cumulé de suspension',
	'Class:UserRequest/Attribute:cumulatedpending+' => '',
	'Class:UserRequest/Attribute:tto' => 'TTO',
	'Class:UserRequest/Attribute:tto+' => 'Delai garanti d\'assignation',
	'Class:UserRequest/Attribute:ttr' => 'TTR',
	'Class:UserRequest/Attribute:ttr+' => 'Délai garanti de résolution',
	'Class:UserRequest/Attribute:tto_escalation_deadline' => 'Echéance TTO',
	'Class:UserRequest/Attribute:tto_escalation_deadline+' => '',
	'Class:UserRequest/Attribute:sla_tto_passed' => 'SLA TTO dépassé ?',
	'Class:UserRequest/Attribute:sla_tto_passed+' => 'SLA TTO dépassé ?',
	'Class:UserRequest/Attribute:sla_tto_over' => 'Dépassement SLA TTO',
	'Class:UserRequest/Attribute:sla_tto_over+' => '',
	'Class:UserRequest/Attribute:ttr_escalation_deadline' => 'Echéance TTR',
	'Class:UserRequest/Attribute:ttr_escalation_deadline+' => '',
	'Class:UserRequest/Attribute:sla_ttr_passed' => 'SLA TTR dépassé ?',
	'Class:UserRequest/Attribute:sla_ttr_passed+' => '',
	'Class:UserRequest/Attribute:sla_ttr_over' => 'Dépassement SLA TTR',
	'Class:UserRequest/Attribute:sla_ttr_over+' => '',
	'Class:UserRequest/Attribute:time_spent' => 'Délai de résolution',
	'Class:UserRequest/Attribute:time_spent+' => '',
	'Class:UserRequest/Attribute:resolution_code' => 'Code de résolution',
	'Class:UserRequest/Attribute:resolution_code+' => 'Qu\'est-ce qui a été fait pour résoudre la demande ?',
	'Class:UserRequest/Attribute:resolution_code/Value:assistance' => 'Assistance',
	'Class:UserRequest/Attribute:resolution_code/Value:assistance+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:bug fixed' => 'Résolution de bug',
	'Class:UserRequest/Attribute:resolution_code/Value:bug fixed+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:hardware repair' => 'Réparation matériel',
	'Class:UserRequest/Attribute:resolution_code/Value:hardware repair+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:other' => 'Autre',
	'Class:UserRequest/Attribute:resolution_code/Value:other+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:software patch' => 'Patch logiciel',
	'Class:UserRequest/Attribute:resolution_code/Value:software patch+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:system update' => 'Mise à jour système',
	'Class:UserRequest/Attribute:resolution_code/Value:system update+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:training' => 'Formation',
	'Class:UserRequest/Attribute:resolution_code/Value:training+' => '',
	'Class:UserRequest/Attribute:solution' => 'Solution',
	'Class:UserRequest/Attribute:solution+' => '',
	'Class:UserRequest/Attribute:pending_reason' => 'Raison de suspension',
	'Class:UserRequest/Attribute:pending_reason+' => '',
	'Class:UserRequest/Attribute:parent_request_id' => 'Requête parente',
	'Class:UserRequest/Attribute:parent_request_id+' => '',
	'Class:UserRequest/Attribute:parent_request_ref' => 'Ref requête parente',
	'Class:UserRequest/Attribute:parent_request_ref+' => '',
	'Class:UserRequest/Attribute:parent_problem_id' => 'Problème lié',
	'Class:UserRequest/Attribute:parent_problem_id+' => '',
	'Class:UserRequest/Attribute:parent_problem_ref' => 'Ref Problème lié',
	'Class:UserRequest/Attribute:parent_problem_ref+' => '',
	'Class:UserRequest/Attribute:parent_change_id' => 'Changement parent',
	'Class:UserRequest/Attribute:parent_change_id+' => '',
	'Class:UserRequest/Attribute:parent_change_ref' => 'Ref Changement parent',
	'Class:UserRequest/Attribute:parent_change_ref+' => '',
	'Class:UserRequest/Attribute:related_request_list' => 'Requêtes filles',
	'Class:UserRequest/Attribute:related_request_list+' => 'Toutes les requêtes liées à cette requête parente',
	'Class:UserRequest/Attribute:public_log' => 'Journal public',
	'Class:UserRequest/Attribute:public_log+' => '',
	'Class:UserRequest/Attribute:user_satisfaction' => 'Satisfaction client',
	'Class:UserRequest/Attribute:user_satisfaction+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:1' => 'Très satisfait',
	'Class:UserRequest/Attribute:user_satisfaction/Value:1+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:2' => 'Plutôt satisfait',
	'Class:UserRequest/Attribute:user_satisfaction/Value:2+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:3' => 'Plutôt mécontent',
	'Class:UserRequest/Attribute:user_satisfaction/Value:3+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:4' => 'Très mécontent',
	'Class:UserRequest/Attribute:user_satisfaction/Value:4+' => '',
	'Class:UserRequest/Attribute:user_comment' => 'Commentaire client',
	'Class:UserRequest/Attribute:user_comment+' => '',
	'Class:UserRequest/Attribute:parent_request_id_friendlyname' => 'nom usuel requête parente',
	'Class:UserRequest/Attribute:parent_request_id_friendlyname+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Assigner',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Réassigner',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_approve' => 'Approuver',
	'Class:UserRequest/Stimulus:ev_approve+' => '',
	'Class:UserRequest/Stimulus:ev_reject' => 'Rejeter',
	'Class:UserRequest/Stimulus:ev_reject+' => '',
	'Class:UserRequest/Stimulus:ev_pending' => 'En attente',
	'Class:UserRequest/Stimulus:ev_pending+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_autoresolve' => 'Résolution automatique',
	'Class:UserRequest/Stimulus:ev_autoresolve+' => '',
	'Class:UserRequest/Stimulus:ev_autoclose' => 'Fermeture automatique',
	'Class:UserRequest/Stimulus:ev_autoclose+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Marquer comme résolu',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Clore cette requête',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_reopen' => 'Ré-ouvrir',
	'Class:UserRequest/Stimulus:ev_reopen+' => '',
	'Class:UserRequest/Stimulus:ev_wait_for_approval' => 'Attendre une approbation',
	'Class:UserRequest/Stimulus:ev_wait_for_approval+' => '',
	'Class:UserRequest/Error:CannotAssignParentRequestIdToSelf' => 'La demande parente ne peut pas être assignée à elle même',
));


Dict::Add('FR FR', 'French', 'Français', array(
	'Portal:TitleDetailsFor_Request' => 'Détail de la demande',
	'Portal:ButtonUpdate' => 'Mettre à jour',
	'Portal:ButtonClose' => 'Fermer',
	'Portal:ButtonReopen' => 'Re-ouvrir',
	'Portal:ShowServices' => 'Catalogue de service',
	'Portal:SelectRequestType' => 'Sélectionnez un type de requête',
	'Portal:SelectServiceElementFrom_Service' => 'Sélectionnez un élément de service pour %1$s',
	'Portal:ListServices' => 'Liste des services',
	'Portal:TitleDetailsFor_Service' => 'Détail d\'un service',
	'Portal:Button:CreateRequestFromService' => 'Créer une demande pour ce service',
	'Portal:ListOpenRequests' => 'Demandes en cours',
	'Portal:UserRequest:MoreInfo' => 'Informations complémentaires',
	'Portal:Details-Service-Element' => 'Eléments de service',
	'Portal:NoClosedTicket' => 'Pas de demande fermée',
	'Portal:NoService' => '',
	'Portal:ListOpenProblems' => 'Problèmes en cours',
	'Portal:ShowProblem' => 'Problèmes',
	'Portal:ShowFaqs' => 'FAQs',
	'Portal:NoOpenProblem' => 'Pas de problème en cours',
	'Portal:SelectLanguage' => 'Changer ma langue',
	'Portal:LanguageChangedTo_Lang' => 'Langue changée en',
	'Portal:ChooseYourFavoriteLanguage' => 'Choisissez votre langue',

	'Class:UserRequest/Method:ResolveChildTickets' => 'ResolveChildTickets (résoudre les tickets fils)',
	'Class:UserRequest/Method:ResolveChildTickets+' => 'Cascader l\'action de résolution de la demande (ev_autoresolve), et aligner les caractéristiques suivantes : service, équipe, agent, information de résolution',
));


Dict::Add('FR FR', 'French', 'Français', array(
	'Organization:Overview:UserRequests' => 'Demandes Utilisateurs pour cette organisation',
	'Organization:Overview:MyUserRequests' => 'Mes Demandes Utilisateurs pour cette organisation',
	'Organization:Overview:Tickets' => 'Les Tickets de cette organisation',
));


// 1:n relations custom labels for tooltip and pop-up title
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:UserRequest/Attribute:related_request_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:UserRequest/Attribute:related_request_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:UserRequest/Attribute:related_request_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:UserRequest/Attribute:related_request_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de sa %1$s',
	'Class:UserRequest/Attribute:related_request_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:UserRequest/Attribute:related_request_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
));
