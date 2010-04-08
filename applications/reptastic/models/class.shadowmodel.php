<?php
if(!defined('APPLICATION')) die();

require_once(PATH_ROOT.DS.'library'.DS.'calendar'.DS.'SG_iCal.php');

class ShadowModel extends Gdn_Model{
	
	public function __construct(){
        parent::__construct('Shadow');
	}
	
	public function Save($FormValues){
	    //what to save?
		return parent::Save($FormValues);
	}
	
	public function Exists($UserID) {
	   $Result = $this->SQL
	               ->Select('*')
	               ->From('Shadow')
	               ->Where('UserID =', $UserID)
	               ->Get();
	   foreach($Result->Result() as $User) {
	       return TRUE;
	   }//foreach
	}
	
	public function GetActiveCharacters() {
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('Status >', '0')
                ->OrderBy('Reputation', 'DESC')
                ->Get();
        return $Result;
    }
    
    public function GetInactiveCharacters() {
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('Status <', '1')
                ->Get();
        return $Result;
    }
    
    public function GetNewAccounts() {
        $NewAccounts = array();
        $DataSet = $this->SQL
                ->Select('*')
                ->From('User')
                ->Get();

        foreach($DataSet->Result() as $Account) {
            if ($this->Exists($Account->UserID) === TRUE) {
                //do nothing
            } else {
                $NewAccounts[$Account->UserID] = $Account->Name;                
            }//if
        }//foreach
        
        return $NewAccounts;
    }
    
    public function GetAllCharacters() {
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Get();
        return $Result;
    }
    
