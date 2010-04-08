<?php if (!defined('APPLICATION')) exit(); ?>
   <description><?php echo Format::Text($this->Head->Title()); ?></description>
   <language><?php echo Gdn::Config('Garden.Locale', 'en-US'); ?></language>
   <atom:link href="<?php echo Url('/rss/discussions'); ?>" rel="self" type="application/rss+xml" />
<?php
foreach ($this->DiscussionData->Result() as $Discussion) {
   ?>
   <item>
      <title><?php echo Format::Text($Discussion->Name); ?></title>
      <link><?php echo Url('/discussion/'.$Discussion->DiscussionID.'/'.Format::Url($Discussion->Name), TRUE); ?></link>
      <pubDate><?php echo date(DATE_RSS, Format::ToTimeStamp($Discussion->DateInserted)); ?></pubDate>
      <dc:creator><?php echo Format::Text($Discussion->FirstName); ?></dc:creator>
      <guid isPermaLink="false"><?php echo $Discussion->DiscussionID . '@' . Url('/discussions'); ?></guid>
      <description><![CDATA[<?php echo Format::To($Discussion->FirstComment, $Discussion->FirstCommentFormat); ?>]]></description>
   </item>
   <?php
}