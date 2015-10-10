/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */

(function($){

    //define the defaults for the plugin and how to call it
    $.fn.DropdownMaxiMenu = function(options){
        //set default options
        var defaults = {
            fxtransition : 'linear',
            fxduration : 500,
            menuID : 'maximenuck',
            testoverflow : '0',
            orientation : 'horizontal',
            behavior : 'mouseover',
            opentype : 'open',
            direction : 'normal',
            directionoffset1 : '30',
            directionoffset2 : '30',
            dureeIn : 0,
            dureeOut : 500,
            ismobile : false,
            menuposition : '0',
            showactivesubitems : '0'
        };

        //call in the default otions
        var options = $.extend(defaults, options);
        var maximenuObj = this;

        //act upon the element that is passed into the design
        return maximenuObj.each(function(options){

            var fxtransition = defaults.fxtransition;
            var fxduration = defaults.fxduration;
            var dureeOut = defaults.dureeOut;
            var dureeIn = defaults.dureeIn;
            // var useOpacity = defaults.useOpacity;
            var menuID = defaults.menuID;
            var orientation = defaults.orientation;
            var behavior = defaults.behavior;
            var opentype = defaults.opentype;
            var fxdirection = defaults.fxdirection;
            var directionoffset1 = defaults.directionoffset1;
            var directionoffset2 = defaults.directionoffset2;
            var ismobile = defaults.ismobile;
            var showactivesubitems = defaults.showactivesubitems;
            var testoverflow = defaults.testoverflow;
            var transitiontype = 0;
            var status = new Array();

            maximenuInit();
            if (defaults.menuposition == 'topfixed') {
                var menuy = $(this).offset().top;
                $(window).bind('scroll',function(){
                    if ($(window).scrollTop() > menuy) {
                        maximenuObj.addClass('maximenufixed');
                    } else {
                        maximenuObj.removeClass('maximenufixed');
                    }   
                });
            } else if (defaults.menuposition == 'bottomfixed') {
                $(this).addClass('maximenufixed').find('ul.maximenuck').css('position','static');
            }        

            function openMaximenuck(el) {
                submenu = $(el.submenu); //alert(el.data('status'));
                if(status[el.data('level')] == 'showing'
                    || el.data('status') == 'opened'
                    || (status[el.data('level')-1] == 'showing' && opentype == 'drop')) return; //alert('okk');
                submenu.stop(true, true);
                submenu.css('left','auto');
                if (opentype != 'noeffect') status[el.data('level')] = 'showing';

                switch (opentype) {
                    case 'noeffect':
                        status[el.data('level')] = '';
                        el.data('status','opened');
                        break;
                    case 'slide':
                        slideconteneur = $('.maximenuck2',el);
                        if (el.hasClass('level1') && orientation == 'horizontal') {
                            slideconteneur.css('marginTop', -el.submenuHeight);
                            slideconteneur.animate({
                                marginTop: 0
                            },{
                                duration: fxduration,
                                queue: false,
                                easing: fxtransition,
                                complete: function() {
                                    status[el.data('level')] = '';
                                    submenu.css('overflow', 'visible');
                                    el.data('status','opened');
                                }
                            });
                            submenu.animate({
                                height: el.submenuHeight
                            },{
                                duration: fxduration,
                                queue: false,
                                easing: fxtransition,
                                complete: function() {
                                    status[el.data('level')] = '';
                                    submenu.css('overflow', 'visible');
                                    el.data('status','opened');
                                }
                            });
                        } else {
                            slideconteneur.css('marginLeft', -el.submenuHeight);
                            slideconteneur.animate({
                                marginLeft: 0
                            },{
                                duration: fxduration,
                                queue: false,
                                easing: fxtransition,
                                complete: function() {
                                    status[el.data('level')] = '';
                                    submenu.css('overflow', 'visible');
                                    el.data('status','opened');
                                }
                            });
                            submenu.animate({
                                width: el.submenuWidth
                            },{
                                duration: fxduration,
                                queue: false,
                                easing: fxtransition,
                                complete: function() {
                                    status[el.data('level')] = '';
                                    submenu.css('overflow', 'visible');
                                    el.data('status','opened');
                                }
                            });
                        }
                        break;
                    case 'show':
                        submenu.show(fxduration,fxtransition, {
                            complete: function() {
                                status[el.data('level')] = '';
                                el.data('status','opened');
                            }
                        });
                        break;
                    case 'fade':
                        submenu.fadeIn(fxduration, {
                            complete: function() {
                                status[el.data('level')] = '';
                                el.data('status','opened');
                            }
                        });
                        break;
                    case 'scale':
                        submenu.show("scale",{
                            duration: fxduration,
                            easing: fxtransition,
                            complete: function() {
                                status[el.data('level')] = '';
                                el.status = 'opened';
                            }
                        });
                        break;
                    case 'puff':
                        submenu.show("puff", {
                            duration: fxduration,
                            easing: fxtransition,
                            complete: function() {
                                status[el.data('level')] = '';
                                el.data('status','opened');
                            }
                        });
                        break;
                    case 'drop':
                        if (el.hasClass('level1') && orientation == 'horizontal') {
                            if (fxdirection == 'inverse') {
                                dropdirection = 'down';
                                submenu.css('bottom',directionoffset1+'px');
                            } else {
                                dropdirection = 'up';
                            }
                        } else {
                            if (fxdirection == 'inverse') {
                                dropdirection = 'right';
                                submenu.css('right',directionoffset2+'px');
                            } else {
                                dropdirection = 'left';
                            }
                        }
                        submenu.show("drop", {
                            direction: dropdirection,
                            duration: fxduration,
                            easing: fxtransition,
                            complete: function() {
                                status[el.data('level')] = '';
                                el.data('status','opened');
                            }
                        });
                        break;
                    case 'open':
                    default:
                        if (el.hasClass('level1') && orientation == 'horizontal') {
                            submenu.animate({
                                height: el.submenuHeight
                            },{
                                duration: fxduration,
                                queue: false,
                                easing: fxtransition,
                                complete: function() {
                                    status[el.data('level')] = '';
                                    submenu.css('overflow', 'visible');
                                    el.data('status','opened');
                                }
                            });
                        } else {
                            submenu.animate({
                                width: el.submenuWidth
                            },{
                                duration: fxduration,
                                queue: false,
                                easing: fxtransition,
                                complete: function() {
                                    status[el.data('level')] = '';
                                    submenu.css('overflow', 'visible');
                                    el.data('status','opened');
                                }
                            });
                        }
                        break;
                }
            }
            
            function closeMaximenuck(el) {
                submenu = $(el.submenu);
                submenu.stop(true, true);
                status[el.data('level')] = '';
                if (opentype == 'open' || opentype == 'slide') {
                    if (el.hasClass('level1') && orientation == 'horizontal') {
                        submenu.css('height','0');
                    } else {
                        submenu.css('width','0');
                    }
                }
                if (opentype == 'noeffect' || opentype == 'open' || opentype == 'slide') {
                    submenu.css('left','-999em');
                    status[el.data('level')] = '';
                    el.data('status','closed');
                } else {
                    submenu.hide({
                        complete: function() {
                            status[el.data('level')] = '';
                            el.data('status','closed');
                        }
                    });
            // status[el.data('level')] = '';
            // el.data('status','closed');
            }
        // el.data('status','closed');
        }
            
        function showSubmenuck(el) {
            el.css('z-index',15000);
            clearTimeout (el.timeout);
            el.timeout = setTimeout (function() {
                openMaximenuck(el);
            }, dureeIn);
        }
            
        function hideSubmenuck(el) {
            el.css('z-index',12001);
            clearTimeout (el.timeout);
            el.timeout = setTimeout (function() {
                closeMaximenuck(el);
            }, dureeOut);
        }
            
        function testOverflowmenuck(el) {
            var pageWidth = $(document.body).outerWidth();
            var elementPosition = el.offset().left + el.outerWidth() + el.submenuWidth;
            if (elementPosition > pageWidth) {
                el.submenu.css('right',el.submenuWidth+'px');
            }
        }

        function maximenuInit(){
            var menuWidth = maximenuObj.outerWidth();
                
            $('li.maximenuck.parent',maximenuObj).each(function(i,el){
                el = $(el);
                // test if dropdown is required
                if (el.hasClass('nodropdown')) {
                    return true;
                }
                // manage item level
                if (el.hasClass('level1')) el.data('level', 1);
                $('li.maximenuck.parent',el).each(function(j,child){
                    $(child).data('level', el.data('level')+1);
                });
                // manage submenus
                el.submenu = $('> .floatck',el);
                if (opentype == 'noeffect' || opentype == 'open' || opentype == 'slide') {
                    el.submenu.css('left','-999em');
                } else {
                    el.submenu.hide();
                }
                el.submenuHeight = el.submenu.height();
                el.submenuWidth = el.submenu.width();
                if (opentype == 'open' || opentype == 'slide') {
                    if (el.hasClass('level1') && orientation == 'horizontal') {
                        el.submenu.css('height','0');
                    } else {
                        el.submenu.css('width','0');
                    }
                }
                //manage active submenus
                if (showactivesubitems == '1' && el.hasClass('active')) {
                    el.submenu.css('left','auto');
                    el.submenu.css('height',el.submenuHeight);
                    el.submenu.show();
                }
                // manage inverse direction
                if (fxdirection == 'inverse' && el.hasClass('level1') && orientation == 'horizontal')
                    el.submenu.css('bottom',directionoffset1+'px');
                if (fxdirection == 'inverse' && el.hasClass('level1') && orientation == 'vertical')
                    el.submenu.css('right',directionoffset1+'px');
                if (fxdirection == 'inverse' && !el.hasClass('level1') && orientation == 'vertical')
                    el.submenu.css('right',directionoffset2+'px');
                    
                if (behavior == 'clickclose') {
                    el.mouseenter(function() {
                        if (testoverflow == '1') testOverflowmenuck(el);
                        $('li.maximenuck.parent.level'+el.data('level'), maximenuObj).each(function(j, el2){
                            el2 = $(el2);
                            if (el.prop('class') != el2.prop('class')) {
                                el2.submenu = $('> .floatck',el2);
                                // el2.data('status','closed');
                                // status[el2.data('level')] = '';
                                hideSubmenuck(el2);
                            }
                        });
                        showSubmenuck(el);
                    });

                    $('> div > .maxiclose', el).click(function() {
                        hideSubmenuck(el);
                    });
                } else if (behavior == 'click') {
                    if (el.hasClass('parent') && $('> a.maximenuck',el).length) {
                        el.redirection = $('> a.maximenuck',el).prop('href');
                        $('> a.maximenuck',el).prop('href','javascript:void(0)');
                        el.hasBeenClicked = false;
                    }

                    // hide when clicked outside
                    // if (ismobile) {
                    // document.body.addEvent('click',function(e) {
                    // if(element && !e.target || !$(e.target).getParents().contains(element)) {
                    // el.hasBeenClicked = false;
                    // el.hideMaxiCK();
                    // }
                    // });
                    // }
                    $('> a.maximenuck,> span.separator',el).click(function() {
                        // event.stopPropagation();
                        // set the redirection again for mobile
                        // if (el.hasBeenClicked == true && ismobile) {
                        // el.getFirst('a.maximenuck').setProperty('href',el.redirection);
                        // }
                        // el.hasBeenClicked = true;
                        if (testoverflow == '1') testOverflowmenuck(el);
                        if (el.data('status') == 'opened') {
                            hideSubmenuck(el);
                            $('li.maximenuck.parent', el).each(function(j, el2){
                                el2 = $(el2);
                                if (el.prop('class') != el2.prop('class')) {
                                    el2.submenu = $('> .floatck',el2);
                                    hideSubmenuck(el2);
                                }
                            });
                        } else {
                            $('li.maximenuck.parent.level'+el.data('level'), maximenuObj).each(function(j, el2){
                                el2 = $(el2);
                                if (el.prop('class') != el2.prop('class')) {
                                    el2.submenu = $('> .floatck',el2);
                                    hideSubmenuck(el2);
                                }
                            });
                            showSubmenuck(el);
                        }
                    });

                } else {
                    el.mouseenter(function() {
                        if (testoverflow == '1') testOverflowmenuck(el);
                        showSubmenuck(el);
                    });
                    el.mouseleave(function() {
                        hideSubmenuck(el);
                    });
                }
            });

        }
        });
};
})(jQuery);

// jQuery(document).ready(function($){
	// $('#maximenuck').DropdownMaxiMenu({
	// });
// });