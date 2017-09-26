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
		
	}
	
	
	
	
	
	//private $metabox = null;
	
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
		//require_once 'options.php';
		
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
		
		if(!$references_by_tag && !$references_footnote){
			echo __('Vaya, parece que no has elegido cómo insertar referencias');
		}
		
		
		/*
		$includedStuff = get_included_files();
		foreach ($includedStuff as $elem){
			echo $elem;
		}
		*/
		//$this->wkrf_render_wiki_references_tags();
		//$this->wkrf_render_wiki_references_footnote();
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
		//echo '<hr>';
	}
	
	private function wkrf_render_wiki_references_add_new(){
		
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
	
}
