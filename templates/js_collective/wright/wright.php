<?php
/**
 * @package Joomlashack Wright Framework
 * @copyright Joomlashack 2010. All Rights Reserved.
 *
 * @description Wright is a framework layer for Joomla to improve stability of Joomlashack Templates
 *
 * It would be inadvisable to alter the contents of anything inside of this folder
 */

defined('_JEXEC') or die ('You are not allowed to directly access this file');

// Adding a check for PHP4 to cut down on support
if (version_compare(PHP_VERSION, '5', '<')) {
    print 'You are using an out of date version of PHP, version '.PHP_VERSION.' and our products require PHP 5.2 or greater. Please contact your host to use PHP 5.2. All versions of PHP prior to this are unsupported, but us and by the PHP community.';
	die();
}

class Wright
{
	public $template;
	public $document;
	public $adapter;
	public $params;
	public $baseurl;
	public $author;

	function Wright() {
		// Initialize properties
		$document = JFactory::getDocument();
		$this->document = $document;
		$this->params = $document->params;
		$this->baseurl = $document->baseurl;
		$this->author = simplexml_load_file(JPATH_BASE.DS.'templates'.DS.$this->document->template.DS.'templateDetails.xml')->author;

		if (is_file(JPATH_THEMES.DS.$document->template.DS.'functions.php')) include_once(JPATH_THEMES.DS.$document->template.DS.'functions.php');

		// Get our template for further parsing, if custom file is found
		// it will use it instead of the default file
		$path = JPATH_THEMES.DS.$document->template.DS.'template.php';
		$menu = & JSite::getMenu();
		// If homepage, load up home.php if found
		if ($menu->getActive() == $menu->getDefault() && is_file(JPATH_THEMES.DS.$document->template.DS.'home.php')) $path = JPATH_THEMES.DS.$document->template.DS.'home.php';
		elseif (is_file(JPATH_THEMES.DS.$document->template.DS.'custom.php')) $path = JPATH_THEMES.DS.$document->template.DS.'custom.php';

		// Include our file and capture buffer
		ob_start();
		include($path);
		$this->template = ob_get_contents();
		ob_end_clean();
	}

	function getInstance() {
		static $instance = null;
		if ($instance === null) {
			$instance = new Wright();
		}
		return $instance;
	}

	public function display()
	{
		// Setup the header
		$this->header();

		// Parse by platform
		$this->platform();

		// Parse by doctype
		$this->doctype();	

		print trim($this->template);
		return true;

	}

	public function header()
	{
		// Remove mootools if set
		if ($this->document->params->get('mootools', '1') == '0')
		{
			$dochead = $this->document->getHeadData();
			reset($dochead['scripts']);
			foreach ($dochead['scripts'] as $script=>$type) {
				if (strpos($script, 'media/system/js/caption.js') || strpos($script, 'media/system/js/mootools.js') || strpos($script, 'media/system/js/mootools-core.js') || strpos($script, 'media/system/js/mootools-more.js')) {
					unset($dochead['scripts'][$script]);
				}
			}
			$this->document->setHeadData($dochead);
		}

		// set custom template theme for user
		$user = &JFactory::getUser();
		if( !is_null( JRequest::getVar('templateTheme', NULL) ) ) {
			$user->setParam('theme', JRequest::getVar('templateTheme'));
			$user->save(true);
		}
		if($user->getParam('theme')) {
			$this->document->params->set('style', $user->getParam('theme'));
		}

		// Build css
		$this->css();
	}

	private function css()
	{
		$styles = $this->loadCSSList();

		if ($this->document->params->get('csscache', 'no') == 'yes' && is_writable(JPATH_THEMES.DS.$this->document->template.DS.'css'))
		{
			$this->processCSSCache($styles);
		}
		else
		{
			$this->addCSSToHead($styles);
		}
	}

	private function processCSSCache($styles)
	{
		// Combine css into one file if files have been altered since cached copy
		$rebuild = false;
		if (is_file(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$this->document->template.'.css')) $cachetime = filemtime(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$this->document->template.'.css');
		else $cachetime = 0;
		foreach ($styles as $folder => $files)
		{
			if (count($files))
			{
				foreach ($files as $style)
				{
					if ($folder == 'wright') $file = JPATH_THEMES.DS.$this->document->template.DS.'wright'.DS.'css'.DS.$style;
					elseif ($folder == 'template') $file = JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$style;
					else $file = JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$style;

					if (filemtime($file) > $cachetime) $rebuild = true;
				}
			}
		}
		if ($rebuild)
		{
			$css = '';
			foreach ($styles as $folder => $files)
			{
				if (count($files))
				{
					foreach ($files as $style)
					{
						if ($folder == 'wright') $css .= file_get_contents(JPATH_THEMES.DS.$this->document->template.DS.'wright'.DS.'css'.DS.$style);
						elseif ($folder == 'template') $css .= file_get_contents(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$style);
						else $css .= file_get_contents(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$style);
					}
				}
			}

			// Clean out any charsets
			$css = str_replace('@charset "utf-8";', '', $css);
			// Strip comments
			$css = preg_replace('/\/\*.*?\*\//s', '', $css);

			include('css'.DS.'csstidy'.DS.'class.csstidy.php');

			$tidy = new csstidy();

			$tidy->parse($css);

			file_put_contents(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.$this->document->template.'.css', $tidy->print->plain());
		}
		$this->document->addStyleSheet(JURI::root().'templates/'.$this->document->template.'/css/'.$this->document->template.'.css');
	}

