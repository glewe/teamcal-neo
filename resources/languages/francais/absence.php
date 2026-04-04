<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Absence
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['abs_list_title'] = 'Types d\'absence';
$LANG['abs_edit_title'] = 'Modifier le type d\'absence : ';
$LANG['abs_alert_edit'] = 'Mettre à jour le type d\'absence';
$LANG['abs_alert_edit_success'] = 'Les informations de ce type d\'absence ont été mises à jour.';
$LANG['abs_alert_created'] = 'Le type d\'absence a été créé.';
$LANG['abs_alert_created_fail'] = 'Le type d\'absence n\'a pas pu être créé. Veuillez vérifier les erreurs de saisie dans le dialogue "Créer un type d\'absence".';
$LANG['abs_alert_deleted'] = 'Le type d\'absence a été supprimé.';
$LANG['abs_alert_save_failed'] = 'Les nouvelles informations de ce type d\'absence n\'ont pas pu être sauvegardées. Une saisie n\'était pas valide. Veuillez vérifier les messages d\'erreur.';
$LANG['abs_allow_active'] = 'Quantité restreinte';
$LANG['abs_allowance'] = 'Allocation par an';
$LANG['abs_allowance_comment'] = 'Définissez ici une allocation annuelle pour ce type d\'absence. Ce montant se réfère à l\'année civile en cours. Lors de l\'affichage du profil d\'un utilisateur, la section du décompte des absences contiendra le montant restant pour ce type d\'absence pour l\'utilisateur (une valeur négative indiquera que l\'utilisateur a utilisé trop de jours d\'absence de ce type). Si l\'allocation est fixée à 0, aucune limite n\'est supposée.';
$LANG['abs_allowmonth'] = 'Allocation par mois';
$LANG['abs_allowmonth_comment'] = 'Définissez ici une allocation mensuelle pour ce type d\'absence. Si l\'allocation est fixée à 0, aucune limite n\'est supposée.';
$LANG['abs_allowweek'] = 'Allocation par semaine';
$LANG['abs_allowweek_comment'] = 'Définissez ici une allocation hebdomadaire pour ce type d\'absence. Si l\'allocation est fixée à 0, aucune limite n\'est supposée.';
$LANG['abs_approval_required'] = 'Approbation requise';
$LANG['abs_approval_required_comment'] = 'Cocher cette case définit que ce type d\'absence nécessite l\'approbation du responsable de groupe, du directeur ou de l\'administrateur. Un utilisateur régulier choisissant ce type d\'absence dans son calendrier recevra un message d\'erreur l\'en informant. Le responsable de groupe de cet utilisateur recevra un e-mail l\'informant que son approbation est requise pour cette demande. Il pourra alors saisir cette absence pour l\'utilisateur s\'il l\'approuve.';
$LANG['abs_bgcolor'] = 'Couleur de fond';
$LANG['abs_bgcolor_comment'] = 'C\'est la couleur de fond utilisée pour ce type d\'absence, indépendamment du symbole ou de l\'icône. Cliquez dans le champ pour ouvrir le sélecteur de couleurs.';
$LANG['abs_bgtrans'] = 'Fond transparent';
$LANG['abs_bgtrans_comment'] = 'Avec cette option activée, la couleur de fond sera ignorée.';
$LANG['abs_color'] = 'Couleur du texte';
$LANG['abs_color_comment'] = 'Si le symbole de caractère est utilisé, c\'est la couleur dans laquelle il est affiché. Cliquez dans le champ pour ouvrir le sélecteur de couleurs.';
$LANG['abs_confidential'] = 'Confidentiel';
$LANG['abs_confidential_comment'] = 'Cocher cette case marque ce type d\'absence comme "confidentiel". Le public et les utilisateurs réguliers ne peuvent pas voir cette absence dans le calendrier, sauf s\'il s\'agit de la propre absence de l\'utilisateur régulier. Cette fonctionnalité est utile si vous souhaitez masquer des types d\'absence sensibles aux utilisateurs réguliers. Vous pouvez également définir des rôles de confiance dans les Options du calendrier qui pourront également visualiser ces absences.';
$LANG['abs_confirm_delete'] = 'Êtes-vous sûr de vouloir supprimer le type d\'absence "%s" ?<br>Toutes les entrées existantes dans les modèles d\'utilisateur seront remplacées par "Présent".';
$LANG['abs_counts_as'] = 'Compte comme';
$LANG['abs_counts_as_comment'] = 'Sélectionnez si les absences prises de ce type sont décomptées de l\'allocation d\'un autre type d\'absence. Si vous sélectionnez un autre type d\'absence, l\'allocation de ce type d\'absence n\'est pas prise en compte, mais celle du type sélectionné.<br>Exemple : "Vacances demi-journée" avec un facteur 0.5 compte dans l\'allocation de "Vacances".';
$LANG['abs_counts_as_present'] = 'Compte comme présent';
$LANG['abs_counts_as_present_comment'] = 'Cocher cette case définit que ce type d\'absence compte comme "présent". Imaginons que vous mainteniez un type d\'absence "Télétravail" mais comme cette personne travaille, vous ne voulez pas compter cela comme "absent". Dans ce cas, cochez la case et toutes les absences en Télétravail compteront comme présentes dans la section du résumé. Ainsi, le "Télétravail" ne figure pas non plus dans la liste des types d\'absence du résumé.';
$LANG['abs_display'] = 'Affichage';
$LANG['abs_display_comment'] = '';
$LANG['abs_factor'] = 'Facteur';
$LANG['abs_factor_comment'] = 'TeamCal peut compter le nombre de jours pris par type d\'absence. Vous trouverez les résultats dans l\'onglet "Absences" du dialogue de profil utilisateur. Le champ "Facteur" ici offre la possibilité de multiplier chaque absence trouvée par une valeur de votre choix. La valeur par défaut est 1.<br>Exemple : Vous créez un type d\'absence appelé "Demi-journée de formation". Vous voudriez lui attribuer le facteur 0.5 afin d\'obtenir le nombre total de jours de formation. Un employé ayant pris 10 demi-journées de formation se retrouverait avec un total de 5 (10 x 0.5 = 5).<br>Définir le facteur sur 0 exclura le type d\'absence du décompte.';
$LANG['abs_groups'] = 'Affectations de groupe';
$LANG['abs_groups_comment'] = 'Sélectionnez les groupes pour lesquels ce type d\'absence est valide. Si un groupe n\'est pas affecté, les membres de ce groupe ne peuvent pas utiliser ce type d\'absence.';
$LANG['abs_hide_in_profile'] = 'Masquer dans le profil';
$LANG['abs_hide_in_profile_comment'] = 'Cocher cette case définit que les utilisateurs réguliers ne peuvent pas voir ce type d\'absence dans l\'onglet Absences de leur profil. Seuls les Managers, Directeurs ou l\'Administrateur le verront. Cette fonctionnalité est utile si un manager souhaite utiliser un type d\'absence uniquement à des fins de suivi ou si les restes ne présentent aucun intérêt pour les utilisateurs réguliers.';
$LANG['abs_icon'] = 'Icône';
$LANG['abs_icon_comment'] = 'L\'icône du type d\'absence est utilisée dans l\'affichage du calendrier.';
$LANG['abs_icon_keyword'] = 'Entrez un mot-clé...';
$LANG['abs_manager_only'] = 'Responsable de groupe uniquement';
$LANG['abs_manager_only_comment'] = 'Cocher cette case définit que seuls les responsables de groupe peuvent définir ce type d\'absence. Ce type d\'absence ne sera disponible que si l\'utilisateur connecté est le responsable de groupe de l\'utilisateur dont le calendrier est en cours de modification.';
$LANG['abs_name'] = 'Nom';
$LANG['abs_name_comment'] = 'Le nom du type d\'absence est utilisé dans les listes et les descriptions et doit indiquer l\'objet de cette absence, par exemple "Déplacement professionnel". Il peut comporter 80 caractères.';
$LANG['abs_sample'] = 'Exemple d\'affichage';
$LANG['abs_sample_comment'] = 'Voici à quoi ressemblera votre type d\'absence dans votre calendrier selon vos paramètres actuels.<br>Note : Dans les Options du calendrier, vous pouvez configurer si l\'icône ou l\'identifiant de caractère doit être utilisé pour l\'affichage.';
$LANG['abs_show_in_remainder'] = 'Afficher dans le Reste';
$LANG['abs_show_in_remainder_comment'] = 'Cocher cette option inclura cette absence sur la page du Reste.';
$LANG['abs_symbol'] = 'Identifiant de caractère';
$LANG['abs_symbol_comment'] = 'L\'identifiant de caractère du type d\'absence est utilisé dans les e-mails de notification car les icônes de police n\'y sont pas prises en charge. Choisissez un seul caractère. Un identifiant de caractère est obligatoire pour chaque type d\'absence, cependant, vous pouvez utiliser le même caractère pour plusieurs types d\'absence. Par défaut, il s\'agit de la première lettre du nom du type d\'absence lors de sa création.';
$LANG['abs_tab_groups'] = 'Affectations de groupe';
$LANG['abs_takeover'] = 'Activer pour la reprise';
$LANG['abs_takeover_comment'] = 'Active ce type d\'absence pour la reprise. Notez que la fonctionnalité de reprise doit être activée dans TeamCal Neo pour que cela ait un effet.';

//
// Absence Icon
//
$LANG['absico_title'] = 'Sélectionnez l\'icône du type d\'absence : ';
$LANG['absico_tab_brand'] = 'Icônes de marque';
$LANG['absico_tab_regular'] = 'Icônes régulières';
$LANG['absico_tab_solid'] = 'Icônes pleines';

//
// Absences Summary
//
$LANG['absum_title'] = 'Résumé des absences %1$s : %2$s';
$LANG['absum_modalYearTitle'] = 'Sélectionnez l\'année pour le résumé';
$LANG['absum_unlimited'] = 'Illimité';
$LANG['absum_year'] = 'Année';
$LANG['absum_year_comment'] = 'Sélectionnez l\'année pour ce résumé.';
$LANG['absum_absencetype'] = 'Type d\'absence';
$LANG['absum_contingent'] = 'Contingent';
$LANG['absum_contingent_tt'] = 'Le contingent est le résultat de l\'allocation pour cette année plus le report de l\'année dernière. Notez que la valeur du report peut également être négative.';
$LANG['absum_taken'] = 'Pris';
$LANG['absum_remainder'] = 'Reste';
