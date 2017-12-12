<?php

namespace Fulcrum\Extender\WP;

class FrontPageDisplays
{
    /**
     * Cache options.
     *
     * @var array
     */
    protected static $options = [];

    /**
     * Clears the options cache.
     *
     * @since 3.1.0
     *
     * @return void
     */
    public static function clearOptionsCache()
    {
        self::$options = [];
    }

    /**
     * Get the static posts page's ID.
     *
     * @since 3.1.0
     *
     * @return int
     */
    public static function getStaticPostsPageID()
    {
        return self::getOption('page_for_posts');
    }

    /**
     * Get the static front page's ID.
     *
     * @since 3.1.0
     *
     * @return int
     */
    public static function getStaticFrontPageID()
    {
        return self::getOption('page_on_front');
    }

    /**
     * Front page displays: Your latest posts
     *
     *      get_option( 'show_on_front' ) === 'posts'
     *      get_option( 'page_on_front' ) === 0
     *      get_option( 'page_for_posts' ) == 0
     *      is_home() is true
     *      is_front_page() is true
     *      get_queried_object_id() is 0
     *      uses the front-page.php template file
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isSetToYourLatestPosts()
    {
        return self::getOption('show_on_front', false) === 'posts';
    }

    /**
     * Front page displays: A static page
     *  - Front page: not selected
     *  - Posts page: set to a static page
     *
     *      get_option( 'show_on_front' ) === 'page'
     *      get_option( 'page_on_front' ) === 0
     *      get_option( 'page_for_posts' ) === the static page's ID
     *      is_home() is true
     *      is_front_page() is false
     *      get_queried_object_id() is 0
     *
     * Static posts page uses home.php template file.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isSetToStaticPostsPage()
    {
        return (self::getOption('show_on_front', false) === 'page') &&
               (self::getOption('page_on_front') === 0) &&
               (self::getOption('page_for_posts') > 0);
    }

    /**
     * Front page displays: A static page
     *  - Front page: set to a static page.
     *  - Posts page: doesn't matter.
     *
     *      get_option( 'show_on_front' ) === 'page'
     *      get_option( 'page_on_front' ) === the static page's ID
     *      get_option( 'page_for_posts' ) ==  Doesn't matter.
     *      is_home() is false
     *      is_front_page() is true
     *      get_queried_object_id() is === the static page's ID
     *
     * Static front page uses front-page.php template file.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isSetToStaticFrontPage()
    {
        return (self::getOption('show_on_front', false) === 'page') &&
               (self::getOption('page_on_front') > 0);
    }

    /**
     * Front page displays: A static page
     *  - Front page: set to a page
     *  - Posts page: set to a page
     *
     *      get_option( 'show_on_front' ) === 'page'
     *      get_option( 'page_on_front' ) === the static page's ID
     *      get_option( 'page_for_posts' ) === the static page's ID
     *
     * When on the root page, which is the "front page", here are the conditions:
     *      is_home() is false
     *      is_front_page() is true
     *      get_queried_object_id() === the static page's ID
     *      Uses front-page.php template file.
     *
     * When on the Posts Page, here are the conditions:
     *      is_home() is true
     *      is_front_page() is false
     *      get_queried_object_id() === the static page's ID
     *      Uses home.php template file.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    public static function isSetForBothStatics()
    {
        return (self::getOption('show_on_front', false) === 'page') &&
               (self::getOption('page_on_front') > 0) &&
               (self::getOption('page_for_posts') > 0);
    }

    /*****************************
     * Private/protected helpers
     ****************************/

    /**
     * Get the specified option.
     *
     * @since 3.1.0
     *
     * @param string $optionName Key for the option.
     * @param bool $castToInt When true, cast the option's value to integer before returning.
     *
     * @return mixed
     */
    protected static function getOption($optionName, $castToInt = true)
    {
        $optionValue = isset(self::$options[$optionName])
            ? self::$options[$optionName]
            : get_option($optionName);

        return $castToInt === true ? (int) $optionValue : $optionValue;
    }
}
