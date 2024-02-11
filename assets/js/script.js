jQuery(document).ready(function ($) {

    // alert('working');

    $('#load-more-videos').on('click', function () {
        var page = $(this).data('page');
        var postsPerPage = funeral_legacy_wall.legacy_video_per_page; // Number of posts to load per page
        var offset = (page ) * postsPerPage; // Calculate offset
        var ajaxurl = funeral_legacy_wall.ajaxurl;
        var post_id = $('#legacy-video-id').val();

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'load_more_videos',
                page: page,
                post_id: post_id,
                postsPerPage: postsPerPage,
                offset: offset // Send the offset to the server
            },
            success: function (response) {

                console.log(response);

                $('.legacy-videos').append(response);
                $('#load-more-videos').data('page', page + 1);
                if (response == '') {
                    $('.no-more-video-text').empty().html('No more videos available');
                    $('#load-more-videos').hide();
                }
            }
        });
    });


    $('#load-more-walls').on('click', function () {
       
        var page = $(this).data('page');
        var postsPerPage = funeral_legacy_wall.legacy_wall_per_page; // Number of posts to load per page
        var offset = (page ) * postsPerPage; // Calculate offset
        var ajaxurl = funeral_legacy_wall.ajaxurl;
        var post_id = $('#legacy-wall-id').val();

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'load_more_walls',
                page: page,
                post_id: post_id,
                postsPerPage: postsPerPage,
                offset: offset // Send the offset to the server
            },
            success: function (response) {

                console.log(response);

                $('.legacy-walls').append(response);
                $('#load-more-walls').data('page', page + 1);
                if (response == '') {
                    $('.no-more-wall-post').empty().html('No more walls available');
                    $('#load-more-walls').hide();
                }
            }
        });
    });



});



