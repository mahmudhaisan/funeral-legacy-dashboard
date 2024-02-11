<?php

$legacy_gallery_per_page = get_option('legacy_gallery_per_page', 3);

// Get the current post ID
$post_id = get_the_ID();

// Get the attachment IDs from post meta
$published_gallery_images = get_post_meta($post_id, 'legacy_gallery_published', true);


print_r($published_gallery_images);


?>

<div class="container my-5">
    <h1 class="text-center mb-5">Legacy Photos Gallery</h1>
    <!-- Image Gallery -->
    <div class="row g-4 legacy-gallery">
        <?php
        // Display only the first 2 images initially
        $num_initial_images = $legacy_gallery_per_page;
        $displayed_images = 0;

        if ($published_gallery_images) {
            $attachment_ids = explode(',', $published_gallery_images);
            foreach ($attachment_ids as $attachment_id) {
                if ($displayed_images >= $num_initial_images) {
                    break;
                }
                echo '<div class="col-md-3 legacy-photo">';
                echo wp_get_attachment_image($attachment_id, 'thumbnail'); // Adjust 'thumbnail' to your desired image size
                echo '</div>';
                $displayed_images++;
            }
        } else {
            echo '<p>No images found.</p>';
        }
        ?>
    </div>


    <!-- Load More Button -->
    <div class="center-button text-center mt-5">

    <div class="mt-2 mb-2" id="nothing-found">
        
    </div>
        <?php
        // Get the current URL and append the necessary parameters for adding a new photo
        $current_url = home_url();
        $new_url = $current_url . '/upload-legacy-gallery/';
        $post_id = get_the_ID();
        ?>
        <button id="load-more" class="btn btn-primary text-white mb-3">Load More</button>
        <a href="<?php echo $new_url; ?>?add-legacy-parts&action=photos&post-id=<?php echo $post_id; ?>" class="btn btn-primary text-white mb-3">Add New Photo</a>

    </div>
</div>





<script>
    jQuery(document).ready(function($) {
        var offset = <?php echo $num_initial_images; ?>; // Initial offset to load more images
        var perPage = <?php echo $legacy_gallery_per_page; ?>; // Number of images to load per request

        $('#load-more').on('click', function() {
            var postId = <?php echo $post_id; ?>;
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'load_more_images',
                    offset: offset,
                    perPage: perPage,
                    postId: postId
                },
                success: function(response) {
                    if (response) {
                        $('.legacy-gallery').append(response);
                        offset += perPage;
                    } else {

                        $('#nothing-found').empty().text('All images loaded. No new Images found.');
                        $('#load-more').hide(); // Hide the button if no more images to load
                    }
                }
            });
        });
    });
</script>