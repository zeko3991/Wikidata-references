<?php
/**
 * Defines the wikidata references plugin metabox in the post editing area
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 */

/**
 * Defines the wikidata references plugin metabox in the post editing area
 *
 * Defines and loads a metabox in the post editing area to make references
 * to wikipedia articles as the user edits a post.
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */
 
class Wikidata_References_metabox{
	
	
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_VERSION' ) ) {
			$this->version = PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		
		if( is_admin() ) {
			add_action('load-post.php', array($this, 'wkrf_init_wikidata_references_metabox') );
			add_action('load-post-new.php', array($this, 'wkrf_init_wikidata_references_metabox') );
		}

		add_action('wkrf', array($this, 'say_hello'));
		add_action('init', array($this, 'testfun'));
		
		add_filter( 'content_save_pre', array($this, 'append_to_content' ));
		
		//add_filter( 'content_edit_pre', array($this, 'append_to_content' ));
		
		
		
		/*
		 $includedStuff = get_included_files();
		 foreach ($includedStuff as $elem){
		 echo $elem;
		 }
		 */
		
		
	}

	/**
	 * Wikidata references
	 * initializes a metabox for the plugin
	 * @since 1.0.0
	 */
	public  function wkrf_init_wikidata_references_metabox(){
		
		$this->plugin_name = 'wikidata-references';
		
		add_action( 'add_meta_boxes', array($this, 'wkrf_add_wiki_reference_meta_box') );
		//add_action( 'save_post' , array($this, ))  save metabox!
	}
	
	/**
	 * Wikidata references
	 * Adds a metabox for the plugin
	 * @param post_type $post_type
	 * @param post $post
	 * @since 1.0.0
	 */
	public function wkrf_add_wiki_reference_meta_box($post) {
	
		add_meta_box(
				'wiki_references_meta_box',
				__( 'Wikidata References' ),
				array($this, 'wkrf_render_wiki_references_meta_box'),
				'post',
				'side',
				'default'
				);
		
	}
	
	

	/**
	 * Wikidata references
	 * Displays the metabox content.
	 * @since 1.0.0
	 */
	function wkrf_render_wiki_references_meta_box(){
		//wp_nonce_field( basename( __FILE__), 'food_meta_box_nonce');
		echo '<hr>';
		
		$options = get_option($this->plugin_name);
		
		$references_by_tag = $options['references_by_tag'];
		$references_footnote = $options['references_footnote'];
		
		if($references_by_tag){
			$this->wkrf_render_wiki_references_tags();
			echo '<hr>';
		}
		
		if($references_footnote){
			$this->wkrf_render_wiki_references_footnote();
			echo '<hr>';
		}
		
		//////////////////////////
		echo '<hr>';echo '<hr>';
		$this->save_function();
		echo '<hr>';echo '<hr>';
		///////////////////////////
		
		if(!$references_by_tag && !$references_footnote){
			echo __('Vaya, parece que no has elegido cómo insertar referencias');
		}
		
	}
	
	function save_function(){
		echo "<h4>Pruebas de añadido de referencias</h4>";
		//https://www.sitepoint.com/handling-post-requests-the-wordpress-way/
		//https://wordpress.stackexchange.com/questions/138737/using-multiple-submit-buttons-to-trigger-customised-php-functions
		
		/*https://tommcfarlin.com/sending-data-post/*/
		/*
		 * https://tommcfarlin.com/sending-data-post/
		 * https://tommcfarlin.com/sending-data-post/
		 * https://tommcfarlin.com/sending-data-post/
		 * https://tommcfarlin.com/sending-data-post/
		 * https://tommcfarlin.com/sending-data-post/
		 * 
		 * 
		 * https://www.sitepoint.com/handling-post-requests-the-wordpress-way/
		 * https://www.sitepoint.com/handling-post-requests-the-wordpress-way/
		 * 
		 * 
		 * https://wordpress.stackexchange.com/questions/134664/what-is-correct-way-to-hook-when-update-post
		 * 
		 * 
		 * https://wordpress.stackexchange.com/questions/214482/how-to-check-which-submission-button-was-clicked
		 * https://codex.wordpress.org/Function_Reference/submit_button
		 */

		
		submit_button("Haz cosas", "secondary", "ticket_action");
		
	} 
	
	
	
	function testfun(){
		if( isset( $_POST['ticket_action'])){
			
			echo '<script type="text/javascript">alert("hello"); </script>';
			
		}
		
		if( isset( $_REQUEST['ticket_action'])){
			
			echo '<script type="text/javascript">alert("hello"); </script>';
			
		}
	}
	
	/**
	 * Wikidata references
	 * called by wkrf_render_wiki_references_meta_box(), displays the option to
	 * insert references by posts' tags
	 * @since 1.0.0
	 */
	function wkrf_render_wiki_references_tags(){
		//$tags = wp_get_post_tags($_GET['post']);
		global $post;
		$title = __('Referencias por etiqueta');
		
		echo '<ul>';
		//echo $post->ID;
		$tags = wp_get_post_tags($post->ID);
		echo '<h3>'.$title.'</h3>';
		if($tags != null){
			foreach ($tags as $elem){
				echo '<li><input id="wkrf-'.$elem->name.'" type="checkbox" name="Wiki_references_meta_box[]" value="'.$elem->name.'"/>';
				echo $elem->name."</li>";
			}
			echo '</ul>';
		}
		else{
			echo __('No hay posibles referencias definidas');
			echo '<br>';
		}
		
		
		echo '<br> <input id="publish" class="button button-primary button-large" type="submit" value="Actualizar etiquetas" accesskey="p" tabindex="5" name="save"> <br>';

	}
	
	
	private function wkrf_render_wiki_references_footnote(){
		wp_nonce_field( basename(__FILE__), 'wikidata_references_meta_box_footnote');
		global $post;
		$title = __('Nota al pie');
		echo '<h3>'.$title.'</h3>';
		
		echo '<div class="ajaxtag hide-if-no-js">';
		echo '<p>';
		
		/*
		 echo '<input type="text" id="new-ref-post_ref" name="newref[post_ref]" class="newtag form-input-tip
		 ui-autocomplete-input" size="16" autocomplete="off" aria-describedby="new-tag-post_tag-desc" value role="combobox"
		 aria-autocomplete="list" aria-expanded="false" aria-owns="ui-id-1">';
		 */
		echo '<input type="text" id="new-ref-post_ref" class="form-input-tip ui-autocomplete-input" autocomplete="off">';
		echo '<input type="button" class="button" value="Añadir">';
		echo '</p></div>';
		
		
		
	}
	
	
	private function activate_add_content(){
		//do_action('wp_insert_post_data');
		//do_action('save_post');
		add_filter( 'content_save_pre', array($this, 'append_to_content' ));
		do_action('save_post');
		remove_filter( 'content_save_pre', array($this, 'append_to_content' ));
	}
	
	function append_to_content($content){
		global $post;
		return $content.
		'<div id="wkrf_references">
			<br /> This post was saved on '.$post->post_date.
		'</div>';
		
	}
	
	function say_hello(){
		echo "helloo";
	}
	
	
	
	
	
}
