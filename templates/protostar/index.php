<?php

/**

 * @package     Joomla.Administrator

 * @subpackage  Templates.protostar

 *

 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */



defined('_JEXEC') or die;



$app = JFactory::getApplication();

$doc = JFactory::getDocument();

$this->language = $doc->language;

$this->direction = $doc->direction;



// Detecting Active Variables

$option   = $app->input->getCmd('option', '');

$view     = $app->input->getCmd('view', '');

$layout   = $app->input->getCmd('layout', '');

$task     = $app->input->getCmd('task', '');

$itemid   = $app->input->getCmd('Itemid', '');

$sitename = $app->getCfg('sitename');



if($task == "edit" || $layout == "form" )

{

  $fullWidth = 1;

}

else

{

  $fullWidth = 0;

}



// Add JavaScript Frameworks

JHtml::_('bootstrap.framework');


// Add Stylesheets

$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');



// Load optional rtl Bootstrap css and Bootstrap bugfixes

JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);



// Add current user information

$user = JFactory::getUser();



// Adjusting content width

if ($this->countModules('position-7') && $this->countModules('position-8'))

{

  $span = "span6";

}

elseif ($this->countModules('position-7') && !$this->countModules('position-8'))

{

  $span = "span9";

}

elseif (!$this->countModules('position-7') && $this->countModules('position-8'))

{

  $span = "span9";

}

else

{

  $span = "span12";

}



// Logo file or site title param

if ($this->params->get('logoFile'))

{

  $logo = '<img src="'. JURI::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';

}

elseif ($this->params->get('sitetitle'))

{

  $logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';

}

else

{

  $logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';

}


JHTML::_('behavior.modal');


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="cmsmagazine" content="6270e92f5d45d505329037da47104e0c" />

  <jdoc:include type="head" />

  <link media="screen" type="text/css" href="/css/lightbox.css" rel="stylesheet">

  <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:400,700">
  
    <link href="http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">

  <?php

  // Use of Google Font

  if ($this->params->get('googleFont'))

  {

  ?>

    <link href='http://fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName');?>' rel='stylesheet' type='text/css' />

    <style type="text/css">

		<?php 

$app = JFactory::getApplication();

$itemid   = $app->input->getCmd('Itemid', '');

if ($itemid == '101')

	 echo '.navigation .nav-pills li a.mainlevel_active {

		background: none !important;
		color: #d0d4d8 !important;

	  }
		.navigation .nav-pills li a.mainlevel_active:hover {

		background: url("/templates/protostar/img/nav-bg-on.png") repeat-x left top !important;
		color: #fff !important;

	  }';
?>

    </style>

  <?php

  }

  ?>

  <?php

  // Template color

  if ($this->params->get('templateColor'))

  {

  ?>

  <?php

  }

  ?>

<!-- jQuery library (served from Google) 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->


  <!--[if !IE]><!--><link href="<?php echo 'templates/'.$this->template.'/'; ?>css/template-not-ie.css" rel="stylesheet" /><!--<![endif]-->

  <!--[if gte IE 10]>

	<link href="<?php echo 'templates/'.$this->template.'/'; ?>css/template-not-ie.css" rel="stylesheet" />

  <![endif]-->


  <script> 
if (/*@cc_on!@*/false) { 
    var headHTML = document.getElementsByTagName('head')[0].innerHTML; 
headHTML    += '<link href="<?php echo 'templates/'.$this->template.'/'; ?>css/template-ie10.css" rel="stylesheet" />'; 
document.getElementsByTagName('head')[0].innerHTML = headHTML; 
} 
</script>
  <!--[if lte IE 9]>

    <script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>

	<link href="<?php echo 'templates/'.$this->template.'/'; ?>css/template-ie.css" rel="stylesheet" />

  <![endif]-->

  <!--[if gte IE 10]>

	<link href="<?php echo 'templates/'.$this->template.'/'; ?>css/template-ie10.css" rel="stylesheet" />

  <![endif]-->

</head>