	private function addCSSToHead($styles)
	{
		foreach ($styles as $folder => $files)
		{
			if (count($files))
			{
				foreach ($files as $style)
				{
					if ($folder == 'wright') $sheet = JURI::root().'templates/'.$this->document->template.'/wright/css/'.$style;
					elseif ($folder == 'template') $sheet = JURI::root().'templates/'.$this->document->template.'/css/'.$style;
					else $sheet = JURI::root().'templates/'.$this->document->template.'/css/'.$style;

					$this->document->addStyleSheet($sheet);
				}
			}
		}
	}

	private function loadCSSList()
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.environment.browser');

		$browser = JBrowser::getInstance();

		// Load stylesheets by scanning directory for any prefixed with an number and underscore: 1_***.cs
		$styles['wright'] = array('reset.css', 'layout.css', 'typography.css');
		if (version_compare(JVERSION, '1.6.0', 'lt')) {
			$styles['wright'][] = 'joomla15.css';
		} else {
			$styles['wright'][] = 'joomla16.css';
		}
		$styles['template'] = JFolder::files(JPATH_THEMES.DS.$this->document->template.DS.'css', '\d{1,2}_.*.css');

		// Load up a specific style if set
		if (is_file(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.'style-'.$this->document->params->get('style').'.css'))
			$styles['template'][] = 'style-'.$this->document->params->get('style').'.css';

		// Add some stuff for lovely IE if needed
		if ($browser->getBrowser() == 'msie')
		{
			$this->document->addScript(JURI::root().'templates/'.$this->document->template.'/wright/js/html5.js');

			if (is_file(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.'ie.css'))
			{
				$styles['ie'][] = 'ie.css';
			}

			// Switch to allow specific versions of IE to have additional sheets
			$major = $browser->getMajor();
			switch ($major)
			{
				case '6' :
					if (is_file(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.'ie6.css'))
						$styles['ie'][] = 'ie6.css';
						$this->document->addScript(JURI::root().'templates/'.$this->document->template.'/wright/js/dd_belatedpng.js');
						if ($this->document->params->get('doctype') == 'html5') $this->document->addScript(JURI::root().'templates/'.$this->document->template.'/js/html5.js');
					break;

				default :
					if (is_file(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.'ie'.$major.'.css'))
						$styles['ie'][] = 'ie'.$major.'.css';
						if ($this->document->params->get('doctype') == 'html5') $this->document->addScript(JURI::root().'templates/'.$this->document->template.'/wright/js/html5.js');
					break;
			}
		}

		if ($this->document->direction == 'rtl' && is_file(JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.'rtl.css')) $styles[] = JPATH_THEMES.DS.$this->document->template.DS.'css'.DS.'rtl.css';

		return $styles;
	}

	private function doctype()
	{
		require(dirname(__FILE__).DS.'doctypes'.DS.$this->document->params->get('doctype', 'html5').'.php');
		$adapter_name = 'HtmlAdapter'.$this->document->params->get('doctype','html5');
		$adapter = new $adapter_name($this->document->params);

		foreach ($adapter->getTags() as $name => $regex)
		{
			$action = 'get'.ucfirst($name);
			$this->template = preg_replace_callback($regex, array($adapter, $action), $this->template);
		}
	}

	/**
	 * Searches for platform specific tags, and has a callback function to
	 * handle the processing of each match
	 *
	 * @access private
	 */
	private function platform()
	{
		// Get Joomla's version to get proper platform
		jimport('joomla.version');
		$version = new JVersion();
		$file = ucfirst(str_replace('.','',$version->RELEASE));

		// Load up the proper adapter
		require_once(dirname(__FILE__).DS.'adapters'.DS.'joomla.php');
		$this->adapter = new WrightAdapterJoomla($file);
		$this->template = preg_replace_callback("/<w:(.*)\/>/i", array(get_class($this), 'platformTags'), $this->template);
		return true;
	}

	private function platformTags($matches)
	{
		// Grab first match since there should only be one
		$match = $matches[1];

		// @TODO Craft a regex to better handle syntax possibilities
		if (strpos($match, '='))
		{
			$tag = substr($match, 0, strpos($match, ' '));
			$list = explode('" ', substr($match, trim(strpos($match, ' ') + 1)));

			$attributes = array();

			foreach ($list as $item)
			{
				if (strlen($item) > strrchr($item, '"'))
				{
					$name = substr($item, 0, strpos($item, '='));
					$value = substr($item, strpos($item, '"') + 1);
					$attributes[$name] = $value;
				}
			}
			$config = array(trim($tag) => $attributes);
			
		}
		else
		{
			$config = array(trim($match) => array());
		}

		return $this->adapter->get($config);
	}

	// Borrowed from JDocumentHtml for compatibility
	function countModules($condition)
	{
		jimport('joomla.application.module.helper');
		$result = '';

		$words = explode(' ', $condition);
		for($i = 0; $i < count($words); $i+=2)
		{
			// odd parts (modules)
			$name		= strtolower($words[$i]);
			$words[$i]	= ((isset($this->_buffer['modules'][$name])) && ($this->_buffer['modules'][$name] === false)) ? 0 : count(JModuleHelper::getModules($name));
		}

		$str = 'return '.implode(' ', $words).';';

		return eval($str);
	}
}