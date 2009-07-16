<?php

class Template extends Savant3
{
    /**
     * overrides Savant 3 constructor to set the template path
     *
	 * @param array $config An associative array of configuration keys for
	 * the Savant3 object.  Any, or none, of the keys may be set.
	 *
	 * @return object Savant3 A Savant3 instance.
     */
    public function __construct($config = null)
    {
        $config = array(
            'template_path' => APP_PATH . Options::get('theme_path')
        );

        parent::__construct($config);
    }
}
