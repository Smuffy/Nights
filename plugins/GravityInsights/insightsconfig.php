<?php
/** array of forum id's that you do not wish to transmit to insights
; they could be your private forums or your admin only forums that you don't wish to track
place one id for each element in the array
EXAMPLE:
$hidden_forums = array(23, 45, 498);
would not send data for categories 23, 45 and 498
*/
$insights_config['hidden_forums'] = array();

/**
 * The line below holds a reference to your unique siteguid. If you downloaded this plugin from insights.gravity.com
 * then the siteguid will be filled in for you, if not then once you get your siteguid from insights you can 
 * fill it in on the line below.
*/
$insights_config['site_guid'] = 'bebd36152cc29564abc10927eb2c7e65';