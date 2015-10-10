<?php
/**
 * @version		$Id: default.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

			<div id="cpanel">
                            <div style=" overflow: hidden;">
			<div style="float:left; width:110px; text-align: center; margin-right:10px;">
                                <div class="icon">
                                    <a href="index.php?option=com_fieldsattach&view=fieldsattachgroups">
                                        <img src="components/com_fieldsattach/images/groups.png" alt="Groups"  />
                                        <span style="text-transform: uppercase; color:#000;"><?php echo JText::_( 'Groups' );?></span>
                                    </a>
                                </div>
                        </div>
                        <div style="float:left; width:110px; text-align: center;margin-right:10px;">
                                <div class="icon">
                                    <a href="index.php?option=com_fieldsattach&view=fieldsattachunidades">
                                        <img src="components/com_fieldsattach/images/units.png" alt="Fields"  />
                                        <span style="text-transform: uppercase; color:#000;"><?php echo JText::_( 'Fields' );?></span>
                                    </a>
                                </div>
                        </div>
                        <div style="float:left; width:110px; text-align: center;margin-right:10px;">
                            <div class="icon">
                                <a href="index.php?option=com_fieldsattach&view=fieldsattachbackup">
                                    <img src="components/com_fieldsattach/images/backup.png" alt="Backup"  />
                                    <span style="text-transform: uppercase; color:#000;"><?php echo JText::_( 'Backup' );?></span>
                                </a>
                            </div>
                        </div>
                        <div style="float:left; width:110px; text-align: center;margin-right:10px;">
                                <div class="icon">
                                    <a href="index.php?option=com_fieldsattach&view=fieldsattachdisplay">
                                        <img src="components/com_fieldsattach/images/help.png" alt="Help"  />
                                        <span style="text-transform: uppercase; color:#000;"><?php echo JText::_( 'FrontEnd display' );?></span>
                                    </a>
                                </div>
                        </div>
                             
</div>
							
