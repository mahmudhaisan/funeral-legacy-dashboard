<!-- Hero Area -->

<div class="single-legacy-funeral-content ">
    <div class="container px-4">
        <div class="row flex-lg-row-reverse align-items-center">
            <div class="col-10 col-sm-8 col-lg-6">
                <?php
                if (has_post_thumbnail()) {
                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . get_the_title() . '" class="hero-image">';
                }
                ?>
            </div>
            <div class="col-lg-6">
                <h1 class="display-4"><?php echo get_the_title(); ?></h1>
                <p class="lead"><?php echo get_field('person_description'); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Tab area -->
<div id="funeral-field-tabs" class="bg-light p-3">
    <div class="container">
        <ul class="nav nav-fill" role="tablist">
            <li class="nav-item me-2" role="presentation">
                <a class="nav-link p-4 active" id="fill-tab-0" data-bs-toggle="tab" href="#orbituary-tabpanel" role="tab" aria-controls="orbituary-tabpanel" aria-selected="true">
                    <i class="fas fa-book me-2"></i> <!-- FontAwesome icon -->
                    Orbituary
                </a>
            </li>
            <li class="nav-item me-2" role="presentation">
                <a class="nav-link p-4" id="fill-tab-1" data-bs-toggle="tab" href="#funeral-service-tabpanel" role="tab" aria-controls="funeral-service-tabpanel" aria-selected="false">
                    <i class="fas fa-church me-2"></i> <!-- FontAwesome icon -->
                    Funeral Service
                </a>
            </li>
            <li class="nav-item me-2" role="presentation">
                <a class="nav-link p-4" id="fill-tab-2" data-bs-toggle="tab" href="#family-memories-tabpanel" role="tab" aria-controls="family-memories-tabpanel" aria-selected="false">
                    <i class="fas fa-users me-2"></i> <!-- FontAwesome icon -->
                    Family Memories
                </a>
            </li>
            <li class="nav-item me-2" role="presentation">
                <a class="nav-link p-4" id="fill-tab-3" data-bs-toggle="tab" href="#legacy-wall-tabpanel" role="tab" aria-controls="legacy-wall-tabpanel" aria-selected="false">
                    <i class="fas fa-history me-2"></i> <!-- FontAwesome icon -->
                    Legacy Wall
                </a>
            </li>
        </ul>
        <div class="tab-content pt-3" id="tab-content">
            <div class="tab-pane active" id="orbituary-tabpanel" role="tabpanel" aria-labelledby="fill-tab-0">
                <?php echo (get_field('person_info_funeral_obituary', get_the_ID())); ?>
            </div>
            <div class="tab-pane" id="funeral-service-tabpanel" role="tabpanel" aria-labelledby="fill-tab-1">

                <?php
                $funeral_service_data = get_field('funeral_service', $post_id);

                // Define a mapping of keys to labels
                $key_labels = array(
                    'funeral_service_date' => 'Service Date',
                    'funeral_service_time' => 'Service Time',
                    'funeral_service_location' => 'Service Location',
                    'funeral_service_phone_number' => 'Phone Number',
                    'funeral_service_dress_attire' => 'Dress Attire',
                    'funeral_service_favorite_flowers' => 'Favorite Flowers',
                    'funeral_service_religion_observed' => 'Religion Observed',
                    'funeral_service_live_streamed_details' => 'Live Streamed Details',
                );

                // Check if the field has a value
                if ($funeral_service_data) {
                    // Iterate through the array
                    foreach ($funeral_service_data as $key => $value) {
                        // Display the label and value if it exists in the mapping, otherwise use the original key
                        $label = isset($key_labels[$key]) ? $key_labels[$key] : $key;

                        if($key== 'funeral_service_live_streamed_details'){
                        
                            echo " <a class='live-link' href='$value'>$label</a><br>";
                            break;
                        }
                        echo "<strong>$label <br> </strong> $value<br>";
                    }
                } else {
                    // The field is empty
                    echo 'No value found for funeral_service';
                }
                ?>


            </div>
            <div class="tab-pane" id="family-memories-tabpanel" role="tabpanel" aria-labelledby="fill-tab-2">Tab 3 selected</div>
            <div class="tab-pane" id="legacy-wall-tabpanel" role="tabpanel" aria-labelledby="fill-tab-3">Tab 4 selected</div>
        </div>
    </div>
</div>