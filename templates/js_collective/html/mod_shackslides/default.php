<?php
/**
 * @version   1.x
 * @package   ShackSlides
 * @copyright (C) 2010 Joomlashack / Meritage Assets Corp
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
/**
 *    This file is part of ShackSlides.
 *    
 *    ShackSlides is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with ShackSlides.  If not, see <http://www.gnu.org/licenses/>.
 *
 * */

defined('_JEXEC') or die('Restricted access');

// This can be used in an override to change default settings. User can override
// settings in the module settings page still.
$defaults = array(
	'width' => '500', // width of container
	'height' => '250', // height of container
	'autoplay' => '5', // number of seconds per slide on autoplay, 0 to disable
	'pause' => 'true', // pauses the autoplay on hover over slider
	'description' => 'true', // displays image discription box
	'description_background' => 'ffffff', // description background color hex code
	'description_opacity' => '0.5', // description background opacity
	'description_height' => '50', // description height if position is top/bottom
	'description_width' => '50', // description width if position is right/left
	'description_position' => 'bottom', // top,button,right,left are options
	'buttons' => '0', // displays the next/prev buttons
	'buttons_opacity' => '1', // buttons opacity
	'buttons_prev_label' => '&laquo;', // previous button label
	'buttons_next_label' => '&raquo;', // next button label
	'navigation' => '1', // displays the navigation
	'navigation_buttons' => '1', // displays next/prev buttons in navigation bar
	'navigation_container' => 'sliderNav', // id for the slider navigation container
	'navigation_label' => '1', // shows the numbers for navigation
	'mousewheel' => '0', // can use mousewheel for navigation
	'container' => 'slider', // id for the slider container
);

// Adding the javascript and css files to the document
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$doc->addScript(JURI::base() . 'modules/mod_shackslides/assets/sliderman.js');
ob_start();
include(JPATH_ROOT.'/templates/'.$app->getTemplate().'/html/mod_shackslides/css/sliderman.css.php');
$styles = ob_get_contents();
ob_end_clean();

$doc->addStyleDeclaration($styles);

?>

<div class="shackSlider">

	<div id="<?php echo $params->get('container', $defaults['container']) ?>">
<?php for ($i = 0; $i < count($images); $i++) : ?>
<?php if ($images[$i] === false) continue; ?>
<?php if ($links[$i]) : ?><a href="<?php echo $links[$i]; ?>"><?php endif; ?>
					<img src="<?php echo $base.$images[$i] ?>" title="<?php echo $titles[$i] ?>" />
		<?php if ($links[$i]) : ?></a><?php endif; ?>
		<?php if ($titles[$i]) : ?>
				<div class="slideTitle">
			<?php echo $titles[$i]; ?>
			</div>
<?php endif; ?>
			<?php endfor; ?>

	</div>
	<div id="<?php echo $params->get('navigation_container', $defaults['navigation_container']) ?>"></div>

<?php require(JPATH_BASE.DS.'modules'.DS.'mod_shackslides'.DS.'assets'.DS.'script.js.php') ?>

</div>