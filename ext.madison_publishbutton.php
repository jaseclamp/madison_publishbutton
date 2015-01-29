<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( ! defined('PATH_THIRD')) { define('PATH_THIRD', APPPATH . 'third_party'); };

//require_once PATH_THIRD . 'zenbu/config.php';

class Madison_publishbutton_ext {
	
	var $name				= 'Madison Publish Button';
	var $addon_short_name 	= 'madison_publishbutton';
	var $version 			= '1.1.1';
	var $description		= 'Adds publish button to channel entries and clone button to edit entry page';
	var $settings_exist		= 'n';
	var $docs_url			= '';
	var $settings        	= array('title_suffix'=>' CLONED', 'url_suffix'=>' CLONED', 'update_time'=>true);

	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	function __construct($settings=''){
		$this->EE 			=& get_instance();
		$this->settings		= $settings;
		$this->site_id 		= $this->EE->config->item('site_id');
    }
	
	function activate_extension() {
	  $data[] = array(
			'class'      => __CLASS__,
			'method'    => "add_publish_button",
			'hook'      => "cp_menu_array",
			'settings'    => serialize($this->settings),
			'priority'    => 10,
			'version'    => $this->version,
			'enabled'    => "y"
		  );
	  
	  $data[] = array(
			'class'      => __CLASS__,
			'method'    => "make_entry_duplicate",
			'hook'      => "publish_form_entry_data",
			'settings'    => serialize($this->settings),
			'priority'    => 10,
			'version'    => $this->version,
			'enabled'    => "y"
		  );
	  
	  
	  // insert in database
	  foreach($data as $key => $data) {
	  $this->EE->db->insert('exp_extensions', $data);
	  }
	}

	public function add_publish_button($menu)
	{
	$this->_include_static();
	return $menu;
	}

	function _theme_url()
	{
	if (! isset($this->cache['theme_url']))
	{
	  $theme_folder_url = defined('URL_THIRD_THEMES') ? URL_THIRD_THEMES : $this->EE->config->slash_item('theme_folder_url').'third_party/';
	  $this->cache['theme_url'] = $theme_folder_url.'madison_publishbutton/';
	}
	return $this->cache['theme_url'];
	}

	private function _include_static()
	{
	if (! isset($this->cache['static_included']))
	{
	  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->_theme_url().'addpublishbutton.js"></script>');
	  $this->cache['static_included'] = TRUE;
	}
	}

		/**
	 * entry_submission_start function.
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function entry_submission_start($data)
	{

		if  ($this->EE->input->get('clone') == 'y')
		{

			$_GET['entry_id'] ='';

		}

		return false;
	}

	function make_entry_duplicate($data)
	{

		if  ($this->EE->input->get('clone') == 'y')
		{

			$suffix = (isset($this->settings['title_suffix'])) ? $this->settings['title_suffix'] : ' Duplicated';
			$url_suffix = (isset($this->settings['url_suffix'])) ? $this->settings['url_suffix'] : '-duplicated' ;

			$ext_data= array (
				'title' => $data['title'].$suffix,
				'url_title' => $data['url_title'].$url_suffix,
				'versioning_enabled' => 'n',
				'recent_comment_date' => '',
				'comment_total' => '' ,
				'ip_address' => $this->EE->input->ip_address(),
			);

			if (isset($this->settings['update_time']) OR 1==1)
			{
				$ext_data= array_merge($ext_data, array (
						'entry_date' => '',
						'edit_date'     => '',
						'year'      => '',
						'month'      => '',
						'day'      => '',
						'year' => '',
						'month' => '',
						'day' => '',
						'expiration_date' => '',
						'comment_expiration_date' => '',
						'edit_date' => '',
					));
			}

			foreach ($ext_data as $key => $val)
			{
				$data[$key] = $val;
			}
		}
		return $data;
	}

	function disable_extension() {
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('exp_extensions');
	} 
	  
	  

  
  

}
// END CLASS