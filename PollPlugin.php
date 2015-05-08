<?php
/**
 * Poll plugin
 *
 * @copyright Ruud de Jong for the Meertens Institute / University of Twente
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */
 
 

define('POLL_PLUGIN_DIR', dirname(__FILE__));
define('POLL_HELPERS_DIR', CONTRIBUTION_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'helpers');
define('POLL_FORMS_DIR', CONTRIBUTION_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'forms');
define('POLL_PAGE_PATH', 'poll/');
define('poll_thankyou_page_message', 'Dankuwel!');
//define('POLL_HELPERS_DIR', METAMETADATA_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'helpers');

require_once CONTRIBUTION_HELPERS_DIR . DIRECTORY_SEPARATOR . 'ThemeHelpers.php';

class PollPlugin extends Omeka_Plugin_AbstractPlugin
{
	
    protected $_hooks = array(
		'install', 
		'uninstall', 
        'admin_plugin_uninstall_message',
		'define_routes',
	    'define_acl',
		'config_form', 
		'config',
        'public_body'
	);

    protected $_filters = array(
        'simple_vocab_routes',
		'admin_navigation_main'
        );
		
	/**
     * @var array Options and their default values.
     */
    protected $_options = array(
		'poll_default_answer' => 7,//'Anders'
        'poll_show_public' => 1,
		'poll_thankyou_page_message' => 'Dankuwel!'
    );
    
    
    public function hookPublicBody($args){
        if ((boolean)get_option('poll_show_public')){
			
			if(!isset($_COOKIE['survey_completed']) || $_COOKIE['survey_completed'] == false)
				include('views/public/index/index.php');
			
			
		}
    }
    
    

	/**
 	* Install the plugin.
	*/
	public function hookInstall()
    {
        $db = $this->_db;
		
        $sql_new = "
		CREATE TABLE IF NOT EXISTS $db->Poll (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `q1` tinyint(3) unsigned NULL,
            `q1_other` VARCHAR(127) NULL ,
            `q2` tinyint(3) unsigned NULL,
            `page` TEXT NULL ,
            `js` tinyint(3) unsigned NULL,
            `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $db->query($sql_new);
    }
    
	
	
    public function hookAdminPluginUninstallMessage()
    {
        echo '<p><strong>Warning</strong>: Uninstalling the Poll plugin
            will remove all responses to the poll.</p>
			<p>Make sure you have downloaded them before uninstalling.</p>';
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $db = $this->_db;
		$db->query("DROP TABLE IF EXISTS $db->Poll");  
    }
    
	/**
	 * Define the acces to controllers and actions
	 */
	public function hookDefineAcl($args)
	{
		$acl = $args['acl'];
			
	    $indexResource = new Zend_Acl_Resource('Poll_Index');
        $acl->add($indexResource);
    
        $acl->allow(null, 'Poll_Index');

		
        
	
	}
    


	

	
    /**
     * Display the plugin config form.
     */
    public function hookConfigForm()
    {
        require 'config_form.php';
    }

    /**
     * Set the options from the config form input.
     */
    public function hookConfig($args)
    {
        $post = $args['post'];
    	set_option('poll_show_public', (int)(boolean)$post['poll_show_public']);
    }

   /**
     * Add the routes for accessing simple pages by slug.
     * 
     * @param Zend_Controller_Router_Rewrite $router
     */
    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        if (is_admin_theme()) {
            return;
        }
		$router = $args['router'];
    	$router->addRoute(
    	    'poll_form', 
    	    new Zend_Controller_Router_Route(
    	        POLL_PAGE_PATH, 
    	        array('module' => 'poll')
    	    )
    	);
		
				
		$router->addRoute(
    	    'poll_form_submit', 
    	    new Zend_Controller_Router_Route(
    	        POLL_PAGE_PATH.'submit', 
    	        array(
    	            'module'       => 'poll', 
    	            'controller'   => 'index', 
    	            'action'       => 'submit', 
    	        )
    	    )
    	);

    }
	
    /**
     * Append routes that render element text form input.
     *
     * @param array $routes
     * @return array
     */
    public function filterSimpleVocabRoutes($routes)
    {
       
        $routes[] = array('module' => 'poll',
                          'controller' => 'poll',
                          'actions' => array('poll-form', 'poll'));
        return $routes;
    }
    /**
     * Display link in Admin nav
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Poll'),
            'uri' => url('poll'),
            'resource' => 'Poll_Index'
        );
        return $nav;

    }

	
	
}




?>