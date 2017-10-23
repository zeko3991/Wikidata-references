<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- https://www.sitepoint.com/create-a-wordpress-theme-settings-page-with-the-settings-api/ -->




<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	
	<form method="post" name="wiki_references_options" action="options.php">
	<?php 
		settings_fields($this->plugin_name); 
		do_settings_sections($this->plugin_name);	
	?>
	<?php 
	
		$tags = get_tags();
		//Grab all setup options
		$options = get_option($this->plugin_name);
		
		//Wiki references options
		$references_by_tag = $options['references_by_tag'];
		$references_footnote = $options['references_footnote'];	
		
		//Reference formats options
		
		$ieee_format;
		$harvard_format;
		$simple_format;
		
		//array of tags-wiki ids values
		//$
		
		
		if(isset($options['ieee_format'])){
			$ieee_format = $options['ieee_format'];
		}
		else{
			$ieee_format = 0;
		}
		
		if(isset($options['harvard_format'])){
			$harvard_format = $options['harvard_format'];
		}
		else{
			$harvard_format= 0;
		}
		
		if(isset($options['simple_format'])){
			$simple_format= $options['simple_format'];
		}
		else{
			$simple_format= 0;
		}
		
		if(!isset($options['ieee_format']) && !isset($options['harvard_format']) && !isset($options['simple_format'])){
			$ieee_format = 1;
			$options['ieee_format'] = 1;
			$options['harvard_format'] = 0;
			$options['simple_format'] = 0;
		}
		
		
	?>
	
	
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ///////////////////////////////////ASSOCIATIONS//////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
		<h2><?php echo _e("Wikidata Associations")?></h2>
		 <span class="help-block"><?php echo __("Set a Wikidata ID to your tags and categories") ?></span>  
		 <p>
		<div class="wkrf-setup">
		
		<?php 
		foreach ($tags as $elem){
			$name = str_replace(" ", "_", $elem->name);
			$id = $name;
			//$tag_post_value = get_post_meta($post->ID, '_'.$name, true);
			wp_nonce_field( 'save_'.$name, $name.'_nonce');
			?>
			 
			
			<div class="wkrf-tag-form col-xl-4 col-md-4 col-xs-12 left input-group input-group-sm"  >
				<label class="col-md-7 col-xs-7" ><?php echo $elem->name?></label>
				<input id="<?php echo $this->plugin_name.'_tag_'.$name; ?>"
					name="<?php echo $this->plugin_name.'_tag_['.$name.']';?>
					type="text" class="col-md-4 col-xs-4" placeholder="Wikidata ID#">
				<span  title="Look for a wikidata item related to the term <?php echo $elem->name;?>" 
					class="input-group-addon wkrf-association-icon " style="cursor:pointer" 
					onclick="wkrf_modal_selection('<?php echo $elem->name;?>', '<?php echo $this->plugin_name.'_tag_'.$name; ?>')">
						<i class="fa fa-search"></i>
				</span>
			</div>
			
			
			
			
			
				<?php 
			}
				?>
		</div>
		
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ///////////////////////////////////REFERENCIAS///////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
		<div class="wkrf-setup">
		<br>
		<hr>
		<h2><?php echo __("Referencias")?></h2>
		<!-- Activates/deactivates references by tag -->
    	
    	<fieldset>
	        <legend class="screen-reader-text">
	            <span><?php __("Activar referencias por etiquetas del post")?></span>
	        </legend>
	        <label for="<?php echo $this->plugin_name; ?>-references_by_tag">
	            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-references_by_tag" name="<?php echo $this->plugin_name; ?>[references_by_tag]" value="1" <?php checked($references_by_tag, 1); ?> />
	            <span><?php esc_attr_e('Activar referencias por etiquetas del post', $this->plugin_name); ?></span>
	        </label>
    	</fieldset>
    	
    	<!--  Activates/deactivates footnotes -->
    	<fieldset>
	        <legend class="screen-reader-text">
	            <span><?php __("Activar notas al pie")?></span>
	        </legend>
	        <label for="<?php echo $this->plugin_name; ?>-references_footnote">
	            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-references_footnote" name="<?php echo $this->plugin_name; ?>[references_footnote]" value="1" <?php checked($references_footnote, 1); ?> />
	            <span><?php esc_attr_e('Activar notas al pie', $this->plugin_name); ?></span>
	        </label>
    	</fieldset>
    	<br>
    	<hr>
    	</div>
    	
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ///////////////////////////////////FORMATOS//////////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	
    	<h2><?php echo __("Formatos")?></h2>
    	
    	
    	
    	<fieldset>
    		<h3><?php echo __("IEEE Referencing System")?></h3>
    		<legend class="screen-reader-text">
    			<span><?php echo __('Seleccionar formato de referencias IEEE')?></span>
    		</legend>
    		<input type="radio" id="<?php echo $this->plugin_name; ?>-ieee_format" 
    					name="<?php echo $this->plugin_name; ?>[ieee_format]"
    					<?php checked($ieee_format, 1)?> 
    					onclick="updateIeeeFormat(this);" 
    		/>
    		<label for="<?php echo $this->plugin_name; ?>-ieee_format">
    			<?php echo __(' [1] "Term", <i>Wikidata</i>, 2015. [Online]. 
									Available: <a href="https://www.wikidata.org/wiki/Q2013">
									https://www.wikidata.org/wiki/Q2013</a>.
									Accessed: 24-jul-2017', $this->plugin_name); ?>
			</label>
    		
    	</fieldset>
    	
    	<fieldset>
    		<h3><?php echo __("Harvard Referencing System")?></h3>
    		<legend class="screen-reader-text">
    			<span><?php echo __('Seleccionar formato de referencias Harvard')?></span>
    		</legend>
    		<input type="radio" id="<?php echo $this->plugin_name; ?>-harvard_format"
    					name="<?php echo $this->plugin_name; ?>[harvard_format]" 
    					<?php checked($harvard_format, 1)?> 
    					onclick="updateHarvardFormat(this);"
    		/>
    		<label for="<?php echo $this->plugin_name; ?>-harvard_format">
    			<?php echo __('  "Term", <i>Wikidata</i>, Wikimedia Foundation. 19 September 2017.
									Viewed 21 September 2017, 
									from <a href="https://www.wikidata.org/wiki/Q2013">
									https://www.wikidata.org/wiki/Q2013</a>.'
									, $this->plugin_name); ?>
			</label>
    		
    	</fieldset>
    	

    	<fieldset>
    		<h3><?php echo __("Simple")?></h3>
    		<legend class="screen-reader-text">
    			<span><?php echo __('Seleccionar formato de referencias simple')?></span>
    		</legend>
    		<input type="radio" id="<?php echo $this->plugin_name; ?>-simple_format" 
    					name="<?php echo $this->plugin_name; ?>[simple_format]"
    					<?php checked($simple_format, 1)?> 
    					onclick="updateSimpleFormat(this);" 
    		/>
    		<label for="<?php echo $this->plugin_name; ?>-simple_format">
    			<?php echo __(' "Term", 
								<a href="https://www.wikidata.org/wiki/Q2013">
								https://www.wikidata.org/wiki/Q2013</a>.
								Accessed: 24-jul-2017', $this->plugin_name); ?>
			</label>
    		
    	</fieldset>
    	<br>
    	<hr>
    	
		
		<?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>
		
		
	</form>

</div>


<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- ///////////////////////////////////MODAL WINDOW//////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- The Modal -->
<div class="wrap">
	
	<div id="wkrf-modal-window" class="modal">
	  <!-- Modal content -->
	  <div id="wkrf-modal-window-content" class="modal-content  col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
	    <span id="wkrf-close" class="close col-md-offset-10" onclick="wkrf_modal_selection_close()">&times;</span>
	    <!-- <p>Some text in the Modal..</p> -->
		    <div class="wkrf-modal-list-header col-md-12 row">
		    	<div class="col-md-3 col-xs-3"><h6>Tag name</h6></div>	
		    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
		    	<div class="col-md-7 col-xs-7"><h6>Description </h6></div>   
		    </div>

		
	  </div>
	
	</div>
</div>



<div class="wrap">
	<fieldset>
		<legend class="screen-reader-text"><span><?php _e('Edit post', $this->plugin_name)?></span></legend>
		
	</fieldset>

</div>