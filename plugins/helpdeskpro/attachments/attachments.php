<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2010 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;

class plgHelpdeskProAttachments extends JPlugin
{	
	public function __construct(& $subject, $config)
	{		
		parent::__construct($subject, $config);				
	}
	
	function onViewTicket($row) {
		$db = JFactory::getDbo() ;
		$sql = 'SELECT attachments, original_filenames FROM #__helpdeskpro_tickets WHERE id='.$row->id ;
		$db->setQuery($sql);
		$rowAttachments = $db->loadObjectList() ;	
		//Select attachments from comments
		$sql = 'SELECT attachments, original_filenames FROM #__helpdeskpro_messages WHERE ticket_id='.$row->id ;
		$db->setQuery($sql);
		$rowAttachments = array_merge($rowAttachments, $db->loadObjectList()) ;					
		if (count($rowAttachments)) {
			ob_start();	
			?>
				<tr>
					<th colspan="2"><?php echo JText::_('HDP_TICKET_ATTACHMENTS'); ?></th>
				</tr>
			<?php							
			foreach ($rowAttachments as $rowAttachment) {
				if ($rowAttachment->original_filenames) {
					$originalFileNames = explode('|', $rowAttachment->original_filenames);
					$attachments = explode('|', $rowAttachment->attachments);
					for ($i = 0, $n = count($originalFileNames) ; $i < $n ; $i++) {
						$filePath = JPATH_ROOT.'/media/com_helpdeskpro/attachments/'.$attachments[$i] ;
						if (file_exists($filePath)) {
						?>
							<tr>									
								<td colspan="2">
									<a href="<?php echo JRoute::_('index.php?option=com_helpdeskpro&task=ticket.download_attachment&filename='.$attachments[$i].'&original_filename='.$originalFileNames[$i]); ?>"><?php echo $originalFileNames[$i]; ?></a> (<?php echo HelpdeskProHelper::getSize($filePath) ; ?>)
								</td>															
							</tr>
						<?php
						}													
					}						
				}																												
			}					
			$text = ob_get_contents() ;			
			ob_end_clean();
			return $text ;			
		} else {
			return null ;
		} 
				
	}
}	