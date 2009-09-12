<?php

class PluginBuilder {
	protected $params;
	protected $base_dir;
	protected $template_dir;
	
	public function build($base_dir, $template_dir, $params)
	{
		$this->params = $params;
		
		$this->base_dir = $base_dir;
		$this->template_dir = $template_dir;
		
		// make the plugin directory
		$plugin_dir = $params['plugin_name'] . '/';
		$this->createDirectory($plugin_dir);
		
		// make all the primary subdirs
		$dirs = array('actions', 'graphics', 'languages', 'lib', 'pages', 'views');
		foreach ($dirs as $dir)
		{
			$this->createDirectory($plugin_dir . $dir);
		}
		
		// make html default directory
		$html_dir = $plugin_dir . 'views/default/';
		$this->createDirectory($html_dir);
				
			
		// plugin settings
		if ($params['plugin_settings'])
		{
			$this->createDirectory($html_dir . 'settings/');
			$this->createDirectory($html_dir . 'settings/' . $params['plugin_name']);
			$this->createFile($html_dir . 'settings/' . $params['plugin_name'] . '/edit.php', 'edit.php', $params);
		}
		
		// user settings
		if ($params['user_settings'])
		{
			$this->createDirectory($html_dir . 'usersettings/');
			$this->createDirectory($html_dir . 'usersettings/' . $params['plugin_name']);
			$this->createFile($html_dir . "settings/" . $params['plugin_name'] . '/edit.php', 'edit.php', $params);
		}
		
		// widget
		if ($params['widget'])
		{
			$this->createDirectory($html_dir . 'widgets/');
			$this->createDirectory($html_dir . 'widgets/' . $params['plugin_name']);
			$this->createFile($html_dir . "widgets/" . $params['plugin_name'] . '/edit.php', 'edit.php', $params);
			$this->createFile($html_dir . "widgets/" . $params['plugin_name'] . '/view.php', 'view.php', $params);
		}
		
		// css
		if ($params['css'])
		{
			$this->createDirectory($html_dir . $params['plugin_name']);
			$this->createFile($html_dir . $params['plugin_name'] . '/css.php', 'css.php', $params);
		}
		
		// primary pages
		if ($params['pages'])
		{
			$pages = explode(',', $params['pages']);
			foreach ($pages as $page)
			{
				$page = trim($page);
				$this->createFile($plugin_dir . 'pages/' . $page . '.php', 'page.php', $params);
			}
		}
		
		// start.php
		$this->createFile($plugin_dir . 'start.php', 'start.php', $params);
		// manifest.xml
		$this->createFile($plugin_dir . 'manifest.xml', 'manifest.xml', $params);
		
	}
		
	public function createFile($filename, $template, array $vars)
	{
		error_log('template is ' . $this->template_dir . $template);
		error_log('new file is ' . $this->base_dir . $filename);
		
		$file = file_get_contents($this->template_dir . $template);
			
		if (!$file) return false; 
			
		foreach ($vars as $k => $v)
			$file = str_replace("%%$k%%", $v, $file);
			
		return file_put_contents($this->base_dir . $filename, $file);
	}
	
	public function createDirectory($new_dir)
	{
		error_log('trying to create directory ' . $this->base_dir . $new_dir);
		if (!mkdir($this->base_dir . $new_dir))
		{
			register_error(elgg_echo(sprintf('elgg_dev_tools:error:dir_error'), $new_dir));
			forward('pg/elgg_dev_tools/builder/');	
		}
	}
	
}


?>