<?php
   /*
   *Plugin Name: custom-plugin
   *Plugin URI:
   *description: >- To add custom post type book and taxonomies author and type 
   *Version: 1.0
   *Author: dipali
   *Tags: Custom Post-type, Custom Taxonomy, Isotope with wordpress
 
   */
   //exit if accessed directly
if(! defined('ABSPATH') ) exit;

   // Our custom post type function Book

   function book_init() {
    //Label part for GUI
    $labels = array(
    'name' => esc_html__('Books', 'themedomain' ),
    'singular_name' => esc_html__('Book ',
    'themedomain' ),
    'add_new' => esc_html__('Add New Book', 'themedomain'),
    'add_new_item' => esc_html__('Add New Book',
    'themedomain' ),
    'edit_item' => esc_html__('Edit Book',
    'themedomain' ),
    'new_item' => esc_html__('Add New Book',
    'themedomain' ),
    'view_item' => esc_html__('View Book', 'themedomain' ),
    'search_items' => esc_html__('Search Book',
    'themedomain' ),
    'not_found' => esc_html__('No Books found',
    'themedomain' ),
    'not_found_in_trash' => esc_html__('No Books
    found in trash', 'themedomain' )
    );

// Set other options for Custom Post Type
    $args = array(
    'labels' => $labels,
    'public' => true,
    'show_ui' => true,
    'menu_icon' => 'dashicons-paperclip',
    'show_in_menu'=>true,
    'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'author', 'custom-fields', 'revisions'
    ),

    'hierarchical' => false,
    'rewrite' => array( 'slug' => sanitize_title(
    'Book' ), 'with_front' => false ),
    'menu_position' => 5,
    'has_archive' => true
    );
     // Registering Custom Post Type Book
    register_post_type( 'book', $args );
}
//Hook into the 'init' action
add_action( 'init', 'book_init' );


//hook into the init action and call create_Types_nonhierarchical_taxonomy when it fires

add_action( 'init', 'create_types_taxonomy', 0 );
 
function create_types_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Types', 'taxonomy general name' ),
    'singular_name' => _x( 'Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'popular_items' => __( 'Popular Types' ),
    'all_items' => __( 'All Types' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Type' ), 
    'update_item' => __( 'Update Type' ),
    'add_new_item' => __( 'Add New Type' ),
    'new_item_name' => __( 'New Type Name' ),
    'separate_items_with_commas' => __( 'Separate Types with commas' ),
    'add_or_remove_items' => __( 'Add or remove Types' ),
    'choose_from_most_used' => __( 'Choose from the most used Types' ),
    'menu_name' => __( 'Types' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('type','book',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'Type' ),
  ));
}

//author taxonomy
//hook into the init action and call create_author_taxonomy when it fire
 
add_action( 'init', 'create_author_taxonomy', 0 );
 
function create_author_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Authors', 'taxonomy general name' ),
    'singular_name' => _x( 'Author', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Authors' ),
    'popular_items' => __( 'Popular Authors' ),
    'all_items' => __( 'All Authors' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Author' ), 
    'update_item' => __( 'Update Author' ),
    'add_new_item' => __( 'Add New Author' ),
    'new_item_name' => __( 'New Topic Author' ),
    'separate_items_with_commas' => __( 'Separate authors with commas' ),
    'add_or_remove_items' => __( 'Add or remove authors' ),
    'choose_from_most_used' => __( 'Choose from the most used authors' ),
    'menu_name' => __( 'Authors' ),
  ); 
 
// Now register the non-hierarchical taxonomy author
 
  register_taxonomy('author','book',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'author' ),
  ));
}

  /*
  *shortcode for showing books
  *with filtering by Taxonomies type and author
  *Arranging and filtering using isotope
  */
 
