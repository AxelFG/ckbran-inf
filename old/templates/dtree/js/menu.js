/**
* @version 1.0.0
* @author Daniel Ecer
* @package exmenu
* @copyright (C) 2005-2006 Daniel Ecer (de.siteof.de)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
var menuTemplate_dtree_counter		= 0;
var menuTemplate_dtree_currentId		= -1;

function menuTemplate_dtree_add(d, elementRootId, label, href, isCurrent, isFolder) {
	var id	= menuTemplate_dtree_counter++;
	if (isCurrent) {
		menuTemplate_dtree_currentId		= id;
	}
	if (typeof(href) == 'undefined') {
		href		= '';
	}
	d.add(id, elementRootId, label, href);
	return id;
}

function menuTemplate_dtree_autoAdd(d, elementRootId, tempObject, isFolder) {
	var id	= -1;
	if (tempObject.label != '!none') {
		id	= menuTemplate_dtree_add(d, elementRootId, tempObject.label, tempObject.href, tempObject.isCurrent, isFolder);
	}
	tempObject.label = '!none';
	tempObject.href	= '';
	tempObject.isCurrent = false;
	return id;
}

function menuTemplate_dtree_findElements(elementRoot, elementRootId, d, classSuffix, tempObject) {
	if (typeof(tempObject) != 'object') {
		tempObject	= {
			label : '!none',
			href : '',
			isCurrent : false
		};
	}
	/*
	var tempLabel = '!none';
	var tempHref = '';
	var tempIsCurrent = false;
	*/
	for (var i=0; i < elementRoot.childNodes.length; i++) {
		var node = elementRoot.childNodes[i];
		if ((node.nodeName != "UL") && (node.nodeName != "#text")) {
			menuTemplate_dtree_autoAdd(d, elementRootId, tempObject);
		}
		if ((node.nodeName == "A") || (node.nodeName == "SPAN")) {
			var t		= '' + node.innerHTML;
			var t2		= t.toLowerCase();
			if ((node.nodeName == "A") || ((t2.indexOf('<a') < 0) && (t2.indexOf('<span') < 0))) {
				tempObject.label		= t;
				tempObject.href		= node.href;
				tempObject.isCurrent = ((node.id == ('active_menu' + classSuffix)) ||
					(node.className == ('mainlevel_current' + classSuffix)) ||
					(node.className == ('sublevel_current' + classSuffix)));
			} else {
				menuTemplate_dtree_findElements(node, elementRootId, d, classSuffix, tempObject);
			}
		} else if (node.nodeName == "UL") {
			var id	= menuTemplate_dtree_add(d, elementRootId,
				(tempObject.label != '!none' ? tempObject.label : ''),
				tempObject.href, tempObject.isCurrent, true);
			tempObject.label		= '!none';
			tempObject.href = '';
			tempObject.isCurrent = false;
			menuTemplate_dtree_findElements(node, id, d, classSuffix, tempObject);
			menuTemplate_dtree_autoAdd(d, id, tempObject);
		} else {
			menuTemplate_dtree_findElements(node, elementRootId, d, classSuffix, tempObject);
		}
	}
	if ((elementRootId < 0) && (tempObject.label != '!none')) {
		menuTemplate_dtree_autoAdd(d, elementRootId, tempObject);
	}
}

function menuTemplate_dtree_show(id, classSuffix, menuTemplateHome) {
	var obj = document.getElementById(id);
	if (!obj) {
		alert('element not found');
		return false;
	}
	var varName = ('' + id).replace(new RegExp('[.\-]', 'g'), '_'); + '_dtree';
	var d = new dTree(varName);
	eval(varName + ' = d');
	var imagePath		= menuTemplateHome + '/images/';
	d.icon.root			= imagePath + "base.gif";
	d.icon.folder		= imagePath + "folder.gif";
	d.icon.folderOpen	= imagePath + "folderopen.gif";
	d.icon.node			= imagePath + "page.gif";
	d.icon.empty			= imagePath + "empty.gif";
	d.icon.line			= imagePath + "line.gif";
	d.icon.join			= imagePath + "join.gif";
	d.icon.joinBottom	= imagePath + "joinbottom.gif";
	d.icon.plus			= imagePath + "plus.gif";
	d.icon.plusBottom	= imagePath + "plusbottom.gif";
	d.icon.minus			= imagePath + "minus.gif";
	d.icon.minusBottom	= imagePath + "minusbottom.gif";
	d.icon.nlPlus		= imagePath + "nolines_plus.gif";
	d.icon.nlMinus		= imagePath + "nolines_minus.gif";
	
	elementRoot			= obj;
	elementRootId		= -1;

	menuTemplate_dtree_findElements(obj, elementRootId, d, classSuffix);

	document.write(d + '<style>\n#' + id + ' { display: none; }\n</style>');

	if (menuTemplate_dtree_currentId >= 0) {
		d.openTo(menuTemplate_dtree_currentId, true);
	}
	return true;
}
