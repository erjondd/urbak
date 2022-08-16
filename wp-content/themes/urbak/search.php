<?php

get_header();
?>

<?php
global $wp_query;

echo $query_string;
$query_args = explode(" ", $query_string);
$search_query = array();

foreach ($query_args as $key => $string) {
    $query_split = explode("=", $string);
    $search_query[$query_split[0]] = $query_split[1];
} // foreach

$search = new WP_Query($search_query);

$loop = new WP_Query($search);
var_dump($loop);
$posts = $search->posts;

foreach ($posts as $post) {

    print_r($post);

    if ($post->post_type === "page") {
?>
        <div class="search-found">

            <h3>Page found<h3>
                    <div class="search-found-content">
                        <a href="<?= the_permalink() ?>"><?= $post->post_title ?></a>
                    </div>
        </div>

<?php
    }
    print_r($post);
}




wp_reset_postdata();
?>

<?php

get_footer();

?>