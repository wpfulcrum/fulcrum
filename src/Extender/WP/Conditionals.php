<?php

namespace Fulcrum\Extender\WP;

class Conditionals
{
    /**
     * Checks if the current web page request is for the Posts Page, i.e
     * the page that displays the posts.
     *
     * Please note: This page can be configured as the site's root page
     * (i.e. set as the front page) or as a static page.  See WPHelpers::isRootPage()
     * for more details.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isPostsPage()
    {
        return is_home();
    }

    /**
     * Checks if the web page is the configured static front page.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isStaticFrontPage()
    {
        return !is_home() && is_front_page();
    }

    /**
     * Checks if the current web page request is for the website's root page.
     *
     * The root page is based entirely upon how the website
     * has `Settings > Reading > Front page display` configured.
     * Therefore, we check the configuration and then look for
     * the various state conditions to determine if the web page
     * is the root.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isRootPage()
    {
        if (is_admin()) {
            return false;
        }

        $isHome        = is_home();
        $isFrontPage   = is_front_page();
        $currentPageId = (int) get_current_web_page_id();

        // No static page is used; rather, the root page
        // is a listing of latest blog posts.  It will use
        // the `front-page.php` template file.
        if (FrontPageDisplays::isSetToYourLatestPosts()) {
            return $isHome && $isFrontPage && ($currentPageId === 0);
        }

        // Just the static Posts Page is configured.  The latest
        // blog posts will be displayed using the `home.php`
        // template file.
        if (FrontPageDisplays::isSetToStaticPostsPage()) {
            return $isHome && !$isFrontPage && ($currentPageId === 0);
        }

        // A static front page is configured. It will use the
        // `front-page.php` template file.
        return !$isHome && $isFrontPage && ($currentPageId === (int) get_option('page_on_front', 0));
    }
}
