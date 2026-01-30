<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Calendar Options
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['calopt_title'] = 'Options du calendrier';

$LANG['calopt_tab_display'] = 'Affichage';
$LANG['calopt_tab_filter'] = 'Filtre';
$LANG['calopt_tab_options'] = 'Options';
$LANG['calopt_tab_remainder'] = 'Reste';
$LANG['calopt_tab_stats'] = 'Statistiques';
$LANG['calopt_tab_summary'] = 'Résumé';

$LANG['calopt_alert_edit_success'] = 'Les options du calendrier ont été mises à jour.';
$LANG['calopt_alert_failed'] = 'Les options du calendrier n\'ont pas pu être mises à jour. Veuillez vérifier votre saisie.';
$LANG['calopt_calendarFontSize'] = 'Taille de la police du calendrier';
$LANG['calopt_calendarFontSize_comment'] = 'Vous pouvez diminuer ou augmenter la taille de la police de la vue mensuelle du calendrier ici en saisissant une valeur en pourcentage, par ex. 80 ou 120.';
$LANG['calopt_currentYearOnly'] = 'Année en cours uniquement';
$LANG['calopt_currentYearOnly_comment'] = 'Avec cette option, le calendrier sera restreint à l\'année en cours. Les autres années ne pourront pas être consultées ou modifiées.';
$LANG['calopt_currentYearRoles'] = 'Rôles pour l\'année en cours';
$LANG['calopt_currentYearRoles_comment'] = 'Si "Année en cours uniquement" est sélectionné, vous pouvez affecter cette restriction à certains rôles ici.';
$LANG['calopt_defgroupfilter'] = 'Filtre de groupe par défaut';
$LANG['calopt_defgroupfilter_comment'] = 'Sélectionnez le filtre de groupe par défaut pour l\'affichage du calendrier. Chaque utilisateur peut toujours modifier son filtre par défaut individuel dans son profil.';
$LANG['calopt_defgroupfilter_all'] = 'Tout';
$LANG['calopt_defgroupfilter_allbygroup'] = 'Tout (par groupe)';
$LANG['calopt_defregion'] = 'Région par défaut pour le calendrier de base';
$LANG['calopt_defregion_comment'] = 'Sélectionnez la région par défaut pour le calendrier de base à utiliser. Chaque utilisateur peut toujours modifier sa région par défaut individuelle dans son profil.';
$LANG['calopt_firstDayOfWeek'] = 'Premier jour de la semaine';
$LANG['calopt_firstDayOfWeek_comment'] = 'Réglez ceci sur Lundi ou Dimanche. Ce paramètre sera reflété dans l\'affichage du numéro de semaine.';
$LANG['calopt_firstDayOfWeek_1'] = 'Lundi';
$LANG['calopt_firstDayOfWeek_7'] = 'Dimanche';
$LANG['calopt_hideDaynotes'] = 'Masquer les notes de jour personnelles';
$LANG['calopt_hideDaynotes_comment'] = 'Activer cette option masquera les notes de jour personnelles pour les utilisateurs réguliers. Seuls les Managers, Directeurs et Administrateurs peuvent les modifier et les consulter. De cette façon, elles peuvent être utilisées uniquement à des fins de gestion. Cette option n\'affecte pas les notes d\'anniversaire.';
$LANG['calopt_hideManagers'] = 'Masquer les responsables dans l\'affichage Tout-par-groupe et Groupe';
$LANG['calopt_hideManagers_comment'] = 'Cocher cette option masquera tous les responsables dans l\'affichage Tout-par-groupe et Groupe, sauf dans les groupes où ils sont simplement membres.';
$LANG['calopt_hideManagerOnlyAbsences'] = 'Masquer les absences réservées à la direction';
$LANG['calopt_hideManagerOnlyAbsences_comment'] = 'Les types d\'absence peuvent être marqués comme "réservés aux responsables", ce qui les rend modifiables uniquement par eux. Ces absences sont affichées aux utilisateurs réguliers mais ils ne peuvent pas les modifier. Vous pouvez masquer ces absences aux utilisateurs réguliers ici.';
$LANG['calopt_includeSummary'] = 'Inclure le résumé';
$LANG['calopt_includeSummary_comment'] = 'Cocher cette option ajoutera une section de résumé extensible en bas de chaque mois, affichant les totaux de toutes les absences.';
$LANG['calopt_managerOnlyIncludesAdministrator'] = 'Réservé aux responsables inclut l\'Administrateur';
$LANG['calopt_managerOnlyIncludesAdministrator_comment'] = 'Les types d\'absence réservés aux responsables ne peuvent être définis que par eux. Avec cette option, les utilisateurs ayant le rôle "Administrateur" peuvent également le faire.';
$LANG['calopt_monitorAbsence'] = 'Surveiller l\'absence';
$LANG['calopt_monitorAbsence_comment'] = 'Spécifiez un ou plusieurs types d\'absence ici. Pour chacun, le décompte Reste/Allocation sera affiché sous le nom d\'utilisateur dans le calendrier.';
$LANG['calopt_notificationsAllGroups'] = 'Notifications pour tous les groupes';
$LANG['calopt_notificationsAllGroups_comment'] = 'Par défaut, les utilisateurs peuvent s\'abonner à des notifications par e-mail pour les événements du calendrier uniquement pour leurs propres groupes. Avec cette option, ils peuvent choisir parmi tous les groupes.<br>
      <i>Note : Si vous désactivez cette option alors que des utilisateurs avaient sélectionné d\'autres groupes, cette sélection ne changera pas jusqu\'à ce que leur profil soit à nouveau sauvegardé.</i>';
