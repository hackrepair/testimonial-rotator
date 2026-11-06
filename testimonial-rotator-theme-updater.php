<?php

class testimonial_rotator_theme_updater
{
	var $theme_slug;
	var $version;
	var $basename;
	
	function __construct( $slug, $version, $basename )
	{
		$this->theme_slug 	= $slug;
		$this->version 		= $version;
		$this->basename 	= $basename;

		add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
		add_filter('plugins_api', array($this, 'check_info'), 10, 3);
	}

	private function get_remote()
	{
        // Retained for legacy integrations; the abandoned theme-pack endpoint is retired.
        // Reborn does not yet provide an automatic update service.
        return false;
	}
	
	public function check_update( $transient )
	{
	    if( !isset($transient->response) ) return $transient;
        
        $info = $this->get_remote();
        if( !$info ) return $transient;
        if( version_compare($info->version, $this->version, '<=') ) return $transient;
        
        $obj = new stdClass();
        $obj->slug 				= $this->theme_slug;
        $obj->plugin			= $this->basename;
        
        $obj->new_version 		= $info->version;
        $obj->url 				= $info->homepage;
        $obj->package 			= $info->download_link;
        $transient->response[ $this->basename ] = $obj;

        return $transient;
	}
	
    public function check_info( $result, $action, $args )
    {
	    if( isset($args->slug) && $args->slug == $this->theme_slug ) 
	    {
			return $this->get_remote();
	    }
        return $result;
    }
}
