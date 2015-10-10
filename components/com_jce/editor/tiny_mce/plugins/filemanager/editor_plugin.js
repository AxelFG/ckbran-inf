/*  
 * File Manager                 2.0.11
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    16 May 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
(function(){tinymce.create('tinymce.plugins.FileManager',{init:function(ed,url){ed.addCommand('mceFileManager',function(){var e=ed.selection.getNode();ed.windowManager.open({file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=filemanager',width:760+ed.getLang('filemanager.delta_width',0),height:650+ed.getLang('filemanager.delta_height',0),inline:1,popup_css:false},{plugin_url:url});});function isFile(n){return n&&n.nodeName=='A'&&/(jce|wf)_file/.test(n.className);}
ed.addButton('filemanager',{title:'filemanager.desc',cmd:'mceFileManager',image:url+'/img/filemanager.png'});ed.onNodeChange.add(function(ed,cm,n,co){if((n&&n.nodeName=='IMG'||n.nodeName=='SPAN')&&/(jce|wf)_/i.test(ed.dom.getAttrib(n,'class'))){n=ed.dom.getParent(n,'A');}
cm.setActive('filemanager',co&&isFile(n));if(n&&isFile(n)){cm.setActive('filemanager',true);}});ed.onInit.add(function(){if(ed&&ed.plugins.contextmenu){ed.plugins.contextmenu.onContextMenu.add(function(th,m,e){m.add({title:'filemanager.desc',icon_src:url+'/img/filemanager.png',cmd:'mceFileManager'});});}});},getInfo:function(){return{longname:'File Manager',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net/index.php?option=com_content&amp;view=article&amp;task=findkey&amp;tmpl=component&amp;lang=en&amp;keyref=filemanager.about',version:'2.0.11'};}});tinymce.PluginManager.add('filemanager',tinymce.plugins.FileManager);})();