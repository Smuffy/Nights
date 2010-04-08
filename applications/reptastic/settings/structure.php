<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

// Use this file to construct tables and views necessary for your application.
// There are some examples below to get you started.

if (!isset($Drop))
   $Drop = FALSE;
   
if (!isset($Explicit))
   $Explicit = TRUE;
   
Gdn::Structure()
    ->Table('Shadow')
        ->PrimaryKey('UserID', 'int(11)')
        ->Column('Reputation', 'mediumint(9)')
        ->Column('Class', 'varchar(11)')
        ->Column('Status', 'tinyint(1)')
        ->Column('LastRaid', 'datetime')
        ->Column('IsRaiding', 'tinyint(1)')
    ->Set($Explicit, $Drop);

$ShadowValidation = new Validation();
$ShadowModel = new Model('Shadow', $ShadowValidation);

Gdn::Structure()
    ->Table('Raiding')
        ->PrimaryKey('UserID', 'int(11)')
    ->Set($Explicit, $Drop);
    
$RaidValidation = new Validation();
$RaidingModel = new Model('Raiding', $RaidValidation);