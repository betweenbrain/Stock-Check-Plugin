<?php defined('_JEXEC') or die;

/**
 * File       stockcheck.php
 * Created    7/17/14 2:24 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2014 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v2 or later
 */

jimport('joomla.plugin.plugin');

class plgSystemStockcheck extends JPlugin
{

	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * If true, .sys.ini file will override .ini definitions
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = false;

	/**
	 * Constructor.
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 *
	 * @since   0.1
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->app = JFactory::getApplication();
	}

	function onAfterRender()
	{

		if ($this->app->isAdmin())
		{
			return true;
		}

		$buffer = JResponse::getBody();

		$mfg = $this->params->get('mfg', 'db9c3034-70df-472e-8a6f-eff4e63be1a4');

		/**
		 * Regex to  match shortcode
		 *
		 */

		$pattern = '/{stockcheck ([a-zA-Z0-9\.\|\_\-]*)}/i';

		preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);

		if (count($matches))
		{

			foreach ($matches as $match)
			{

				$replacement = '<a title="Check Distributor Stock" href="http://service.stkcheck.com/Default.aspx?mfg=' . $mfg . '&amp;parts=' . $match[1] . '"><img src="' . JURI::base() . 'media/images/Check_Distributor_Stock.jpg" alt="Stock Check"> </a>';

				$buffer = str_replace($match[0], $replacement, $buffer);
			}

			JResponse::setBody($buffer);

			return true;
		}
	}
}
