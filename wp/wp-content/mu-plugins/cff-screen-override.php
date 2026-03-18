<?php
/**
 * Normalize Smash Balloon screen IDs so the builder loads correctly.
 * This makes the plugin compatible with custom admin menu slugs.
 */

add_filter('current_screen', function($screen) {
    if (! $screen) {
        return $screen;
    }

    // Your environment uses: facebook-feed_page_cff-feed-builder
    // Smash Balloon expects:  cff-feed-builder
    if (strpos($screen->id, 'cff-feed-builder') !== false) {
        $screen->id = 'cff-feed-builder';
    }

    return $screen;
});
