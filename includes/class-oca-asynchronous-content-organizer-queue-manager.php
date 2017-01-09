<?php

/**
 * The content fetcher functionality of the plugin.
 *
 * @link 		http://slushman.com
 * @since 		0.2.0
 *
 * @package 	Oca_Asynchronous_Content_Organizer/includes
 */

/**
 * The content fetcher functionality of the plugin.
 *
 * @package 	Oca_Asynchronous_Content_Organizer/includes
 * @author     2Aces Conteúdo <contato@2aces.com.br>
 */
class Oca_Asynchronous_Content_Organizer_Queue_Manager {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.2.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.2.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * An array with registered functions for processing
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		array			$queue   holds the queue of registered functions for OCA
	 */
	private $oca_queue;

	/**
	 * An array of hashes of registered functions for processing
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		array			$oca_hashes   holds the hashes of registered functions for OCA
	 */
	private $oca_hashes;

	/**
	 * The name of the function to enqueue
	 * 
	 * This is the function that will be called by OCA ajax fetcher. If $nopriv_function_name is set for a different function
	 * this one will be used for privileged users only.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$function_name    The name of the function to request content from.
	 */
	private $function_name;

	/**
	 * The arguments to the function to enqueue
	 * 
	 * The arguments to be passed to the $function_name function by OCA ajax fetcher. If $nopriv_function_name is set for a
	 * different function and $nopriv_function_args have different args, this one will be used for privileged users only. 
	 * Defaults to empty.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$function_name    The arguments to be used with the requesting function. Default: empty.
	 */
	private $function_args;

	/**
	 * The function output behavior
	 * 
	 * The original function output behavior: does it echo or just return data. If set to return,
	 * OCA Fetcher will user it's own echo statement. Defaults to 'return'.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$function_output    Indicates the function output behavior: 'echo' or 'return' (default).
	 */
	private $function_output;

	/**
	 * The name of the function to enqueue for non-privileged users
	 * 
	 * The function called by OCA ajax fetcher for non-privileged users, if $function_name is set for a different function.
	 * Defaults to empty.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$function_name    The name of the function to request content from. Default: empty. 
	 */
	private $nopriv_function_name;

	/**
	 * The arguments to the function to enqueue for non-privileged users.
	 *
	 * The arguments to be passed to the $nopriv_function_name function by OCA ajax fetcher if $nopriv_function_name is set
	 * for a different function. Defaults to empty.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$function_name    The arguments to be used with the requesting function  Default: empty.
	 */
	private $nopriv_function_args;

	/**
	 * The function output behavior for non-privileged users.
	 * 
	 * The output behavior for the original function used for non-privileged users: does it echo or just return data. 
	 * If set to return, OCA Fetcher will user it's own echo statement. Defaultos to 'return';
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$function_output    Indicates the function output behavior: 'echo' or 'return' (default).
	 */
	private $nopriv_function_output;

	/**
	 * The cache behavior for the content.
	 * 
	 * Indicates if the content returned by the function should be cached. The default is false (default).
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		bool 			$use_cache;    The cache behavior for the content: true or false (default)
	 */
	private $use_cache;

	/**
	 * The container for the injected content
	 * 
	 * A CSS selector of the element used as container for the content returned by the OCA Fetcher.
	 * Defaults to #main (WordPress default theme main conten area).
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$container    A CSS selector of the element container for the returned content. Default: #main.
	 */
	private $container;

	/**
	 * The trigger of the process on frontend
	 * 
	 * Indicate the trigger for the OCA ajax manager on front-end. Accepts 'window.load' (default), 'document.load' or an array
	 * with a CSS selector and an action. e.g array('#more-content', 'click').
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		mixed string/array $trigger;     'window.load' (default), 'document.load' or array (e.g. 'css-selector', 'action')
	 */
	private $trigger;

	/**
	 * The timeout for the ajax request
	 * 
	 * Indicates how long the ajax handler waits before timing out. The default is 15s.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		int 			$timeout;    An integer of how long the ajax handler waits. default: 15s.
	 */
	private $timeout;