$LANG['calopt_pastDayColor'] = 'Couleur des jours passés';
$LANG['calopt_pastDayColor_comment'] = 'Définit une couleur de fond pour les jours passés dans la vue mensuelle du calendrier. Laissez ce champ vide si vous ne souhaitez pas colorer les jours passés.';
$LANG['calopt_regionalHolidays'] = 'Marquer les jours fériés régionaux';
$LANG['calopt_regionalHolidays_comment'] = 'Avec cette option, les jours fériés dans des régions autres que celle actuellement sélectionnée seront marqués d\'une bordure colorée.';
$LANG['calopt_regionalHolidaysColor'] = 'Couleur de bordure des jours fériés régionaux';
$LANG['calopt_regionalHolidaysColor_comment'] = 'Définit la couleur de la bordure pour le marquage des jours fériés régionaux.';
$LANG['calopt_repeatHeaderCount'] = 'Nombre de répétitions de l\'en-tête';
$LANG['calopt_repeatHeaderCount_comment'] = 'Spécifie le nombre de lignes d\'utilisateurs dans le calendrier avant que l\'en-tête du mois ne soit répété pour une meilleure lisibilité. S\'il est réglé sur 0, l\'en-tête ne sera pas répété.';
$LANG['calopt_satBusi'] = 'Le samedi est un jour ouvrable';
$LANG['calopt_satBusi_comment'] = 'Par défaut, le samedi et le dimanche sont des jours de week-end. Cochez cette option si vous souhaitez faire du samedi un jour ouvrable.';
$LANG['calopt_showAvatars'] = 'Afficher les avatars';
$LANG['calopt_showAvatars_comment'] = 'Cocher cette option affichera un pop-up d\'avatar utilisateur lors du passage de la souris sur l\'icône de l\'avatar.';
$LANG['calopt_showMonths'] = 'Afficher plusieurs mois';
$LANG['calopt_showMonths_comment'] = 'Saisissez le nombre de mois à afficher sur la page du calendrier (maximum 12).<br><i>Note : Un utilisateur peut écraser cette valeur dans ses paramètres, laquelle prime sur la valeur par défaut.</i>';
$LANG['calopt_showRegionButton'] = 'Afficher le bouton de filtre de région';
$LANG['calopt_showRegionButton_comment'] = 'Cocher cette option affichera le bouton de filtre de région en haut du calendrier pour basculer facilement entre les différentes régions. Si vous n\'utilisez que la région standard, il peut être judicieux de masquer ce bouton.';
$LANG['calopt_showRoleIcons'] = 'Afficher les icônes de rôle';
$LANG['calopt_showRoleIcons_comment'] = 'Cocher cette option affichera une icône à côté du nom de l\'utilisateur indiquant son rôle.';
$LANG['calopt_showSummary'] = 'Développer le résumé';
$LANG['calopt_showSummary_comment'] = 'Cocher cette option affichera/développera la section résumé par défaut.';
$LANG['calopt_showTooltipCount'] = 'Compteur d\'infobulle';
$LANG['calopt_showTooltipCount_comment'] = 'Si cette option est cochée, le montant pris pour un type d\'absence sera affiché sous la forme "(pris mois en cours/pris année en cours)" dans l\'infobulle du type d\'absence lors du survol dans le calendrier.';
$LANG['calopt_showUserRegion'] = 'Afficher les jours fériés régionaux par utilisateur';
$LANG['calopt_showUserRegion_comment'] = 'Si cette option est activée, le calendrier affichera les jours fériés régionaux dans chaque ligne d\'utilisateur en fonction de la région par défaut définie pour lui. Notez que cela peut être déroutant selon le nombre d\'utilisateurs et de régions.';
$LANG['calopt_showWeekNumbers'] = 'Afficher les numéros de semaine';
$LANG['calopt_showWeekNumbers_comment'] = 'Cocher cette option ajoutera une ligne à l\'affichage du calendrier indiquant le numéro de semaine de l\'année.';
$LANG['calopt_sortByOrderKey'] = 'Clé de tri utilisateur';
$LANG['calopt_sortByOrderKey_comment'] = 'Avec cette option, les utilisateurs du calendrier seront triés par leur clé de tri au lieu de leur nom de famille. La clé de tri est un champ facultatif du profil utilisateur.';
$LANG['calopt_statsDefaultColorAbsences'] = 'Couleur par défaut des statistiques d\'absence';
$LANG['calopt_statsDefaultColorAbsences_comment'] = 'Sélectionnez la couleur par défaut pour cette page de statistiques.';
$LANG['calopt_statsDefaultColorAbsencetype'] = 'Couleur par défaut des statistiques de types d\'absence';
$LANG['calopt_statsDefaultColorAbsencetype_comment'] = 'Sélectionnez la couleur par défaut pour cette page de statistiques.';
$LANG['calopt_statsDefaultColorPresences'] = 'Couleur par défaut des statistiques de présence';
$LANG['calopt_statsDefaultColorPresences_comment'] = 'Sélectionnez la couleur par défaut pour cette page de statistiques.';
$LANG['calopt_statsDefaultColorPresencetype'] = 'Couleur par défaut des statistiques de types de présence';
$LANG['calopt_statsDefaultColorPresencetype_comment'] = 'Sélectionnez la couleur par défaut pour cette page de statistiques.';
$LANG['calopt_statsDefaultColorRemainder'] = 'Couleur par défaut des statistiques de reste';
$LANG['calopt_statsDefaultColorRemainder_comment'] = 'Sélectionnez la couleur par défaut pour cette page de statistiques.';
$LANG['calopt_summaryAbsenceTextColor'] = 'Couleur du texte des absences';
$LANG['calopt_summaryAbsenceTextColor_comment'] = 'Ici vous pouvez définir la couleur des décomptes d\'absences dans la section résumé. Laissez le champ vide pour la couleur par défaut.';
$LANG['calopt_summaryPresenceTextColor'] = 'Couleur du texte des présences';
$LANG['calopt_summaryPresenceTextColor_comment'] = 'Ici vous pouvez définir la couleur des décomptes de présences dans la section résumé. Laissez le champ vide pour la couleur par défaut.';
$LANG['calopt_sunBusi'] = 'Le dimanche est un jour ouvrable';
$LANG['calopt_sunBusi_comment'] = 'Par défaut, le samedi et le dimanche sont des jours de week-end. Cochez cette option si vous souhaitez faire du dimanche un jour ouvrable.';
$LANG['calopt_supportMobile'] = 'Prise en charge des appareils mobiles';
$LANG['calopt_supportMobile_comment'] = 'Avec cette option, TeamCal Neo préparera les tableaux du calendrier pour une largeur d\'écran spécifique afin qu\'aucun défilement horizontal ne soit nécessaire.';
$LANG['calopt_symbolAsIcon'] = 'Identifiant de caractère du type d\'absence comme icône';
$LANG['calopt_symbolAsIcon_comment'] = 'Avec cette option, l\'identifiant de caractère sera utilisé dans l\'affichage du calendrier au lieu de son icône.';
$LANG['calopt_takeover'] = 'Activer la reprise d\'absence';
$LANG['calopt_takeover_comment'] = 'Avec cette option activée, l\'utilisateur connecté peut reprendre les absences d\'autres utilisateurs s\'il peut modifier leur calendrier. Les absences reprises ne sont PAS validées.';
$LANG['calopt_todayBorderColor'] = 'Couleur de bordure du jour actuel';
$LANG['calopt_todayBorderColor_comment'] = 'Spécifie la couleur en hexadécimal des bordures gauche et droite de la colonne du jour actuel.';
$LANG['calopt_todayBorderSize'] = 'Taille de bordure du jour actuel';
$LANG['calopt_todayBorderSize_comment'] = 'Spécifie la taille (épaisseur) en pixels des bordures gauche et droite de la colonne du jour actuel.';
$LANG['calopt_trustedRoles'] = 'Rôles de confiance';
$LANG['calopt_trustedRoles_comment'] = 'Sélectionnez les rôles qui peuvent consulter les absences confidentielles et les notes de jour.';
$LANG['calopt_usersPerPage'] = 'Nombre d\'utilisateurs par page';
$LANG['calopt_usersPerPage_comment'] = 'Indiquez combien d\'utilisateurs vous souhaitez afficher sur chaque page. Une valeur de 0 désactive la pagination.';
