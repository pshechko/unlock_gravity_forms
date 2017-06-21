<?php
/* Add this code to your functions.php or plugin file */

function gf_cache_exists() {
    global $wpdb;
    $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%_GFCache_%'");
    return $wpdb->num_rows > 0;
}

function gf_cache_remove() {
    global $wpdb;
    return $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%_GFCache_%'");
}

add_action('admin_notices', function () {
    if (!gf_cache_exists())
        return;
    ?>
    <div class="notice-warning notice">
        <form method="get">
            <input type="hidden" name="cleargfcache" value="true">
            In case if Gravity Forms are locked 
            <input class="button button-primary button-large" type="submit" value="Flush Gravityforms Cache" >
        </form>
    </div>
    <?php
});

add_action('admin_init', function() {
    if (!gf_cache_exists())
        return;
    if (isset($_GET['cleargfcache']) && $_GET['cleargfcache'] === "true") {
        add_action('admin_notices', function () {
            $result = gf_cache_remove();
            ?>
            <div class="notice <?php echo $result ? "updated" : "notice-warning"; ?>">
                <form method="get">
                    Gravity Forms Cache Clean <?php echo $result ? "was successfull" : "failed"; ?>
                </form>
            </div>
            <?php
        }, 1);
    }
});