	/**
	 * The placement behavior on the container
	 * 
	 * Indicates where, inside the container, the content returned by the function should be injected: before (prepend), after (append)
	 * or replace (replace) original content. Defaults to 'append'.
	 *
	 * @since 		0.2.0
	 * @access 		private
	 * @var 		string 			$placement;    Indicates how the content should be injected: append (default), prepend, replace
	 */
	private $placement;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.2.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->oca_queue = [];
		$this->oca_hashes = [];

	}
	 
	/**
	 * make_args_hash function.
	 * create a hash from supplied arguments 
	 * 
	 * @since    0.2.0
	 * @access public
	 * @param array $args 
	 * @return string cache key (identifier)
	 */
	private function make_args_hash( $args ){
		
		if ( empty($args) ){
			return;
		}
		
		// creates a hash based on serialized args for identification
		$hash = md5( serialize( $args ) );
	    return $hash;
	}
	 
	/**
	 * make_fragment_cache_key function.
	 * create a fragment key ( identifier ) from the arguments array, used by cache functons 
	 * 
	 * @since	0.2.0	enqueue ocaVars object using localize script
	 * @access public
	 * @param array $args 
	 * @return string cache key (identifier)
	 */
	public function make_fragment_cache_key( $args ){
		
		// creates a hash based on serialized args for identification
		$args_hash = $this->make_args_hash( $args );
		
		// the actual identification key
		$fragment_key = 'oca-frag-' .  $args_hash;
	    return apply_filters( 'oca_fragment_cache_key' , $fragment_key );
	}
	 
	/**
	 * Adds a job to OCA oca_queue.
	 *
	 * It adds a job to OCA queue. First, checkes if job is already on queue, then validate arguments and if they are valid, adds
	 * the job to queue and to hashes array. Returns 'job added to queue' on success or erros like 'job already on queue',
	 * 'job arguments invalid' on such cases.
	 * 
	 * @since	0.2.0
	 * @access public
	 * @param array $args	arguments for the OCA job
	 * @return string 'job added to queue', 'job already on queue' or 'job arguments invalid'
	*/
	public function add_job( $args ) {
		//TODO remove echo 'debug args contendo ' . var_dump($args );
		if ( empty( $args ) ){
			echo 'error: no arguments provided';
		}
		// default args for WP_Query
		$defaults = array( 
			'function_name'				=> '',
			'function_args'				=> '',
			'function_output'			=> 'return',
			'nopriv_function_name'		=> '',
			'nopriv_function_args'		=> '',
			'nopriv_function_output'	=> '',
			'use_cache'					=> false,
			'container'					=> '#main',
			'triger'					=> 'window.load',
			'timeout'					=> 30,
			'placement'					=> 'append',
		);
	
		// merges defaults and user provided argument
		$job_args = wp_parse_args($args, $defaults);
		// make hash of args (method)
		$job_hash = $this->make_args_hash( $job_args );
		
		// check is already on queue by comparing hashes
		if ( in_array( $job_hash, $this->oca_hashes) ){
			echo 'warning: job already on queue';
		}
		
		// TODO validate args (method)
		/*
		$args_status = validate_args( $args );
		if ( 'invalid' ===  $args_status){
			return 'job arguments invalid';
		}*/
		
		array_push( $this->oca_queue, $job_args );
		array_push( $this->oca_hashes, $job_hash );
		
		//TODO add to hashes (method)
		//TODO remove this: echo 'success: job added to queue';
		//TODO remove echo 'debug this queue contendo: ' . var_dump($this->oca_queue);
	}
	 
	/**
	 * Retrieves the oca_queue.
	 *
	 * Retrieves the oca_queue contents in an array form.
	 * 
	 * @since	0.2.0
	 * @access public
	 * @return array oca_queue array
	*/
	public function get_queue(){
		return $this->oca_queue;
	}
	 
	/**
	 * Retrieves the oca_hashes.
	 *
	 * Retrieves the oca_hashes contents in an array form.
	 * 
	 * @since	0.2.0
	 * @access public
	 * @return array oca_hashes array
	*/
	public function get_hashes(){
		return $this->oca_hashes;
	}

}