    public function GetDecayList() {
        $ThisWeek = date('W');
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('LastRaid <' => ($ThisWeek - 2), 'Status >' => '0'))
                ->Get();
        return $Result;
    }
    
    public function GetWarnList() {
        $ThisWeek = date('W');
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('LastRaid' => ($ThisWeek - 2), 'Status >' => '0'))
                ->Get();
        return $Result;
    }
    
    public function GetDecayNumber() {
        $ThisWeek = date('W');
        $DecaySet = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('LastRaid <' => ($ThisWeek - 2), 'Status >' => '0'))
                ->Get();
        
        $Total = 0;
        foreach($DecaySet->Result() as $Decay) {
            $Total++;
        }//foreach
        return '<span>'.$Total.'</span>';
    }
    
    public function GetReputation($UserID) {
        $DataSet = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('UserID =', $UserID)
                ->Get();
        foreach ($DataSet->Result() as $User) {
            $Reputation = $User->Reputation;
        }
        return $Reputation;
    }
    
    public function GetName($UserID) {
        $Name = '';
        $DataSet = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('UserID =', $UserID)
                ->Get();
        foreach ($DataSet->Result() as $User) {
            $Name = $User->Name;
        }
        return $Name;
    }
    
    public function GetID($Name) {
        $ID = '';
        $DataSet = $this->SQL
                    ->Select('*')
                    ->From('Shadow')
                    ->Where(array('Name' => $Name))
                    ->Get();
        foreach($DataSet->Result() as $UserID) {
            $ID = $UserID->UserID;
        }
        if ($ID === '') {
            $SecondCheck = $this->SQL
                            ->Select('*')
                            ->From('User')
                            ->Where(array('Name' => $Name))
                            ->Get();
            foreach($SecondCheck->Result() as $UserID) {
                $ID = $UserID->UserID;
            }
        }
        return $ID;
    }
    
    public function GetClass($UserID) {
        $DataSet = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('UserID =', $UserID)
                ->Get();
        foreach ($DataSet->Result() as $User) {
            $Class = $User->Class;
        }
        return $Class;
    }
    
    public function GetStatus($UserID) {
        $DataSet = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('UserID = ', $UserID)
                ->Get();
        foreach ($DataSet->Result() as $User) {
            $Status = $User->Status;
        }
        return $Status;
    }
    
    public function GetDecayStatus($UserID) {
        $Message = '<span class="Status">';
        $DataSet = $this->SQL
                    ->Select('*')
                    ->From('Shadow')
                    ->Where('UserID = ', $UserID)
                    ->Get();
        $ThisWeek = date('W');
        $NextDecay = date('M d, Y', strtotime('+1 week'));
        foreach($DataSet->Result() as $User) {
            if ($User->Marks == '0') {
                $Standing = ', in good standing';
            } else if ($User->Marks == '1') {
                $Standing = ', with '.$User->Marks.' no-show';
            } else {
                $Standing = ', with '.$User->Marks.' no-shows';
            }//if
        
            if ($User->LastRaid == ($ThisWeek - 2) && $User->Status === '1') {
                $Message .= 'Active'. $Standing .', and no raid last week. <span class="Orange">Decay: '.$NextDecay.'</span>.';
            } else if ($User->LastRaid < ($ThisWeek - 2) && $User->Status === '1') {
                $Message .= 'Active'. $Standing .', and no recent raids. <span class="Red">Reputation decaying</span>.';
            } else if ($User->Status === '1') {
                $Message .= 'Active'. $Standing .'.';
            }//if
        }//foreach
        $Message .= '</span>';
        
        return $Message;
    }
    
    public function IsDecayReady() {
        $ThisWeek = date('W');
        
        $Status = 'False';
        $DataSet = $this->SQL
                ->Select('*')
                ->From('Decay')
                ->Where('DecayID = ', '0')
                ->Get();
        foreach($DataSet->Result() as $Decay) {
            if ($Decay->LastProcessed < $ThisWeek) {
                $Status = 'True';
            } else { 
                $Status = 'False';
            }
        }
        
        return $Status;
    }
    
    public function ChangeLog($UserID, $Reason = ' ') {
        $File = 'applications/reptastic/logs/history.txt';
        $Reputation = $this->GetReputation($UserID);
        $Date = date('d-M-Y h:iA');
        
        $Message = '['.$Date.']: '.$this->GetName($UserID).'('.$Reputation.')';
        if ($Reason !== '') {
            $Message .= ' '.$Reason.'.
';
        } else {
            $Message .= '.
';
        }//if
        $Log = fopen($File, 'a');
            fwrite($Log, $Message);
            fclose($Log);
    }
    
    public function GetLog($LogName) {
        $File = 'applications/reptastic/logs/'.$LogName.'.txt';
        $Log = fopen($File, 'r');
            $Result = fread($Log, filesize($File));
            fclose($Log);
        
        $LogArray = explode('.', $Result);
        $OrderedArray = array_reverse($LogArray);
        $Echo = '';
        foreach($OrderedArray as $Key => $Value) {
            $Echo .= '<li class="Activity">'.$Value.'</li>';
        }//foreach
        
        return $Echo;        
    }
    
    public function ProcessDecay() {
        $ThisWeek = date('W');
        $DecayMessage = 'Decay processed for: ';
        $DecayTotal = 0;
        
        $DataSet = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('LastRaid <' => ($ThisWeek - 2), 'Status >' => '0'))
                ->Get();
        foreach($DataSet->Result() as $Decay) {
            $Reputation = intval($Decay->Reputation);
            $NewReputation = 0;
            if ($Reputation <= 50) {
                $NewReputation = 50;
            } else {
                $NewReputation = ceil($Reputation * 0.75);
            }//if
            $Message = '-'. floor($Reputation * 0.25) .' Shadow Reputation this week. <br /><small>(25% Decay is processed every Monday following two consistent weeks of inactivity. You can stop decay by completing any guild 25 person raid. Decay automatically stops when your points equal 50.)</small>';
            
            AddActivity($Decay->UserID, 'Decay', $Message, $Decay->UserID, '');
            $this->SQL->Update('Shadow')->Set('Reputation', $NewReputation)->Where(array('UserID' => $Decay->UserID))->Put();
            
            $Change = $Reputation - $NewReputation;
            $this->ChangeLog($Decay->UserID, 'Decay');
            
            $DecayMessage .= $Decay->Name.'; ';
            $DecayTotal++;
        }//foreach
        
        $WarnSet = $this->SQL
                    ->Select('*')
                    ->From('Shadow')
                    ->Where(array('LastRaid' => ($ThisWeek - 2), 'Status >' => '0'))
                    ->Get();
        $Warned = 0;
        $NextDecay = date('M d, Y', strtotime('+1 week'));
        foreach($WarnSet->Result() as $Warn) {
            $WarnMessage = '<strong>You have not lost any reputation.</strong><br />
                            You can prevent decay by attending a raid this week!<br />
                            If you do not attend a raid this week, your reputation decay (-'.floor($Warn->Reputation*0.25).') will be processed on <u>'.$NextDecay.'</u>.';
            AddActivity($Warn->UserID, 'WarnDecay', $WarnMessage, $Warn->UserID, '');
            $Warned++;
        }//foreach
        
        $DecayMessage .= ' Warned('.$Warned.')';
        
        $this->SQL->Update('Decay')->Set('LastProcessed', $ThisWeek)->Where(array('DecayID' => '0'))->Put();
        
        AddActivity(1, 'DecayAnnounce', '', 1, '');
        return $DecayMessage;
    }
    
    public function DefineRaid($DataSet) {
        $Values = 'Your raid has been created.';
        
        $ShadowData = $this->SQL->Select('*')
                            ->From('Shadow')
                            ->Get();
        
        foreach($ShadowData as $Character) {
            $Exists = FALSE;
            foreach($DataSet as $Key => $Value) {
                if ($Key !== "TransientKey" && $Key !== "Start_Raid") {
                    if($Character->UserID === $Value) {
                        $Exists = TRUE;
                    }//if
                }//if
            }//foreach
            
            $Date = date('c');
            
            if ($Exists !== FALSE) {
                $this->SQL->Update('Shadow')
                    ->Set('IsRaiding', '1')
                    ->Set('Tardy', '0')
                    ->Set('Added', $Date)
                    ->Where(array('UserID' => $Character->UserID))
                    ->Put();
            } else {
                $this->SQL->Update('Shadow')
                    ->Set('IsRaiding', '0')
                    ->Set('Tardy', '0')
                    ->Set('Added', NULL)
                    ->Where(array('UserID' => $Character->UserID))
                    ->Put();
            }//if
        }//foreach

        return $Values;
    }
    
    public function PostRaidActivity() {
        $Raiders = array();
        $Cycle = 0;
        $DataSet = $this->SQL->Select('*')
                        ->From('Shadow')
                        ->Where(array('IsRaiding' => '1'))
                        ->Get();
        
        foreach($DataSet->Result() as $Raider) {
            $Raiders[$Cycle] = '<span class="'.$Raider->Class.'">'.$Raider->Name.'</span>';
            $this->SQL->Update('Shadow')
                    ->Set('IsRaiding', '0')
                    ->Where(array('UserID' => $Raider->UserID))
                    ->Put();
            $Cycle++;
        }//foreach
        
        $Message = '<strong>Date:</strong> '.date('D, M d Y').'<br /><strong>Raiders:</strong><br /> ';
        foreach($Raiders as $Raider) {
            $Message .= $Raider;
        }//foreach
        
        AddActivity(1, 'NewRaid', $Message, 1, '');
    }
    
    public function RemoveRaiders($DataSet) {
        $Values = 'Raiders removed.';   
            
        foreach($DataSet as $Key => $Value) {
            if ($Key !== "TransientKey" && $Key !== "Remove") {
                $this->SQL->Update('Shadow')
                    ->Set('IsRaiding', '0')
                    ->Set('Added', NULL)
                    ->Where(array('UserID' => $Value))
                    ->Put();
            }//if
        }//foreach

        return $Values;
    }
    
    public function AddRaiders($DataSet) {
        $Values = 'Raiders added.';   
        $Date = date('c');
        
        foreach($DataSet as $Key => $Value) {
            if ($Key !== "TransientKey" && $Key !== "Add") {
                $this->SQL->Update('Shadow')
                    ->Set('IsRaiding', '1')
                    ->Set('Added', $Date)
                    ->Where(array('UserID' => $Value))
                    ->Put();
            }//if
        }//foreach

        return $Values;
    }
    
    public function AddCharacter($UserID) {
        $Name = '';
        $DataSet = $this->SQL
                    ->Select('*')
                    ->From('User')
                    ->Where(array('UserID' => $UserID))
                    ->Get();
        foreach($DataSet->Result() as $Account) {
            $Name = $Account->Name;
        }//foreach
        $Fields = array('UserID' => $UserID, 'Name' => $Name, 'Status' => '1');
        
        $Success = $this->SQL->Insert('Shadow', $Fields);
        if ($Success) {
            $Message = $Name.' added.';
        } else {
            $Message = 'Error adding account.';
        }//if
        
        return $Message;        
    }
    
    public function SaveTardy($DataSet) {
        $Values = 'Tardy status saved for: ';
        $UserID = $DataSet['UserID'];
        $Tardy = $DataSet['Tardy'];
        $User = '';
        
        $Name = $this->SQL->Select('*')
            ->From('Shadow')
            ->Where(array('UserID' => $UserID))
            ->Get();
        foreach($Name->Result() as $Username) {
            $Message = '';
            $Reputation = $Username->Reputation;
            $User = $Username->Name;
            $Marks = $Username->Marks;
            if ($Tardy === '4') {
                $Values = 'No show status saved for: ';
                if ($Marks === '0') {
                    $Message = 'As your first no-show, this is a warning. No points were lost.';
                } else {
                    $Decay = ceil($Reputation*.2);
                    $Reputation = $Reputation - $Decay;
                    $Message = '-'.$Decay.' Shadow Reputation.';
                }//if
                
                $Marks++;
                $this->SQL->Update('Shadow')
                    ->Set('Marks', $Marks)
                    ->Set('Reputation', $Reputation)
                    ->Set('IsRaiding', '0')
                    ->Where(array('UserID' => $UserID))
                    ->Put();
                
                $this->ChangeLog($UserID, 'Raid missed');
                
                AddActivity($UserID, 'RaidMissed', $Message, $UserID, '');
            }//if
        }//foreach
        
        $Values .= $User.'.';

        $this->SQL->Update('Shadow')
            ->Set('Tardy', $Tardy)
            ->Where(array('UserID' => $UserID))
            ->Put();
        
        return $Values;
    }
    
    public function GetRaid() {
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where('IsRaiding >', '0')
                ->OrderBy('Reputation', 'DESC')
                ->Get();
        return $Result;
    }
    
    public function GetTestRaid() {
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('Status' => '1'))
                ->OrderBy('Reputation', 'DESC')
                ->Get();
        return $Result;
    }
    
    public function IsRaiding($UserID) {
        $IsRaiding = 0;
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('IsRaiding' => '1', 'UserID' => $UserID))
                ->Get();
        foreach($Result->Result() as $User) {
            $IsRaiding = 1;
        }//foreach        
        
        return $IsRaiding;
    }
    
    public function GetNotRaiding() {
        $Result = $this->SQL
                ->Select('*')
                ->From('Shadow')
                ->Where(array('IsRaiding' => '0', 'Status' => '1'))
                ->OrderBy('Reputation', 'DESC')
                ->Get();
        return $Result;
    }
    
    public function GetTardy($UserID) {        
        $TardyResult = '';
        $Result = $this->SQL
                    ->Select('Tardy')
                    ->From('Shadow')
                    ->Where('UserID =', $UserID)
                    ->Get();
        foreach($Result->Result() as $User) {
            $Tardy = $User->Tardy;
            switch($Tardy) {
                case 0:
                    $TardyResult = 'On Time';
                    break;
                case 1:
                    $TardyResult = '< 15 Minutes';
                    break;
                case 2:
                    $TardyResult = '< 30 Minutes';
                    break;
                case 3:
                    $TardyResult = '< 2 Hours';
                    break;
                case 4:
                    $TardyResult = 'No show';
                    break;
                    
            }
        }
        return $TardyResult;
    }
    
    public function AwardPoints($Points, $Reason) {
        $Result = $this->SQL
                    ->Select('*')
                    ->From('Shadow')
                    ->Where(array('IsRaiding' => '1', 'Status' => '1'))
                    ->Get();
                    
        if($Reason === 'Progression') {
            $ReputationArray = array();
            
            foreach($Result->Result() as $User) {
                $ReputationArray[$User->UserID] = $User->Reputation;
            }//foreach
            
            $Maximum = max($ReputationArray);
            $Percent = $Points * 0.01;
            $Points = ceil($Maximum * $Percent);
        }//if
    
        $Values = $Reason.' points awarded. +'.$Points.' Shadow Reputation.';
        
        foreach($Result->Result() as $User) {
            $Reputation = 0;
            $Update = $Reason.' points awarded. ';
            $Penalty = 0;
            $LastRaid = $User->LastRaid;
            
            if ($User->Tardy > 0 && $Reason === 'Finish') {
                $Penalize = $User->Tardy;
                switch($Penalize) {
                    case 1:
                        $Penalty = 30;
                        break;
                    case 2:
                        $Penalty = 50;
                        break;
                    case 3:
                        $Penalty = 55;
                        break;
                    case 4:
                        $Penalty = 55;
                        break;
                }//switch
                $Reputation = $User->Reputation + ($Points - $Penalty);
            } else {
                $Reputation = $User->Reputation + $Points;
            }//endif
            
            if ($Reason === 'Finish')
                $LastRaid = date('W');
            $Update .= '+'.($Points - $Penalty).' Shadow Reputation.';
            
            $this->SQL->Update('Shadow')
                    ->Set('Reputation', $Reputation)
                    ->Set('LastRaid', $LastRaid)
                    ->Where(array('UserID' => $User->UserID))
                    ->Put();
                    
            
            $this->ChangeLog($User->UserID, $Reason);

            AddActivity($User->UserID, 'RaidReputation', $Update, $User->UserID, '');
        }//foreach
        
        if ($Reason === 'Finish')
            $this->PostRaidActivity();

        return $Values;
    }
    
    public function GetLootHistory($Filter = '') {
        if ($Filter !== '') {
            $Name = $this->GetID($Filter);
            $DataSet = $this->SQL->Select('*')
                            ->From('Loot')
                            ->Where(array('UserID' => $Name))
                            ->OrderBy('EntryID', 'DESC')
                            ->Get();
        } else {
            $DataSet = $this->SQL->Select('*')
                            ->From('Loot')
                            ->OrderBy('EntryID', 'DESC')
                            ->Get();
        }//if
        
        return $DataSet;
    }
    
    public function Loot($DataSet, $Type) { 
        $Item = '';
           
        //Delete old rolls
        $RollDatabase = $this->SQL->Select('*')
                            ->From('Rolls')
                            ->Get();
        foreach($RollDatabase->Result() as $Entry) {
            $this->SQL->Delete('Rolls', array('UserID' => $Entry->UserID));
        }//foreach
    
        //Save characters
        foreach($DataSet as $Key => $Value) {
            if ($Key !== "TransientKey" && $Key !== "Off_Spec" && $Key !== "MAIN_SPEC") {
                if ($Key === "Item") {
                    $Item = $Value;
                } else {
                    if ($Value) {
                        $Fields = array('UserID' => $Value, 'ItemName' => $Item);
                        $Success = $this->SQL->Insert('Rolls', $Fields);
                    }//if
                }//if
            }//if
        }//foreach
        
        //Roll dice
        $this->Roll($Type);
    }
    
    public function Roll($Type) {
        
        $Rolls = $this->SQL->Select('*')
                    ->From('Rolls')
                    ->Get();
        $Users = array();
        $RollWeight = array();
        $RollList = array();
        
        foreach($Rolls->Result() as $Roll) {
            $Users[$Roll->UserID] = $Roll->UserID;
        }//foreach
        
        foreach($Users as $Key => $Value) {
            $ReputationData = $this->SQL->Select('*')
                                ->From('Shadow')
                                ->Where(array('UserID' => $Key))
                                ->Get();
            foreach($ReputationData->Result() as $User) {
                $Users[$Key] = $User->Reputation;
            }//foreach
        }//foreach
        
        if ($Users) {
            $Maximum = max($Users);
            $Rollers = count($Users);
            $Scale = 0;
            
            $Log = 1 + (1/($Rollers * 9.51));
            
            foreach ($Users as $Key => $Value) {
                $Difference = $Maximum - $Value;
                
                if ($Difference > 0) {
                    if ($Difference < 5) {
                        if ($Difference === 1) {
                            $Offset = 19;
                        } else if ($Difference === 4) {
                            $Offset = 73;
                        } else {
                            $Offset = 90/($Difference*(2.4616 - ($Difference * 0.6387)));
                        }//if
                        $RollWeight[$Key] = 1/((log10(($Difference+$Offset)/$Offset)) / log10($Log));
                    } else {
                        $RollWeight[$Key] = 1/((log10(($Difference+90)/90)) / log10($Log));
                    }//if
                } else {
                    $RollWeight[$Key] = 1;
                }//if
                $this->SQL->Update('Rolls')
                        ->Set('Weight', $RollWeight[$Key])
                        ->Set('Roll', '0')
                        ->Set('Reputation', $Value)
                        ->Where(array('UserID' => $Key))
                        ->Put();                
                
                $Scale = $Scale + $RollWeight[$Key];
            }//foreach
            
            $Iteration = 0;
            
            foreach($Users as $Key => $Value) {
                $Weight = ceil(5000 * ($RollWeight[$Key]/$Scale));
                $this->SQL->Update('Rolls')
                        ->Set('Chance', ($Weight/50))
                        ->Where(array('UserID' => $Key))
                        ->Put();
                
                for($B=0; $B<$Weight; $B++) {
                    $List[$Iteration] = $Key;
                    $Iteration++;
                }//for
            }//foreach
            
            $ChooseWinner = rand(1, $Iteration-1);
            $Winner = $List[$ChooseWinner];
            $Cost = $Users[$Winner] * $Type;
            
            $Result = $this->SQL->Update('Rolls')
                ->Set('Roll', '1')
                ->Set('Cost', $Cost)
                ->Where(array('UserID' => $Winner))
                ->Put();
            
            $Name = $this->GetName($Winner);
            $Message = $Name.' is the winner!';
            
            return $Message;
        }//if
    }
    
    public function GetRolls() {
        $Result = $this->SQL->Select('*')
                    ->From('Rolls')
                    ->OrderBy('Reputation', 'DESC')
                    ->Get();
        return $Result;
    }
    
    public function SaveLoot($ItemLink) {
        $Date = date('l, F j');
        $Rollers = '';
        
        $DataSet = $this->SQL->Select('*')
                    ->From('Rolls')
                    ->Get();
        $Iteration = '';
        foreach($DataSet->Result() as $Roller) {
            if ($Roller->Roll !== '1') {
                $Rollers .= $Iteration.$this->GetName($Roller->UserID);
                $Iteration = ', ';
            }//if
        }//foreach
    
    
        $Result = $this->SQL->Select('*')
                    ->From('Rolls')
                    ->Where(array('Roll' => '1'))
                    ->Get();
        
        foreach($Result->Result() as $Winner) {
            $Name = $this->GetName($Winner->UserID);
            $Reputation = ($this->GetReputation($Winner->UserID)) - $Winner->Cost;
            if ($Winner->Cost === '0') {
                $Cost = '75 Gold';
            } else {
                $Cost = $Winner->Cost;
            }//if
            $Fields = array('UserID' => $Winner->UserID, 'ItemName' => $Winner->ItemName, 'RollDate' => $Date, 'ItemLink' => $ItemLink, 'CharacterName' => $Name, 'Rollers' => $Rollers, 'ItemCost' => $Cost, 'Tier' => '10');
            $this->SQL->Insert('Loot', $Fields);
            
            $this->SQL->Update('Shadow')
                ->Set('Reputation', $Reputation)
                ->Where(array('UserID' => $Winner->UserID))
                ->Put();
                
            $this->ChangeLog($Winner->UserID, 'Loot won');
            
            $Update = 'Item: <a href="'.$ItemLink.'" target="_blank">'.$Winner->ItemName.'</a> &nbsp; &nbsp; Cost: <u>'.$Cost.'</u>.';
            AddActivity($Winner->UserID, 'WonItem', $Update, $Winner->UserID, '');
        }//foreach
    
        return $Result;
    }    
    
    public function TestLoot($DataSet) {
        //Start table
        $Math = '<div class="Message Warning">';
        $Message = '<ul class="Activities">';
    
        //Save characters
        $Users = array();
        foreach($DataSet as $Key => $Value) {
            if ($Key !== "TransientKey" && $Key !== "Roll" && $Key !== "Re-roll") {
                if ($Value) {
                    $Users[$Value] = $Value;
                }//if
            }//if
        }//foreach
        
        //Roll dice
        $RollWeight = array();
        $RollList = array();
        
        foreach($Users as $Key => $Value) {
            $ReputationData = $this->SQL->Select('*')
                                ->From('Shadow')
                                ->Where(array('UserID' => $Key))
                                ->Get();
            foreach($ReputationData->Result() as $User) {
                $Users[$Key] = $User->Reputation;
            }//foreach
        }//foreach
        
        if ($Users) {
            $Maximum = max($Users);
            $Rollers = count($Users);
            $Scale = 0;
                $Math .= '<p>Max: '.$Maximum.'; Rollers: '.$Rollers.'; ';
            
            $Log = 1 + (1/($Rollers * 9.51));
                $Math .= 'Log: '.$Log.';</p>';
            
            foreach ($Users as $Key => $Value) {
                $Difference = $Maximum - $Value;
                    $Math .= '<p>Difference('.$this->GetName($Key).'): '.$Difference.'; ';
                
                if ($Difference > 0) {
                    if ($Difference < 5) {
                        if ($Difference === 1) {
                            $Offset = 19;
                        } else if ($Difference === 4) {
                            $Offset = 73;
                        } else {
                            $Offset = 90/($Difference*(2.4616 - ($Difference * 0.6387)));
                        }//if
                        $RollWeight[$Key] = 1/((log10(($Difference+$Offset)/$Offset)) / log10($Log));
                    } else {
                        $RollWeight[$Key] = 1/((log10(($Difference+90)/90)) / log10($Log));
                        $Math .= 'Weight > 0 :: 1/('.log10(($Difference+90)/90).' / '.log10($Log).') == '.$RollWeight[$Key].'</p>';
                    }//if
                } else {
                    $RollWeight[$Key] = 1;
                    $Math .= 'Weight !> 0;</p>';
                }//if               
                
                $Scale = $Scale + $RollWeight[$Key];
            }//foreach
            
            $Iteration = 1;
            $TotalPercent = 0;
            
            foreach($Users as $Key => $Value) {
                $Weight = ceil(5000 * ($RollWeight[$Key]/$Scale));
                $Chance = $Weight/50;
                if ($RollWeight[$Key] === 1) {
                    $Favored = ' Winner Favored';
                } else {
                    $Favored = '';
                }//if
                
                $Message .= '<li class="Activity'.$Favored.'">
                                '.$Iteration.'. <strong class="Title">'.$this->GetName($Key).'</strong>
                                <div class="Meta Raider">
                                    <strong class="Badge '.$this->GetClass($Key).'">'.$this->GetReputation($Key).'</strong> &nbsp; &nbsp; Weight: <strong>'.$RollWeight[$Key].'</strong> &nbsp; &nbsp; <u>'.round($Chance, 2).'%</u>
                                </div>
                            </li>';
                $Iteration++;
                $TotalPercent = $TotalPercent + round($Chance, 2);
            }//foreach
            
            $Message .= '</ul>';
            $Math .= '</div>';
            
            return $Message;
        }//if
        
    }
    
    public function SignedUp($Day, $UserID) {
        $Name = 'No name';
        $SignedUp = 'Error';
        
        $File = 'http://www.wowarmory.com/feeds/private/calendar.ics?cn=Smark&r=Emerald%20Dream&token=3e7b4db22a044906acfe9ecbb297d6c9&locale=en_US&cache='.date('c');
        $Calendar = new SG_iCal($File);
        $Exists = empty($Calendar->getEvents);
        
        switch($Day) {
            case 'Tuesday':
                $Raid = date('Ymd', strtotime('next wednesday'));
                break;
            case 'Wednesday':
                $Raid = date('Ymd', strtotime('next thursday'));
                break;
        }//switch
        
        //Get Name
        $DataSet = $this->SQL
                    ->Select('*')
                    ->From('Shadow')
                    ->Where(array('UserID' => $UserID, 'Status' => '1'))
                    ->Get();
        foreach($DataSet->Result() as $User) {
            if ($User->Name !== '') {
                $Name = ucwords($User->Name);
            }//if
        }//foreach
            foreach($Calendar->getEvents() as $Event) {
                if ($Event->getStart() == $Raid) {
                    $SignedUp = 'False';
                
                    $Description = $Event->getDescription();
                    $Character = strpos($Description, $Name);
                
                    if ($Character !== FALSE)
                        $SignedUp = 'True';
                }//if
            }//foreach
        
        return $SignedUp;
    }
    
    public function SignUpBonus() {        
        $File = 'http://www.wowarmory.com/feeds/private/calendar.ics?cn=Smark&r=Emerald%20Dream&token=3e7b4db22a044906acfe9ecbb297d6c9&locale=en_US&cache='.date('c');
        $Calendar = new SG_iCal($File);
        $Exists = empty($Calendar->getEvents);
        
        //Get Name
        $DataSet = $this->SQL
                    ->Select('*')
                    ->From('Shadow')
                    ->Where(array('Status' => '1'))
                    ->Get();
                    
        $Today = date('l');
        if ($Today === 'Thursday') {
            foreach($DataSet->Result() as $User) {
                $this->SQL->Update('Shadow')
                    ->Set('SignedUp', '0')
                    ->Where(array('UserID' => $User->UserID))
                    ->Put();
            }//foreach    
        }//if
        
        //if(!$Exists) {
            foreach($DataSet->Result() as $User) {
                $UserID = $User->UserID;
                $Name = ucwords($User->Name);
                $Character = FALSE;
                $SignUps = 0;
            
                $RaidTuesday = date('Ymd', strtotime('next wednesday'));
                $RaidWednesday = date('Ymd', strtotime('next thursday'));
            
                foreach($Calendar->getEvents() as $Event) {
                    if ($Event->getStart() == $RaidTuesday || $Event->getStart() == $RaidWednesday) {
                        $Description = $Event->getDescription();
                        $Character = strpos($Description, $Name);
                        $SignUps++;
                    }//if
                }//foreach
            
                if ($Character !== FALSE) {
                    $this->SQL->Update('Shadow')
                        ->Set('SignedUp', $SignUps)
                        ->Where(array('UserID' => $UserID))
                        ->Put();
                }//if
            }//foreach
            $Status = 'Signups processed.';
        //} else {
        //    $Status = 'Error connecting to Armory.';
        //}//if
        
        return $Status;
    }
    
    public function IsSignedUp($UserID) {
        $Result = '';
        $DataSet = $this->SQL->Select('*')
                    ->From('Shadow')
                    ->Where(array('UserID' => $UserID))
                    ->Get();
        foreach($DataSet->Result() as $Character) {
            $Result = intval($Character->SignedUp);
        }//foreach
        
        return $Result;
    }
}
?>