<body class="site <?php echo $option . " view-" . $view . " layout-" . $layout . " task-" . $task . " itemid-" . $itemid . " ";?> <?php if ($this->params->get('fluidContainer')) { echo "fluid"; } ?>">

  <!-- Body -->

  <div class="container">
  
<!-- Header -->

      <div class="header">

        <div class="header-inner">

          <a class="brand pull-left" href="<?php echo $this->baseurl; ?>">

            <?php echo $logo;?> <?php if ($this->params->get('sitedescription')) { echo '<div class="site-description">'. htmlspecialchars($this->params->get('sitedescription')) .'</div>'; } ?>

          </a>

<!--          <div class="header-search pull-right"> -->

<!--          </div> -->


            <jdoc:include type="modules" name="position-0" style="none" />
          
          <div class="clearfix"></div>
            
      <div class="navigation">

        <ul class="nav menu nav-pills">

        <jdoc:include type="modules" name="position-1" style="none" />

        </ul>

      </div>


        </div>

      </div>

          <div class="clearfix"></div>

    <div class="container<?php if ($this->params->get('fluidContainer')) { echo "-fluid"; } ?>">

      <?php if ($this->countModules('position-4')): ?>

        <jdoc:include type="modules" name="position-4" style="none" />

      <?php endif; ?>

      <jdoc:include type="modules" name="banner" style="xhtml" />

      <div class="row-fluid">

        <?php if ($this->countModules('position-8')): ?>

        <!-- Begin Sidebar -->

        <div id="sidebar" class="span3">

          <div class="sidebar-nav">

            <jdoc:include type="modules" name="position-8" style="xhtml" />

          </div>

        </div>

        <!-- End Sidebar -->

        <?php endif; ?>

        <div id="content" class="<?php echo $span;?>">

          <!-- Begin Content -->

          <jdoc:include type="modules" name="position-2" style="none" />

          <jdoc:include type="message" />
          
          <jdoc:include type="modules" name="position-3" style="xhtml" />

          <jdoc:include type="component" />
          
	      <?php if ($this->countModules('position-12')): ?>
	
	        <jdoc:include type="modules" name="position-12" style="none" />
	
	      <?php endif; ?>
          
          <div class="clearfix"></div>

	      <?php if ($this->countModules('position-9')): ?>
	
	        <jdoc:include type="modules" name="position-9" style="none" />
	
	      <?php endif; ?>

          <!-- End Content -->

        </div>

        <?php if ($this->countModules('position-7')) : ?>

          <!-- Begin Right Sidebar -->

        	<div id="aside" class="span3"><jdoc:include type="modules" name="position-7" style="none" /></div>

          <!-- End Right Sidebar -->

        <?php endif; ?>

      </div>


      <?php if ($this->countModules('position-10')): ?>

        <jdoc:include type="modules" name="position-10" style="none" />

      <?php endif; ?>

      <?php if ($this->countModules('position-11')): ?>

        <jdoc:include type="modules" name="position-11" style="none" />

      <?php endif; ?>
      
	  <div class="clearfix"></div>
      
      <!-- Footer -->

      <div class="footer">

        <div class="footer-inner<?php if ($this->params->get('fluidContainer')) { echo "-fluid"; } ?>">

          <jdoc:include type="modules" name="footer" style="none" />

<!--          <p class="pull-right"><a href="#top" id="back-top"><?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?></a></p>

          <p>&copy; <?php echo $sitename; ?> <?php echo date('Y');?></p> -->

        </div>

      </div>

