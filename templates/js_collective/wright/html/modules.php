<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Wright GRID Module
 * (i.e. <jdoc:include type="modules" name="user3" grid="<?php echo $user3gridcount;?>" style="wrightgrid" />)
 */
function modChrome_wrightgrid($module, &$params, &$attribs) {
?>
<div class="module<?php echo $params->get( 'moduleclass_sfx' ); ?> grid_<?php echo $attribs['grid'] ?>">
  <?php if ($module->showtitle) : ?>
  <h3><?php echo $module->title; ?></h3>
  <?php endif; ?>
  <?php echo $module->content; ?>
</div>
<?php
}

/**
 * SHACK GRID + Rounded Corners
 * (i.e. <jdoc:include type="modules" name="user1" grid="<?php echo $user2gridcount;?>" style="shackflexgridrounded" />)
 */
function modChrome_wrightgridrounded($module, &$params, &$attribs) {
	if (!$module->showtitle) {
		$moduletitle = ' notitle';
	} else {
		$moduletitle = NULL;
	}
?>
<div class="module<?php echo $params->get( 'moduleclass_sfx' ); ?> grid_<?php echo $attribs['grid']; ?>">
	<div class="side TL"></div>
	<div class="side TR"></div>
	<div class="side BL"></div>
	<div class="side BR"></div>
	<?php if ($module->showtitle) : ?>
		<h3><?php echo $module->title; ?></h3>
	<?php endif; ?>
		<div class="module_body<?php echo $moduletitle; ?>">
			<?php echo $module->content; ?>
		</div>
</div>
<?php
}

/**
 * SHACK FLEX GRID
 * (i.e. <jdoc:include type="modules" name="user1" grid="<?php echo $user2gridcount;?>" style="shackflexgrid" />)
 */
function modChrome_wrightflexgrid($module, &$params, &$attribs) {
?>
<div class="module<?php echo $params->get( 'moduleclass_sfx' ); ?> flexgrid_<?php echo $attribs['grid'] ?>">
	<div class="pad">
  <?php if ($module->showtitle) : ?>
  <h3><?php echo $module->title; ?></h3>
  <?php endif; ?>
  <?php echo $module->content; ?>
</div>
</div>
<?php
}

/**
 * SHACK FLEX GRID + Rounded Corners
 * (i.e. <jdoc:include type="modules" name="user1" grid="<?php echo $user2gridcount;?>" style="shackflexgridrounded" />)
 */
function modChrome_wrightflexgridrounded($module, &$params, &$attribs) {
	if (!$module->showtitle) {
		$moduletitle = ' notitle';
	} else {
		$moduletitle = NULL;
	}
?>
<div class="module<?php echo $params->get( 'moduleclass_sfx' ); ?> flexgrid_<?php echo $attribs['grid'] ?>">
	<div class="side TL"></div>
	<div class="side TR"></div>
	<div class="side BL"></div>
	<div class="side BR"></div>
	<?php if ($module->showtitle) : ?>
		<h3><?php echo $module->title; ?></h3>
	<?php endif; ?>
		<div class="module_body<?php echo $moduletitle; ?>">
			<?php echo $module->content; ?>
		</div>
</div>
<?php
}



/**
 * SHACK FLEX GRID + CSS3 Rounded
 * (i.e. <jdoc:include type="modules" name="user1" grid="<?php echo $user2gridcount;?>" style="wrightCSS3" />)
 */
function modChrome_wrightCSS3($module, &$params, &$attribs) {
	if (!$module->showtitle) {
		$moduletitle = ' notitle';
	} else {
		$moduletitle = NULL;
	}
?>
<div class="module<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<div class="pad">
			<?php if ($module->showtitle) : ?>
				<h3><?php echo $module->title; ?></h3>
			<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
</div>
<?php
}


/**
 * SHACK ROUNDED CORNER 1
 * (i.e. <jdoc:include type="modules" name="right" style="shackrounded" />)
 */
function modChrome_wrightrounded($module, &$params, &$attribs) {
?>
<div class="moduletable<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<span class="tl"></span><span class="tr"></span>
    <div>
	<?php if ($module->showtitle) : ?>
  <h3><?php echo $module->title; ?></h3>
  <?php endif; ?>
  <?php echo $module->content; ?>
  </div>
  <span class="bl"></span><span class="br"></span>
</div>
<?php
}

/**
 * SHACK ROUNDED CORNER 2
 * (i.e. <jdoc:include type="modules" name="right" style="shackrounded"2 />)
 */
function modChrome_wrightrounded2($module, &$params, &$attribs) {
	if (!$module->showtitle) {
		$moduletitle = ' notitle';
	} else {
		$moduletitle = NULL;
	}
?>
<div class="moduletable<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<div class="side TL"></div>
	<div class="side TR"></div>
	<div class="side BL"></div>
	<div class="side BR"></div>
<?php if ($module->showtitle) : ?>
	<h3><?php echo $module->title; ?></h3>
<?php endif; ?>
	<div class="module_body<?php echo $moduletitle; ?>">
		<?php echo $module->content; ?>
	</div>
</div>
<?php
}