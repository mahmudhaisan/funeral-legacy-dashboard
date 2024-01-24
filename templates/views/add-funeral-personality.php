

<div class="add-funeral-personality">
    <div class="container">
        <h2>Add Funeral Personality</h2>
        <form method="post" action="" enctype="multipart/form-data">
           
             <!-- Person's Name -->
            <div class="mb-3">
                <label for="charityImage" class="form-label">Person's Name</label>
                <input type="text" class="form-control" id="personsName" name="persons_name">
            </div>

            <!-- Person's Image -->
            <div class="mb-3">
                <label for="charityImage" class="form-label">Person's Image</label>
                <input type="file" class="form-control" id="personsImage" name="persons_image">
            </div>

            <!-- Short Description -->
            <div class="mb-3">
                <label for="shortDescription" class="form-label">Short Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shortDescription" name="short_description" requireds>
            </div>

            <!-- Obituary -->
            <div class="mb-3">
                <label for="obituary" class="form-label">Obituary <span class="text-danger">*</span></label>
                <textarea class="form-control" id="obituary" name="obituary" rows="4" requireds></textarea>
            </div>

            <!-- Music Link -->
            <div class="mb-3">
                <label for="musicLink" class="form-label">Music Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="musicLink" name="music_link" requireds>
            </div>

            <!-- Funeral Service Date -->
            <div class="mb-3">
                <label for="serviceDate" class="form-label">Funeral Service Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="serviceDate" name="service_date" requireds>
            </div>

            <!-- Funeral Service Time -->
            <div class="mb-3">
                <label for="serviceTime" class="form-label">Funeral Service Time <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="serviceTime" name="service_time" requireds>
            </div>

            <!-- Funeral Service Location -->
            <div class="mb-3">
                <label for="serviceLocation" class="form-label">Funeral Service Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="serviceLocation" name="service_location" requireds>
            </div>

            <!-- Funeral Service Phone Number -->
            <div class="mb-3">
                <label for="servicePhoneNumber" class="form-label">Funeral Service Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="servicePhoneNumber" name="service_phoneNumber" requireds>
            </div>

            <!-- Funeral Service Dress Attire -->
            <div class="mb-3">
                <label for="dressAttire" class="form-label">Funeral Service Dress Attire <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dressAttire" name="dress_attire" requireds>
            </div>

            <!-- Funeral Service Favorite Flowers -->
            <div class="mb-3">
                <label for="favoriteFlowers" class="form-label">Funeral Service Favorite Flowers <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="favoriteFlowers" name="favorite_flowers" requireds>
            </div>

            <!-- Funeral Service Religion Observed -->
            <div class="mb-3">
                <label for="religionObserved" class="form-label">Funeral Service Religion Observed <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="religionObserved" name="religion_observed" requireds>
            </div>


             <!-- Funeral Service Religion Observed -->
             <div class="mb-3">
                <label for="liveStreamed" class="form-label"> Funeral Service Live Streamed Details <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="liveStreamed" name="live_streamed_link" requireds>
            </div>



            <!-- Charity Name -->
            <div class="mb-3">
                <label for="charityName" class="form-label">Charity Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="charityName" name="charity_name" requireds>
            </div>

            <!-- Charity Details -->
            <div class="mb-3">
                <label for="charityDetails" class="form-label">Charity Details</label>
                <textarea class="form-control" id="charityDetails" name="charity_details" rows="4"></textarea>
            </div>

            <!-- Charity Image - Image -->
            <div class="mb-3">
                <label for="charityImage" class="form-label">Charity Image - Image</label>
                <input type="file" class="form-control" id="charityImage" name="charity_image">
            </div>

            <!-- Charity Link -->
            <div class="mb-3">
                <label for="charityLink" class="form-label">Charity Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="charityLink" name="charity_link" requireds>
            </div>

            <!-- Submit Button -->
            <button type="submit"  name="funeral_submit_button" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>