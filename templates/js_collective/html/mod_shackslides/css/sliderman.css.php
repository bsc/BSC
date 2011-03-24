<?php $app = JFactory::getApplication(); ?>
.shackSlider {
	width: <?php echo $params->get('width', $defaults['width']) ?>px;
	background:#EEE;
	border:3px solid #D5D5D5;
}

#<?php echo $params->get('container', $defaults['container']) ?>{
	width: <?php echo $params->get('width', $defaults['width']) ?>px;
	height: <?php echo $params->get('height', $defaults['height']) ?>px;
	margin: 0 auto 10px auto;
}

.slideTitle {
	background: url(<?php echo JURI::base() ?>templates/<?php echo $app->getTemplate() ?>/html/mod_shackslides/images/bg.png);
	color:#FFF;
	text-align: left;
	padding: 10px;
}

.shackSlider #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> {
	height: 24px;
	text-align: center;
	padding-bottom: 3px;
	position:relative;
}

#<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a:link, #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a:active, #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a:visited, #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a:hover{
	background: url(<?php echo JURI::base() ?>templates/<?php echo $app->getTemplate() ?>/html/mod_shackslides/images/item.png) no-repeat center center;
	line-height: 24px;
	font-size: 16px;
	height: 24px;
	width: 25px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	color: #ddd;
	text-shadow: 1px 1px 1px #666;
	text-indent:-9999px;
}

#<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a.active:link, #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a.active:active, #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a.active:visited, #<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a.active:hover{
	background: url(<?php echo JURI::base() ?>templates/<?php echo $app->getTemplate() ?>/html/mod_shackslides/images/item_active.png) no-repeat center center;
	color: #fff;
	text-shadow: 1px 1px 1px #666;
}

#<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a.sliderPrev{
	background: url(<?php echo JURI::base() ?>templates/<?php echo $app->getTemplate() ?>/html/mod_shackslides/images/prev.png) no-repeat center center;
	width: 49px;
	height: 24px;
	display: inline-block;
	text-decoration: none;
	text-indent:-9999px;
	position:absolute;
	left:0;
	top:0;
}

#<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?> a.sliderNext{
	background: url(<?php echo JURI::base() ?>templates/<?php echo $app->getTemplate() ?>/html/mod_shackslides/images/next.png) no-repeat center center;
	width: 49px;
	height: 24px;
	display: inline-block;
	text-decoration: none;
	line-height: 24px;
	text-align: center;
	text-indent:-9999px;
	position:absolute;
	right:0;
	top:0;
}