add_shortcode('isotope-filter',function($atts,$content=null){
  wp_enqueue_script('isotope-js',plugin_dir_url( __FILE__ ) .'/isotope/isotope.pkgd.min.js',array(),true);
  //wp_enqueue_script('isotope-js','https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',array(),true);
  wp_enqueue_style( 'style1-css', plugin_dir_url( __FILE__ ) .'/css/style1.css' );
  ?>

  <div>
    <input type="text" class="filterInput" id="filterInput" placeholder="Search for books..">
  </div>
<div class="isotope_wrapper">
  <div>

      <h5> FILTER BY TYPE</h5>
      <div class="button-group filter-buttons-group" >
        <button class="button is-checked " data-filter="*">show all</button>

        <?php 
          $terms = get_terms( array( 
              'taxonomy' => 'type',
              'hide_empty' => false) ); 
           
            foreach ( $terms as $term )
              {   ?>
                <button data-filter=".<?php echo $term->slug; ?>" ><?php echo $term->name; ?></button>
                <?php 
              }
            ?>    
      </div>

    <!-- Button for author -->
      <h5> FILTER BY AUTHOR</h5>
      <div class="button-group filter-button-group">
        <button class="button is-checked" data-filter="*">show all</button>
        <?php  
          $terms = get_terms( array( 
              'taxonomy' => 'author',
              'hide_empty' => false) ); 

            foreach ( $terms as $term ) 
              {?>
              <button data-filter=".<?php echo $term->slug; ?>" ><?php echo $term->name; ?></button>
              <?php
              }
        ?>    
       </div>
  </div>

    <div id="gridbox" class="grid">
        <?php  $args=array(
              'post_type'=> 'book',
            'posts_per_page' => 6,
            'status'  => 'published');
            $query=new WP_Query($args);

        // The Loop
        if ( $query->have_posts() ) 
        {
            while ( $query->have_posts() )
             {
                $query->the_post();?>

                <div class="grid-item
                <?php isotope_classes(get_the_id()); ?>">
                <a href="<?php the_permalink(); ?>">
                 <h5 class="title"><?php the_title() ;?></h5>
                </a>
              </div>    
       <?php
            }
        } 
        else {
            // no Books found
            ?><h1>Sorry...</h1>
          <p><?php _e('Sorry, no books found.'); ?></p>
          <?php
            } ?>
    </div>
</div>

<?php
/* Restore original Post Data */
wp_reset_postdata();?>
<script>
 
  window.addEventListener('load',function(){

      var iso= new Isotope('.grid',{
          itemSelector:'.grid-item',
          layoutMode:'fitRows',
          getSortData:{
          name: '.title'}
      });
        // bind filter button click
        var filtersElem = document.querySelector('.filter-buttons-group');
        //adding eventListener to button
        filtersElem.addEventListener('click', function (event){
        //only work with button
        if(!matchesSelector(event.target, 'button') ){
          return;
        }
        var filterValue = event.target.getAttribute('data-filter');
        //use matching filter function
        iso.arrange({filter:filterValue });
        });  
        var filtersElem = document.querySelector('.filter-button-group');

        filtersElem.addEventListener('click', function (event){
            //only work with button
            if(!matchesSelector(event.target, 'button') ){
              return;
            }
            var filterValue = event.target.getAttribute('data-filter');
            //use matching filter function
            iso.arrange({filter:filterValue });
        });

      //dynamic search function

      //stoing filter input element in a variable
      let filterInput=document.getElementById("filterInput");
      //adding eventLidtener to filterInput element
      filterInput.addEventListener('keyup',filterTitles);
      //Defining filterTitles function
      function filterTitles(){
          let filterValue=document.getElementById('filterInput').value.toUpperCase();
          let gridbox=document.getElementById('gridbox');
          let griditem=gridbox.querySelectorAll(".grid-item");
          //loop through grad-items 
          for(var i=0;i<griditem.length;i++)
          {
              let title=griditem[i].getElementsByTagName('h5')[0];
              txtValue = title.textContent || title.innerText;
              if(txtValue.toUpperCase().indexOf(filterValue) > -1){
                  //console.log(title);
                  griditem[i].style.display="";
              }
              else{
                  griditem[i].style.display='none';
              }
          }
      }
});
</script>

<?php

 });
//
//Isotope function(isotop_classes) to get taxonomy slug
  function isotope_classes($id)
    {
        $author_terms = wp_get_post_terms(get_the_id(), array('author'));
        foreach ( $author_terms as $author_term ){
            echo $author_term->slug.' ';
          }

        $type_terms= wp_get_post_terms( get_the_id(), array( 'type' ) ); 
        foreach ( $type_terms as $type_term ) {
            echo $type_term->slug.' '; 
          }
    }

