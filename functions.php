<?php
/* Add this code to your functions.php or plugin file */


add_action('admin_notices', function () {
    global $wpdb;
    $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%_GFCache_%'");
    if (!$wpdb->num_rows) {
        return;
    }
    ?>
    <div class="notice-warning notice">
        <form method="get">
            <input type="hidden" name="cleargfcache" value="true">
            In case if Gravity Forms are locked: 
            <input type="submit" value="Flush Gravityforms Cache" >
        </form>
    </div>
    <?php
});

add_action('admin_init', function() {

    global $wpdb;
    $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%_GFCache_%'");
    if (!$wpdb->num_rows) {
        return;
    }
    if (isset($_GET['cleargfcache']) && $_GET['cleargfcache'] === "true") {
        add_action('admin_notices', function () {
			global $wpdb;
           $result = $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%_GFCache_%'");
            ?>
            <div class="notice <?php echo $result ? "updated" : "notice-warning"; ?>">
                <form method="get">
                    Gravity Forms Cache Clean <?php echo $result ? "was successfull" : "failed"; ?>
                </form>
            </div>
            <?php
        });
    }
});
