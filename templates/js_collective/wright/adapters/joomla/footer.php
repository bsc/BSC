<?php

class WrightAdapterJoomlaFooter
{
	public function render($args)
	{
		$doc = Wright::getInstance();

		if ($doc->document->params->get('rebrand', 'no') !== 'yes')
		{
			if (stripos($doc->author, 'praise')) {
				$anchors = array("Joomla Templates"=>25,				"Free Joomla Templates"=>45,					"Joomla 1.6 Template"=>61,					"Joomla 1.6 Templates"=>75,					"Joomla Template"=>80,					"Joomla 1.5 Templates"=>84,					"Joomla 1.5 Template"=>88,					"Joomla Template Club"=>92,					"Joomla Themes"=>96,					"Joomla Theme"=>100);
				$links = array("Joomla Templates"=>"joomla-templates",	"Free Joomla Templates"=>"joomla-templates",	"Joomla 1.6 Template"=>"joomla-templates",	"Joomla 1.6 Templates"=>"joomla-templates",	"Joomla Template"=>"joomla-templates",	"Joomla 1.5 Templates"=>"joomla-templates",	"Joomla 1.5 Template"=>"joomla-templates",	"Joomla Template Club"=>"joomla-templates",	"Joomla Themes"=>"joomla-templates",	"Joomla Theme"  => "joomla-templates");
				$endlines  = array(": by JoomlaPraise"=>10,				": from JoomlaPraise"=>20,						" by JoomlaPraise"=>30,						" from JoomlaPraise"=>40,					" at JoomlaPraise"=>50,					": by JoomlaPraise.com"=>60,				": from JoomlaPraise.com"=>70,				" by JoomlaPraise.com"=>80,					" from JoomlaPraise.com"=>90,			" at JoomlaPraise.com"=>100);
				$host = "http://www.joomlapraise.com/";
				$class = 'joomlapraise';
			}
			else
			{
				$anchors = array("Joomla Templates"=>25,							"Free Joomla Templates"=>45,						"Joomla Tutorial"=>61,			"Joomla Template Tutorial"=>75,				"Joomla Template"=>80,								"Joomla 1.5 Templates"=>84,					"Joomla 1.5 Template"=>88,								"Joomla Extensions"=>92,					"Joomla Extension"=>96,						"Joomla Training"=>100);
				$links = array("Joomla Templates"=>"professional-joomla-templates",	"Free Joomla Templates"=>"free-joomla-templates",	"Joomla Tutorial"=>"tutorials",	"Joomla Template Tutorial"=>"tutorials",	"Joomla Template"=>"professional-joomla-templates",	"Joomla 1.5 Templates"=>"joomla-templates",	"Joomla 1.5 Template"=>"professional-joomla-templates",	"Joomla Extensions"=>"joomla-extensions",	"Joomla Extension"=>"joomla-extensions",	"Joomla Training"  => "university/");
				$endlines  = array(": by JoomlaShack"=>10,							": from JoomlaShack"=>20,							" by JoomlaShack"=>30,			" from JoomlaShack"=>40,					" at JoomlaShack"=>50,								": by JoomlaShack.com"=>60,					": from JoomlaShack.com"=>70,							" by JoomlaShack.com"=>80,					" from JoomlaShack.com"=>90,				" at JoomlaShack.com"=>100);
				$host = "http://www.joomlashack.com/";
				$class = 'joomlashack';
			}

			$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

			$md5 = md5($url);

			$nums = str_replace(array('a','b','c','d','e','f'), '', $md5);
			
			$first = substr($nums,0,2);
			$second = substr($nums,2,2);
			$third  = substr($nums,4,2);

			if(substr($first,0,1)=='0') { $first = substr($first,1,1); }
			if(substr($second,0,1)=='0') { $second = substr($second,1,1); }
			if(substr($third,0,1)=='0') { $third = substr($third,1,1); }

			$anchor = '';
			$endline = '';
			foreach($anchors as $possibility=>$val) {;
			  if($anchor=='' && $val>$first) {
					$anchor = $possibility;
					$host .= $links[$possibility];
					foreach($endlines as $ends=>$v) {
						if($endline=='' && $v>$third) { $endline = $ends; }
					}
			  }
			}

			return '<div class="'.$class.'"><a href="'.$host.'">'.$anchor.'</a>'.$endline.'</div>';
		}
		else
		{
			return;
		}
	}
}