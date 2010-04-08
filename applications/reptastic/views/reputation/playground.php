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

<h1><a href="/reputation">Shadow Reputation</a> \ Reputation Playground
<?php
    if($this->ShadowModel->Exists($Session->UserID)) {   
          if ($this->ShadowModel->GetStatus($Session->UserID) === '1') { 
            echo $SignUp; 
          }//if
     }//if
?>
</h1>
<p>You can use this tool to play around with various roll situations. Choose whom you wish to roll below and then click "Roll". This tool does not perform a roll, it only displays the chances of winning.</p>
<br />
<?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
?>
<?php if ($this->Winner !== '') { ?>
    <br />
    <h2>Results <span class="Reset"><a href="/reputation/playground">Reset</a></span></h2>
    <?php echo $this->Winner; ?>
    <div class="clearfix"></div>
    <br />
<?php } ?>
<h2>Choose Rollers</h2>
<ul class="Activities">
<?php $DataSet = $this->ShadowModel->GetTestRaid(); foreach($DataSet->Result() as $Shadow) { ?>
    <li class="Activity List Classes">
        <h3 class="<?php echo $Shadow->Class; ?>"><?php echo $this->Form->CheckBox('User'.$Shadow->UserID, $Shadow->Name.'<small><strong>'.$Shadow->Reputation.'</strong> Reputation</small>', array('value' => $Shadow->UserID)); ?></h3>
    </li>        
<?php } ?>
</ul>
<div class="clearfix"></div>
<div class="Center">
<?php
    echo $this->Form->Close('Roll');
?>
</div>
<div class="clearfix"></div>