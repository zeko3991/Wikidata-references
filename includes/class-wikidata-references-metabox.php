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
		
		$wkrf_wikidata_tags_references = array();
		$wkrf_wikidata_handmade_references = array();
		
		
		
		if( is_admin() ) {
			add_action('load-post.php', array($this, 'wkrf_init_wikidata_references_metabox') );
			add_action('load-post-new.php', array($this, 'wkrf_init_wikidata_references_metabox') );
		}
		//https://code.tutsplus.com/articles/reusable-custom-meta-boxes-part-1-intro-and-basic-fields--wp-23259
		//https://wordpress.stackexchange.com/questions/134664/what-is-correct-way-to-hook-when-update-post
		add_action('wkrf', array($this, 'say_hello'));
		add_action('save_post', array($this, 'wkrf_save_wikidata_references_metabox_prueba_uno'));
		add_action('save_post', array($this, 'wkrf_save_wiki_references_tags_metabox'));
		add_filter('content_edit_pre', array($this, 'append_to_content' ));

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
		
		add_action( 'add_meta_boxes', array($this, 'wkrf_add_wiki_reference_metabox') );
	}
	
	/**
	 * Wikidata references
	 * Adds a metabox for the plugin
	 * @param post_type $post_type
	 * @param post $post
	 * @since 1.0.0
	 */
	public function wkrf_add_wiki_reference_metabox($post) {
	
		add_meta_box(
				'wiki_references_metabox',  //id
				__( 'Wikidata References' ),  //title
				array($this, 'wkrf_render_wiki_references_metabox'), //callback
				'post', //page
				'side', //context
				'core'  //priority
				);
		
	}
	
	

	/**
	 * Wikidata references
	 * Displays the metabox content.
	 * @since 1.0.0
	 */
	function wkrf_render_wiki_references_metabox(){
		//wp_nonce_field( basename( __FILE__), 'food_metabox_nonce');
		
		
		
		echo '<hr>';
		
		$options = get_option($this->plugin_name);
		
		
		//checks the options selected to render in the metabox
		$references_by_tag_option = $options['references_by_tag'];
		$references_footnote_option = $options['references_footnote'];
		
		if($references_by_tag_option){
			$this->wkrf_render_wiki_references_tags();
			echo '<hr>';
		}
		
		if($references_footnote_option){
			$this->wkrf_render_wiki_references_footnote();
			echo '<hr>';
		}
		
		
		echo '<hr>';
		$this->render_prueba_uno();
		echo '<hr>';
		
		if(!$references_by_tag_option && !$references_footnote_option){
			echo __('Vaya, parece que no has elegido cómo insertar referencias');
		}
		
	}
	

	/**
	 * Wikidata references
	 * called by wkrf_render_wiki_references_metabox(), displays the option to
	 * insert references by posts' tags
	 * @since 1.0.0
	 */
	function wkrf_render_wiki_references_tags(){
		global $post;
		$title = __('Referencias por etiqueta');
		
		$tags = wp_get_post_tags($post->ID);
		echo '<h3>'.$title.'</h3>';
		
		if($tags != null){
			echo '<div id="wkrf-metabox-references-tags" class="wkrf-mtbox-references-tags">';
			echo '<ul>';

			foreach ($tags as $elem){
				
				$name = str_replace(" ", "_", $elem->name);
				$id = $name;
				$tag_post_value = get_post_meta($post->ID, '_'.$name, true);
				wp_nonce_field( 'save_'.$name, $name.'_nonce');
				
				?>
				
				<li>
					<input type="checkbox" id="wkrf-tag-<?php echo $id; ?>" name="wkrf-tag-<?php echo $name; ?>" value='yes'<?php checked("yes", $tag_post_value); ?> />
					<span><?php echo $elem->name;?></span>
				</li>
				
				<?php 
			}
			echo '</ul>';
			echo '</div>';
		}
		else{
			echo __('No hay posibles referencias definidas');
			echo '<br>';
		}

	}
	
	/**
	 * Wikidata references
	 * saves the tags metabox content, only text
	 * @since 1.0.0
	 */
	function wkrf_save_wiki_references_tags_metabox(){
		global $post;
		$tags = wp_get_post_tags($post->ID);
		
		/*if( isset($_POST['wkrf-tag-prueba'])){
			update_post_meta($post->ID, '_wkrf-tag-prueba', "yes");
		}
		else{
			update_post_meta($post->ID, '_wkrf-tag-prueba', "no");
		}*/
		
		foreach ($tags as $tag_to_save){
			$name = str_replace(" ", "_", $tag_to_save->name);
			if(! wp_verify_nonce( $_REQUEST[$name."_nonce"], 'save_'.$name)){
				return $post->ID;
			}

			
			if(isset( $_POST['wkrf-tag-'.$name])){
				update_post_meta($post->ID, '_'.$name, "yes");
			}
			else{
				//update_post_meta($post->ID, '_'.$tag_to_save->name, 0);
				update_post_meta($post->ID, '_'.$name, "no");
			}
		}
		
	}
	
	/**
	 * pruebas de guardado de metadatos en el post
	 */
	function render_prueba_uno(){
		global $post;
		
		$prueba_uno = get_post_meta($post->ID, '_prueba_uno', true);
		
		wp_nonce_field( 'save_prueba_uno', 'prueba_uno_nonce');
		echo '<ul>';
		echo '<li><input type="text" name="prueba_uno" value="'.sanitize_text_field($prueba_uno). '" /></li>';
		echo '</ul>';
		
		
		$prueba_box = get_post_meta($post->ID, '_prueba_box', true);
		wp_nonce_field( 'save_prueba_box', 'prueba_box_nonce');
		
		?>
		
		<input type="checkbox" id="prueba_box" name="prueba_box" value='yes'<?php checked("yes", $prueba_box); ?> />
					<span><?php echo "prueba box"?></span>
					
					<?php 
	}
	
	/**
	 * Wikidata references
	 * saves the metabox content when saving the post
	 * @since 1.0.0
	 */
	function wkrf_save_wikidata_references_metabox_prueba_uno(){
		global $post;
		
		if( ! isset( $_POST['prueba_uno'])){
			return $post->ID;
		}
		
		if( ! wp_verify_nonce( $_POST['prueba_uno_nonce'], 'save_prueba_uno')){
			return $post->ID;
		}
		
		if( ! isset( $_POST['prueba_box_nonce'])){
			return "prueba box nonce not set";
		}
		
		if( ! wp_verify_nonce( $_POST['prueba_box_nonce'], 'save_prueba_box')){
			return "prueba box nonce not verified";
		}
		
		
		
		$prueba_uno = sanitize_text_field( $_POST['prueba_uno']);
		
		update_post_meta($post->ID, '_prueba_uno', $prueba_uno);
		
		
		if( isset($_POST['prueba_box'])){
			update_post_meta($post->ID, '_prueba_box', "yes");
		}
		else{
			//update_post_meta($post->ID, '_'.$tag_to_save->name, 0);
			update_post_meta($post->ID, '_prueba_box', "no");
		}
	//update_post_meta($post->ID, '_prueba_box', $_POST['prueba_box']);
		
	}
	
	

	
	
	private function wkrf_render_wiki_references_footnote(){
		wp_nonce_field( basename(__FILE__), 'wikidata_references_metabox_footnote');
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
		echo '<input type="text" id="new-ref-post_ref" class="form-input-tip value="valuee" ui-autocomplete-input" autocomplete="off">';
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
		//$tags = wp_get_post_tags($post->ID);
		//$meta = get_post_meta( get_the_ID(), 'new-ref-post_ref', false);
		//$appendix = "<br> etiquetas: ";
		$appendix = "";
		$prueba_uno = get_post_meta($post->ID, '_prueba_uno', true);
		$metabox_meta = get_post_meta($post->ID);
		$tags = wp_get_post_tags($post->ID);
		$tag_post_value;
		
		///////////////////////////////////////////////////////////////////
		///////////////MUESTRA DE ETIQUETAS SELECCIONADAS//////////////////
		echo '<ul id="wkrf-tag-references">';
		foreach ($tags as $tag_to_load){
		//	http://www.lets-develop.com/html5-html-css-css3-php-wordpress-jquery-javascript-photoshop-illustrator-flash-tutorial/php-programming/remove-div-by-class-php-remove-div-contents/
			$name = str_replace(" ", "_", $tag_to_load->name);
			if(isset($metabox_meta['_'.$name])){  //si existe
				$tag_post_value = get_post_meta($post->ID, '_'.$name, true);
				if($tag_post_value == "yes"){	//si estaba marcado
					if(strpos($content, 'id="wkrf-tag-'.$name.'"') == false){  //si no estaba ya en la lista de tags
						$appendix = $appendix.' <li id="wkrf-tag-'.$name.'">'.$tag_to_load->name.'</li>';
					}
				}
				else{ //si no estaba marcado lo eliminamos
					//jquery, javascript, php???
					if(strpos($content, 'id="wkrf-tag-'.$name.'"') == true){
						$content = preg_replace('#<li id="wkrf-tag-'.$name.'">(.*?)</li>#', " ", $content);
					}
					
				}
			}
			else{    // si no existe un metacampo involuctrado
				if(strpos($content, 'id="wkrf-tag-'.$name.'"') == true){  //si está en la lista de checkboxes
					$content = preg_replace('#<li id="wkrf-tag-'.$name.'">(.*?)</li>#', " ", $content);
				}
			}
			
		}
		echo '</ul>';
		//////////////////////////////////////////////////////////////////////

		
		
		$prueba_box = get_post_meta($post->ID, '_prueba_box', true);
		//return $content. $appendix." + <br> ".$prueba_uno;
		return $content.$appendix;
		//return $content. " <br> prueba box: ".$prueba_box;
		
		
		
		
		
		
		
		
		
		
	}
	
	
	
	
	function say_hello(){
		echo "helloo";
	}
	
	function save_function(){
		//https://www.sitepoint.com/handling-post-requests-the-wordpress-way/
		//https://wordpress.stackexchange.com/questions/138737/using-multiple-submit-buttons-to-trigger-customised-php-functions
		
		/*https://tommcfarlin.com/sending-data-post/*/
		/*
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
		
		
	} 
	
	
	
	
}