//Shortcode for sorting by isotope
add_shortcode('isotope-sort',function($atts,$content=null)
{
  
    wp_enqueue_script('isotope-js','https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',array(),true);
    //wp_enqueue_style('bootstrap-css','https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css',array(),true);
    //wp_enqueue_script('bootstrap-js','https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js');
    wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) .'/css/style1.css' );
    ?>
    <div >
        <input type="text" class="filterInput" id="filterInput" placeholder="Search for books">
    </div>
    <div class="isotope_wrapper">
      <div>
        <div class="button-group sort-by-button-group">
            <h5> Sort By--------</h5>
            <button class="button is-checked" data-sort-by="original-order">Recency</button>
            <button  class="button is-checked" data-sort-by="name">name</button>
        </div>
      </div>
        <div id="gridbox" class="grid">
          <?php 
          //global $wp_query;
            $args=array(
                'post_type'=> 'book',
                'posts_per_page' => 5,
                'status'  => 'published',
                'paged' => $paged);
                
            $wp_query=new WP_Query($args);
          // The Loop
          if ( $wp_query->have_posts() ) {
              while ( $wp_query->have_posts() ) 
              {
                  $wp_query->the_post();?>
                  <div class="grid-item
                      <?php isotope_classes(get_the_id()); ?>">
                      <a href="<?php the_permalink(); ?>">
                          <h5 class="title"><?php the_title() ;?></h5>
                      </a>
                  </div>
              <?php
              }
              //global $wp_query;
              //echo $wp_query->max_num_pages;
              if(  $wp_query->max_num_pages > 1 ){
                  //Don't display the button if there are not enough posts
                  echo '<div id="my_loadmore" class="my_loadmore">More posts</div>'; // you can use <a> as well

              }
          }
          else {
              // no posts found
              echo "No Books found";
          }?>
        </div>
    </div>
        <?php
        /* Restore original Post Data */
        wp_reset_postdata();
        ?>

  <script>
    window.addEventListener('load',function(){
        var iso= new Isotope('.grid',{
          itemSelector:'.grid-item',
          layoutMode:'fitRows',
          getSortData:{
          name: '.title'}
        });
        //  Sorting Function
        // bind sort button click
        var sortByGroup = document.querySelector('.sort-by-button-group');
        sortByGroup.addEventListener( 'click', function( event ) { 
              // only button clicks
              if ( !matchesSelector( event.target, '.button' ) ) {
                //console.log(23);
                return;
              }
              var sortValue = event.target.getAttribute('data-sort-by');
              iso.arrange({ sortBy: sortValue });
         });

      //dynamic search
        let filterInput=document.getElementById("filterInput");
        filterInput.addEventListener('keyup',filterTitles);
        function filterTitles()
        {
            let filterValue=document.getElementById('filterInput').value.toUpperCase();
            //console.log(filterValue);
            let gridbox=document.getElementById('gridbox');
            let griditem=gridbox.querySelectorAll(".grid-item");
            //loop through grad-items 
            for(var i=0;i<griditem.length;i++)
            {
                let title=griditem[i].getElementsByTagName('h5')[0];
                txtValue = title.textContent || title.innerText;
                if(txtValue.toUpperCase().indexOf(filterValue) > -1){
                    //console.log(title);
                    griditem[i].style.display="";
                }
                else{
                    griditem[i].style.display='none';
                }
            }
        }
    });
  </script>
 
  <?php

});

  add_action( 'wp_footer', 'mi_my_load_more_scripts' );
  function mi_my_load_more_scripts()
   {

        // In most cases it is already included on the page and this line can be removed
        wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',array('jquery'),true);
        wp_register_script('jquery', plugin_dir_url( __FILE__ ) . '/js/jquery-3.5.1.min.js' ,array(),true);
        wp_enqueue_script('jquery');
        // register our main script but do not enqueue it yet
        wp_register_script( 'my_loadmore', plugin_dir_url( __FILE__ ) . '/js/myloadmore.js', array('jquery'),true);

        // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
        wp_localize_script( 'my_loadmore', 'mi_loadmore_params', array(
            // WordPress AJAX
            'ajaxurl' => admin_url( 'admin-ajax.php' ),  //site_url() . '/wp-admin/admin-ajax.php', 
            'posts' =>  json_encode($wp_query->query_vars) , // everything about your loop is here
            'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
            'max_page' => $wp_query->max_num_pages
        ) );
        wp_enqueue_script( 'my_loadmore' );
   } 
  function mi_loadmore_ajax_handler()
  { 
      // prepare our arguments for the query
      $args = json_decode( stripslashes( $_POST['query'] ), true );
      $args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
      $args['post_status'] = 'publish';
     
      query_posts( $args );
      if( have_posts() ) :
        // run the loop
        while( have_posts() ): the_post();
          ?>
            <div class="grid-item
                <?php isotope_classes(get_the_id()); ?>">
                <a href="<?php the_permalink(); ?>">
                 <h5 class="title"><?php the_title() ;?></h5>
               </a>
            </div>
          <?php   
        endwhile;
      endif;
      die; // here we exit the script and even no wp_reset_query() required!
  }
 
  add_action('wp_ajax_loadmore', 'mi_loadmore_ajax_handler'); // wp_ajax_{action}
  add_action('wp_ajax_nopriv_loadmore', 'mi_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

  ?>
