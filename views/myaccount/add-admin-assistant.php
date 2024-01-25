
<?php

handle_admin_assistant_form();


// Generate a unique identifier (you can use any method suitable for your application)
$unique_identifier = 'admin_assistant';
?>

<h2>Add Admin Assistant</h2>
<form method="post" action="">
    <!-- Username -->
    <div class="mb-3">
        <label for="<?php echo $unique_identifier; ?>_username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="<?php echo $unique_identifier; ?>_username" name="username" required>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="<?php echo $unique_identifier; ?>_password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="<?php echo $unique_identifier; ?>_password" name="password" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="<?php echo $unique_identifier; ?>_email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="<?php echo $unique_identifier; ?>_email" name="email" required>
    </div>

    <!-- Submit Button -->
    <div class="mb-3">
        <button type="submit" name="add-admin-assistant-submit" class="btn btn-primary">Add Admin Assistant</button>
    </div>
</form>