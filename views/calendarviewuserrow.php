<?php
/**
 * calendarviewuserrow.php
 * 
 * Calendar view page - User row
 *
 * @category TeamCal Neo 
 * @version 1.9.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2018 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"calendarviewuserrow.php: \");</script>";

//
// Check whether the current user may view users profiles.
// If so, we link the name to the viewprofile page
//
$fullName = $U->getLastFirst($usr['username']);
if (isAllowed($CONF['controllers']['viewprofile']->permission))
{
   $profileName = '<a href="index.php?action=viewprofile&amp;profile=' . $usr['username'] . '">'.$fullName.'</a>';
}
else
{
   $profileName = $fullName;
}
 
//
// Check whether the current user may edit this loop users calendar.
// If so, we link each table cell to the editcalendar page (done so in the day loop below).
//
$editAllowed = false;
$editLink = '';
if (isAllowed($CONF['controllers']['calendaredit']->permission))
{
   if ( $UL->username == $usr['username'] )
   {
      if (isAllowed("calendareditown")) $editAllowed = true;
   }
   else if ( $UG->shareGroups($UL->username, $usr['username']) )
   {
      if (isAllowed("calendareditgroup")) $editAllowed = true;
   }
   else
   {
      if (isAllowed("calendareditall")) $editAllowed = true;
   }
}
if ($editAllowed)
{
   $editLink = ' onclick="window.location.href = \'index.php?action=calendaredit&amp;month='.$viewData['year'].$viewData['month'].'&amp;region='.$viewData['regionid'].'&user='.$usr['username'].'\';"';
}
?>

      <!-- ==================================================================== 
      view.calendaruserrow (<?=$viewData['year'].$viewData['month']?> - <?=$fullName?>)
      -->
      <tr>
         <?php 
         $nameStyle = "m-name";
         if ($viewData['groupid']!="all") { 
            //
            // We have a group display. Let's display guests in italic letters.
            //
            if (!$UG->isMemberOrManagerOfGroup($usr['username'], $viewData['groupid'])) {
               $nameStyle = "m-name-guest";
            }
         }?>
         <td class="<?=$nameStyle?>">
            <?php if ($C->read('showAvatars')) { ?>
               <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<img src='<?=APP_AVATAR_DIR.$UO->read($usr['username'],'avatar')?>' style='width: 80px; height: 80px;'>"><img src="<?=APP_AVATAR_DIR?>/<?=$UO->read($usr['username'],'avatar')?>" alt="" style="width: 16px; height: 16px;"></i>
            <?php } ?>
            <?php if ($C->read('showRoleIcons')) {
               $thisRole = $U->getRole($usr['username']);
               ?>
               <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['role']?>: <?=$RO->getNameById($thisRole)?>"><i class="fa fa-user text-<?=$RO->getColorById($thisRole)?>" style="font-size: 128%; padding-right: 8px;"></i></i>
            <?php } ?>
            <?=$profileName?>
            <?php if ($monAbsId=$C->read('monitorAbsence')) {
               $summary = getAbsenceSummary($usr['username'],$monAbsId,$viewData['year']);
               ?>
               <div style="text-align:right;font-style:italic;" title="<?=$A->getName($monAbsId).' '.$viewData['year'].': '.$LANG['remainder'].'/'.$LANG['allowance']?>">&nbsp;<span class="text-danger"><?=$summary['remainder']?></span>/<?=$summary['totalallowance']?></div>
            <?php } ?>
         </td>
         <?php 
         $T->getTemplate($usr['username'], $viewData['year'], $viewData['month']);
         $currDate = date('Y-m-d');
         //
         // Loop through all days of this month
         //
         for ($i=$daystart; $i<=$dayend; $i++) 
         { 
            $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
            $abs = 'abs'.$i; 
            $style = $viewData['dayStyles'][$i];
            $icon = '&nbsp;';
            $absstart = '';
            $absend = '';
            $note = false;
            $notestart = '';
            $noteend = '';
            $bday = false;
            $bdaystart = '';
            $bdayend = '';
            $allowed = true;
            
            if ($T->$abs) 
            {
               //
               // This is an absence. Get icon and coloring info.
               //
               if (!$viewData['absfilter'] OR ($viewData['absfilter'] AND $T->$abs==$viewData['absid']))
               {
                  if ($A->isConfidential($T->$abs))
                  {
                     //
                     // This absence type is confidential. Check whether the logged in user may see it.
                     // Rules:
                     // - Logged in user must be in a trusted role or must be "admin"
                     //
                     $allowed = false;
                     if (in_array($UL->getRole($UL->username), $viewData['trustedRoles']) OR $UL->username=='admin') $allowed = true;
                  }
                  
                  if ($allowed)
                  {
                     if ($A->getBgTrans($T->$abs)) $bgStyle = ""; else $bgStyle = "background-color: #". $A->getBgColor($T->$abs) . ";";
                     $style .= 'color: #' . $A->getColor($T->$abs) . ';' . $bgStyle;
                     if ($C->read('symbolAsIcon'))
                     {
                        $icon = $A->getSymbol($T->$abs);
                     }
                     else
                     {
                        $icon = '<span class="fa fa-'.$A->getIcon($T->$abs).'"></span>';
                     }
                     $countFrom = $viewData['year'].$viewData['month'].'01'; 
                     $countTo = $viewData['year'].$viewData['month'].$dayend;
                     $taken = '';
                     if ($C->read("showTooltipCount"))
                     {
                        $taken .= ' (';
                        $taken .= countAbsence($usr['username'], $T->$abs, $countFrom, $countTo, true, false);
                        $taken .= ')';
                     }
                     $absstart = '<div class="tooltip-danger" style="width: 100%; height: 100%;" data-position="tooltip-top" data-toggle="tooltip" data-title="'.$A->getName($T->$abs).$taken.'">';                 
                     $absend = '</div>';
                     if (!$A->getCountsAsPresent($T->$abs)) $dayAbsCount[$i]++; else $dayPresCount[$i]++;
                  }
                  else
                  {
                     //
                     // This is a confidential absence and the logged in user is not allowed to see it. Just color it gray and add a tooltip.
                     //
                     $style .= 'color: #d5d5d5;background-color: #d5d5d5;';
                     $icon = '&nbsp;';
                     $absstart = '<div class="tooltip-danger" style="width: 100%; height: 100%;" data-position="tooltip-top" data-toggle="tooltip" data-title="'.$LANG['cal_tt_absent'].'">';
                     $absend = '</div>';
                     if (!$A->getCountsAsPresent($T->$abs)) $dayAbsCount[$i]++; else $dayPresCount[$i]++;
                  }
               }
               else
               {
                  //
                  // An absence filter was submitted. This is not it but a different absence. Just color it gray and add a tooltip.
                  //
                  $style .= 'color: #d5d5d5;background-color: #d5d5d5;';
                  $icon = '&nbsp;';
                  $absstart = '<div class="tooltip-danger" style="width: 100%; height: 100%;" data-position="tooltip-top" data-toggle="tooltip" data-title="'.$LANG['cal_tt_anotherabsence'].'">';
                  $absend = '</div>';
                  if (!$A->getCountsAsPresent($T->$abs)) $dayAbsCount[$i]++; else $dayPresCount[$i]++;
               }
            }
            else 
            {
               if ($loopDate < $currDate AND $bgColor=$C->read('pastDayColor')) $style .= "background-color:#".$bgColor.";";
               $dayPresCount[$i]++;
            }
            
            if ($D->get($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), $usr['username'], $viewData['regionid'], true))
            {
               //
               // This is a user's daynote
               //
               $allowed = true;
               if ($D->isConfidential($D->id))
               {
                  //
                  // This daynote is confidential. Check whether the logged in user may see it.
                  // Rules:
                  // - Logged in user must be in a trusted role or must be "admin"
                  //
                  $allowed = false;
                  if (in_array($UL->getRole($UL->username), $viewData['trustedRoles']) OR $UL->username=='admin') $allowed = true;
               }
               
               if ($allowed)
               {
                  $note = true;
                  $notestart = '<div class="tooltip-'.$D->color.'" style="width: 100%; height: 100%;" data-position="tooltip-bottom" data-toggle="tooltip" data-title="' . $D->daynote . '">';
                  $noteend = '</div>';
               }
            }
            
            //
            // Select the upper right corner indicator if applicable
            //
            if ($note AND $bday)
            {
               $style .= 'background-image: url(images/ovl_bdaynote.gif); background-repeat: no-repeat; background-position: top right;';
            }
            else if ($note)
            {
               $style .= 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;';
            }
            else if ($bday)
            {
               $style .= 'background-image: url(images/ovl_birthday.gif); background-repeat: no-repeat; background-position: top right;';
            }
            
            //
            // If we have styles collected, build the style attribute
            //
            if (strlen($style)) $style = ' style="' . $style . '"';
            ?>
            
            <td class="m-day text-center"<?=$style?><?=$editLink?>>
               <?php
               if ($editAllowed) {
                  echo '<i data-position="tooltip-right" class="tooltip-success" data-toggle="tooltip" data-title="'.$LANG['cal_tt_clicktoedit'].'">';
               }
               echo $bdaystart . $notestart. $absstart . $icon . $absend . $noteend . $bdayend; 
               if ($editAllowed) {
                  echo '</i>';
               }
               ?>
            </td>
            
         <?php } ?>
      </tr>
