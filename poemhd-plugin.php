<?php
/**
* @package PoemhdPlugin
*/
/*
 * Plugin Name:       Poemhd Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Krithi Krishna
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       poemhd-plugin
 * Domain Path:       /languages
 */
 /*
 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 Copyright 2005-2015 Automattic, Inc.
 */
?>
<script type="text/javascript" language="javascript" src="js/val.js"> </script>
	<script type="text/javascript" language="javascript" src="js/jquery.js"> </script>

<?php

defined( 'ABSPATH' ) or die('Hey,you can\t access this file!' );

// function which add metabox
function add_book_meta_box() {

    add_meta_box(
        'global-notice',
        __( 'Book Review', 'sitepoint' ),
        'book_meta_box_callback',
        'post'
    );
}

add_action( 'add_meta_boxes', 'add_book_meta_box' );

function add_book_meta_box1() {

    $screens = array( 'post', 'page', 'book' );

    foreach ( $screens as $screen ) {
        add_meta_box(
            'global-notice',
            __( 'Book Review', 'sitepoint' ),
            'book_meta_box_callback',
            $screen
        );
    }
}

add_action( 'add_meta_boxes', 'add_book_meta_box1' );

function add_book_meta_box2() {

    add_meta_box(
        'global-notice',
        __( 'Book Review', 'sitepoint' ),
        'book_meta_box_callback'
    );

}

add_action( 'add_meta_boxes', 'add_book_meta_box2' );

function add_book_meta_box3() {

    add_meta_box(
        'global-notice',
        __( 'Book Review', 'sitepoint' ),
        'book_meta_box_callback'
    );

}

add_action( 'add_meta_boxes_book', 'add_book_meta_box3' );

function book_cpt() {

    $args = array(
        'label'                => 'Books',
        'public'               => true,
        'register_meta_box_cb' => 'add_book_meta_box'
    );

    register_post_type( 'book', $args );
}

add_action( 'init', 'book_cpt' );

function add_book_meta_box4() {

    add_meta_box(
        'global-notice',
        __( 'Book Review', 'sitepoint' ),
        'book_meta_box_callback'
    );

}

//function to display fields inside metabox
function book_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'global_notice_nonce', 'global_notice_nonce' );

    $value = get_post_meta( $post->ID, '_book_meta', true );
    ?>
    <form action="" method="POST" enctype="multipart/form-data" name="form">
      <?php
    echo '<textarea style="width:100%" id="book_meta" name="book_meta" required>' . esc_attr( $value ) . '</textarea>';
    ?>
    <br>
<br>

    <label for="meta-checkbox">Check Box</label>
    <?php
        $checkbox_value = get_post_meta($post->ID, "meta-checkbox", true);

        if($checkbox_value == "")
        {
            ?>
                <input name="meta-checkbox" type="checkbox" value="true">

            <?php
        }
        else if($checkbox_value == "true")
        {
            ?>
                <input name="meta-checkbox" type="checkbox" value="true" checked>
                <?php


        }
?>
</form>
<?php
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id
 */
function save_book_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['global_notice_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['global_notice_nonce'], 'global_notice_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['book_meta'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['book_meta'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_book_meta', $my_data );

    if(isset($_POST["meta-checkbox"]))
    {
        $meta_box_checkbox_value = $_POST["meta-checkbox"];
    }
    update_post_meta($post_id, "meta-checkbox", $meta_box_checkbox_value);
}

add_action( 'save_post', 'save_book_meta_box_data' );


function global_book_post( $content ) {

    global $post;
    $book1 = esc_attr( get_post_meta( $post->ID, 'meta-checkbox', true ) );
    if ($book1 == "true")
    // retrieve the global notice for the current post
    {
    // retrieve the global notice for the current post
    $book = esc_attr( get_post_meta( $post->ID, '_book_meta', true ) );

    $notice = "<div class='sp_book_meta'>$book</div>";

    return $notice . $content;

}
else{

  return $content;
}
}

add_filter( 'the_content', 'global_book_post' );

//function to get lyrics
function Poemhd_plugin_get_lyric() {
	/** These are the lyrics to Hello Dolly */
	$lyrics = "Go, lovely Rose —
Tell her that wastes her time and me,
That now she knows,
When I resemble her to thee,
How sweet and fair she seems to be.
Tell her that's young,
And shuns to have her graces spied,
That hadst thou sprung
In deserts where no men abide,
Thou must have uncommended died.
Small is the worth
Of beauty from the light retired:
Bid her come forth,
Suffer herself to be desired,
And not blush so to be admired.
Then die — that she
The common fate of all things rare
May read in thee;
How small a part of time they share
That are so wondrous sweet and fair!";

	// Here we split it into lines.
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line.
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later.
function poemhd_plugin() {
	$chosen = Poemhd_plugin_get_lyric();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="dolly"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from Hello Dolly song, by Jerry Herman:' ),
		$lang,
		$chosen
	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_shortcode( 'heaven', 'poemhd_plugin' );

if( !class_exists( 'PoemhdPlugin' ) ) {

  class PoemhdPlugin
  {

    function __construct() {
      add_action( 'init', array( $this,'custom_post_type') );
    }

    function register() {
      add_action( 'admin_enqueue_scripts', array( $this,'enqueue') );
    }

    function activate() {
      $this->custom_post_type();
      flush_rewrite_rules();
    }

    function deactivate() {
      flush_rewrite_rules();
    }

    function custom_post_type() {
      register_post_type( 'book', ['public' => true, 'label' => 'Books', 'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')] );
    }

    function  enqueue() {
      wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/mystyle.css', __FILE__) );
      wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/myscript.js', __FILE__) );
    }
  }

  if ( class_exists( 'PoemhdPlugin' ) ) {
    $poemhdPlugin = new PoemhdPlugin();
    $poemhdPlugin->register();
  }

//plugin_activation

  register_activation_hook(__FILE__ , array( $poemhdPlugin, 'activate' ) );

//plugin_deactivation

  register_deactivation_hook(__FILE__ , array( $poemhdPlugin, 'deactivate' ) );

}
//uninstall
