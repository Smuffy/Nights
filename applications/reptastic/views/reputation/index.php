<?php if (!defined('APPLICATION')) exit(); ?>

<?php
    $Session = Gdn::Session();
    if($this->ShadowModel->IsSignedUp($Session->UserID) > 0) {
        $Class = '';
    } else {
        $Class = ' None';
    }//if
    
    $SignUp = '<span class="SignUp'.$Class.'">Raids: '.$this->ShadowModel->IsSignedUp($Session->UserID).' signups</span>';
?>

<h1>Shadow Reputation 
<?php
    if($this->ShadowModel->Exists($Session->UserID)) {   
          if ($this->ShadowModel->GetStatus($Session->UserID) === '1') { 
            echo $SignUp; 
          }//if
     }//if
?>
</h1>
<div id="Status">
    <p>Below is an up-to-date list of Shadow Reputation by <em>main character</em>. You can sort by armor type using the tabs.</p>
</div>
<ul class="Tabs">
    <li class="Active"><a href="/reputation">All Characters</a></li>
    <li>&raquo;</li>
    <li><a href="/reputation/cloth">Cloth</a></li>
    <li><a href="/reputation/leather">Leather</a></li>
    <li><a href="/reputation/mail">Mail</a></li>
    <li><a href="/reputation/plate">Plate</a></li>
    <li>&raquo;</li>
    <li><a href="/reputation/conqueror">Conqueror</a></li>
    <li><a href="/reputation/protector">Protector</a></li>
    <li><a href="/reputation/vanquisher">Vanquisher</a></li>
</ul>

<ul class="Reputation">
<?php $DataSet = $this->ShadowModel->GetActiveCharacters(); foreach($DataSet->Result() as $Shadow) { 
    
    $ThisWeek = date('W');
    $LastRaid = $Shadow->LastRaid;
    
    if($LastRaid == $ThisWeek) {
        $LastRaid = 'This week';    
    } else if ($LastRaid == ($ThisWeek - 1)) {
        $LastRaid = '<span class="Yellow">Last week</span>';
    } else if ($LastRaid == ($ThisWeek - 2)) {
        $LastRaid = '<span class="Orange">Two weeks ago</span>';
    } else {
        $LastRaid = '<span class="Red">Decaying</span>';
    }?>
    <li class="<?php echo $Shadow->Class; ?><?php if($Shadow->UserID === $Session->UserID) { echo ' MyCharacter'; } ?>">
        <h3><a href="/profile/<?php echo $Shadow->UserID; ?>/<?php echo $Shadow->Name; ?>"><?php echo $Shadow->Name; ?></a></h3>
        <p><?php echo $Shadow->Reputation; ?></p>
        <small><?php echo $LastRaid; ?></small>
    </li>        
<?php } ?>
</ul>