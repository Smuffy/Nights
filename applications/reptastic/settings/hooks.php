<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/


class ReptasticHooks implements Gdn_IPlugin {
   
   public function Setup() {
      return TRUE;
   }
   
   public function ProfileController_AfterPreferencesDefined_Handler(&$Sender) {
      $Sender->Preferences['Email Notifications']['Email.ReputationChange'] = Gdn::Translate('Notify me when my Shadow Reputation is manually updated.');
   }
   
   public function ProfileController_AddProfileTabs_Handler(&$Sender) {
		$Sender->AddProfileTab(Translate("Loot History"), "/history/".Format::Url($Sender->User->Name));
   }
   
   public function Base_Render_Before(&$Sender) {
      // Add menu items.      
      $Session = Gdn::Session();
      
      $Reptastic = Gdn::Translate('Reptastic');
      $ReptasticHome = '/'.Gdn::Config('Reptastic', 'reputation');
      
      if ($Sender->Menu) {
          $Sender->Menu->AddLink('Reptastic', 'Reputation', $ReptasticHome, FALSE);
          $Sender->Menu->AddLink('Reptastic', 'Loot Policy', $ReptasticHome.'/policy', FALSE);
 
          if ($Sender->Menu && $Session->IsValid()) {
            $Sender->Menu->AddLink('Reptastic', 'History', '/history', FALSE);
            $Sender->Menu->AddLink('Reptastic', 'Reputation Playground', '/reputation/playground', FALSE);
         
            if ($Session->CheckPermission('Garden.Users.Edit')) {                
                $Sender->Menu->AddLink('Dashboard', 'Officer Panel', '/garden/settings', FALSE);
                $Sender->Menu->AddLink('Dashboard', 'Start a Raid', '/raiding', FALSE);
                $Sender->Menu->AddLink('Dashboard', 'Raid Panel', '/raiding/panel', FALSE);
                $Sender->Menu->AddLink('Dashboard', 'Manage Reputation', '/manage/characters', FALSE);
            }//end if
          }//if
       }//if
   }
}