<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t57.10;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->
<a href="http://yandex.ru/cy?base=0&amp;host=www.ckbran.ru"><img src="http://www.yandex.ru/cycounter?www.ckbran.ru" width="88" height="31" alt="ßíäåêñ öèòèðîâàíèÿ" border="0" /></a>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter6509464 = new Ya.Metrika({id:6509464,
                    webvisor:true,
                    clickmap:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/6509464" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18869859-1']);
  _gaq.push(['_setDomainName', 'ckbran.ru']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

    </div>

  </div>
  

  <jdoc:include type="modules" name="debug" style="none" />

<script src="js/jquery-ui-1.8.18.custom.min.js"></script>

<script src="js/jquery.smooth-scroll.min.js"></script>

<script src="js/lightbox.js"></script>

  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="js/datepicker-ru.js"></script>





<script>




jQuery(document).ready(function($) { 


$('#dp404').hover(function(){$('#human404').show();},function(){$('#human404').hide();});

$('#dp704').hover(function(){$('#human704').show();},function(){$('#human704').hide();});

$('#dp302').hover(function(){$('#human302').show();},function(){$('#human302').hide();});

$('#dp303').hover(function(){$('#human303').show();},function(){$('#human303').hide();});

$('#dp637').hover(function(){$('#human637').show();},function(){$('#human637').hide();});

$('#dp304').hover(function(){$('#human304').show();},function(){$('#human304').hide();});

$('#dp305').hover(function(){$('#human305').show();},function(){$('#human305').hide();});

$('#dp307').hover(function(){$('#human307').show();},function(){$('#human307').hide();});

$('#dp306').hover(function(){$('#human306').show();},function(){$('#human306').hide();});

$('#dp309').hover(function(){$('#human309').show();},function(){$('#human309').hide();});

$('#dp1180').hover(function(){$('#human1180').show();},function(){$('#human1180').hide();});

$('#dp310').hover(function(){$('#human310').show();},function(){$('#human310').hide();});

$('#dp311').hover(function(){$('#human311').show();},function(){$('#human311').hide();});

$('#dp312').hover(function(){$('#human312').show();},function(){$('#human312').hide();});

$('#dp314').hover(function(){$('#human314').show();},function(){$('#human314').hide();});

$('#dp797').hover(function(){$('#human797').show();},function(){$('#human797').hide();});

$('#dp844').hover(function(){$('#human844').show();},function(){$('#human844').hide();});

$('#dp703').hover(function(){$('#human703').show();},function(){$('#human703').hide();});

	if( $('#aside').children('div').length == 0 ) {
		if ( $('#sidebar').length )
			$('#content').attr("class","span9");
		else
			$('#content').attr("class","span12");
		$('#aside').remove();
	}
	<?php 
	
	$checkid =JRequest::getVar('id');
	$checkview = JRequest::getVar('view');


	if ($checkview != 'article')
		if  ($checkid == 14)
			echo ('
				$("#content").attr("class","span5");
				$("#aside").attr("class","span4");
			');

 		if ($itemid == 1420)
			echo ('
				$("#content").attr("class","span5");
				$("#aside").attr("class","span4");
			');
	 ?>


$('.bottominfo').ready(function() {
	$('.priceblock').append($('.bottominfo').html());
	$('.bottominfo').remove();
});

<?php 

$app = JFactory::getApplication();

$itemid   = $app->input->getCmd('Itemid', '');

if ($itemid == '101') {
echo "$('.bxslider').bxSlider({
  minSlides: 3,
  maxSlides: 6,
  slideWidth: 180,
  slideHeight: 112,
  slideMargin: 9,
  moveSlides: 1
}); ";
}
?>     


$('a').smoothScroll({

speed: 1000,

easing: 'easeInOutCubic'

});

$('.showOlderChanges').on('click', function(e){

$('.changelog .old').slideDown('slow');

$(this).fadeOut();

e.preventDefault();

})

});

$( "#datepicker" ).datepicker({ regional : 'ru', beforeShowDay: $.datepicker.noWeekends, minDate: 0, maxDate: "+3M" });

var _gaq = _gaq || [];

_gaq.push(['_setAccount', 'UA-2196019-1']);

_gaq.push(['_trackPageview']);

(function() {

var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;

ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

})();

</script> 

<!-- BEGIN JIVOSITE CODE {literal} -->
<!-- <script type='text/javascript'>
(function(){ var widget_id = '95729';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script> -->
<!-- {/literal} END JIVOSITE CODE -->


</body>

</html>

