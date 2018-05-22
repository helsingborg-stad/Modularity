<?php

namespace Modularity\Module\Posts;

class PostsFilters
{
    public $moduleId;
    public $postType;
    public $taxonomies;
    public $taxonomyType;

    public function __construct($module)
    {
        $this->moduleId = $module->ID;
        $this->postType = get_field('posts_data_post_type', $this->moduleId);
        $this->taxonomies = get_field('taxonomy_display', $this->moduleId);
        $this->taxonomyType = get_field('posts_taxonomy_type', $this->moduleId);


        //add_filter('template_include', array($this, 'enableSearch'), 10);

        remove_action('pre_get_posts', array($this, 'doPostTaxonomyFiltering'));
        add_filter('posts_where', array($this, 'doPostDateFiltering'), 10, 2);
        add_action('pre_get_posts', array($this, 'doPostTaxonomyFiltering'));
        add_action('pre_get_posts', array($this, 'doPostOrdering'));
        add_action('pre_get_posts', array($this, 'getSearchQuery'));
        remove_filter('content_save_pre', 'wp_filter_post_kses');
        remove_filter('excerpt_save_pre', 'wp_filter_post_kses');
        remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');

        add_filter('query_vars', array($this, 'newQueryVars'));
    }

    /**
     * Register custom query vars
     * @param array $vars The array of available query variables
     */
    public function newQueryVars($vars)
    {
        $vars[] = 'search';
        return $vars;
    }


    /**
     * Do taxonomy fitering
     * @param  object $query Query object
     * @return object        Modified query
     */
    public function doPostTaxonomyFiltering($query)
    {
        //Only run on frontend
        if (is_admin()) {
            return $query;
        }

        $postType = $this->postType;
        $filterable = $this->getEnabledTaxonomies($postType);

        if (empty($filterable)) {
            return $query;
        }

        $taxQuery = array('relation' => 'AND');

        foreach ($filterable as $key => $value) {
            if (!isset($_GET['filter'][$key]) || empty($_GET['filter'][$key]) || $_GET['filter'][$key] === '-1') {
                continue;
            }

            $terms = (array)$_GET['filter'][$key];

            $taxQuery[] = array(
                'taxonomy' => $key,
                'field' => 'slug',
                'terms' => $terms,
                'operator' => 'IN'
            );
        }

        if (is_tax() || is_category() || is_tag()) {
            $taxQuery = array(
                'relation' => 'AND',
                array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => get_queried_object()->taxonomy,
                        'field' => 'slug',
                        'terms' => (array)get_queried_object()->slug,
                        'operator' => 'IN'
                    )
                ),
                $taxQuery
            );
        }

        $query->set('tax_query', $taxQuery);
        $query->set('post_type', get_field('posts_data_post_type', $this->moduleId));

        return $query;
    }

    /**
     * Get filterable taxonomies
     * @return array Taxonomies
     */
    public function getEnabledTaxonomies($group = true)
    {
        $grouped = array();
        $ungrouped = array();
        $taxonomies = $this->taxonomies;

        if (!$taxonomies) {
            return array();
        }

        // Hide category filter if displaying a category
        if (is_category()) {
            $taxonomies = array_filter($taxonomies, function ($item) {
                return $item !== 'category';
            });
        }

        // Hide taxonomy if displaying a taxonomy
        if (is_a(get_queried_object(), 'WP_Term')) {
            $taxonomies = array_diff($taxonomies, (array) get_queried_object()->taxonomy);
        }

        foreach ($taxonomies as $key => $item) {
            $tax = get_taxonomy($item);
            $terms = get_terms($item, array(
                'hide_empty' => false
            ));


            $placement = $this->taxonomyType;
            if (is_null($placement)) {
                $placement = 'secondary';
            }

            $type = $this->taxonomyType;

            $grouped[$placement][$tax->name] = array(
                'label' => $tax->label,
                'type' => $type,
                'slug' => $item,
                'values' => $terms
            );

            $ungrouped[$tax->name] = array(
                'label' => $tax->label,
                'type' => $type,
                'values' => $terms
            );
        }

        if ($group) {
            $grouped = json_decode(json_encode($grouped));
            return $grouped;
        }

        return $ungrouped;
    }


    /**
     * Use correct template when filtering a post type archive
     * @param  string $template Template path
     * @return string           Template path
     */
    public function enableSearch($template)
    {
        $template = \Municipio\Helper\Template::locateTemplate($template);

        //if ((is_post_type_archive() || is_category() || is_date() || is_tax() || is_tag() || is_page() || is_single()) && is_search()) {
        $archiveTemplate = \Municipio\Helper\Template::locateTemplate('archive-' . $this->postType . '.blade.php');

        if (!$archiveTemplate) {
            $archiveTemplate = \Municipio\Helper\Template::locateTemplate('archive.blade.php');
        }

        $template = $archiveTemplate;
        //}

        return $template;
    }


    /**
     * Returns escaped search query
     * @return string Search query
     */
    public function getSearchQuery($query)
    {
        $searchQuery = '';
        /*if (!empty(get_search_query())) {
            $searchQuery = get_search_query();
        } elseif (!empty($_GET['search'])) {
            $searchQuery = esc_attr($_GET['search']);
        }

        var_dump($searchQuery);
        */


        // check if the user is requesting an admin page
        // or current query is not the main query
        /*if ( is_admin() || ! $query->is_main_query() ){
            return;
        }*/

        $query->set('post_title',  array(
            array(
                'key' => 'search',
                'value' => get_query_var('search'),
                'compare' => 'LIKE'
            )
        ));

        $query->set('post_content', array(
            array(
                'key' => 'search',
                'value' => get_query_var('search'),
                'compare' => 'LIKE'
            )
        ));


        //var_dump($query);
        return $query;
    }

    /**
     * Get the current post slug.
     * @return string
     */
    public function getPostUrl()
    {
        $pageId = get_the_ID();
        $post = get_post($pageId);
        return $post->post_name;
    }

    /**
     * Trying to sort terms natural
     * @param $terms
     * @return array
     */
    public static function sortTerms($terms)
    {
        $sort_terms = array();
        foreach ($terms as $term) {
            $sort_terms[$term->name] = $term;
        }
        uksort($sort_terms, 'strnatcmp');

        return $sort_terms;
    }

    public static function getMultiTaxDropdown($tax, int $parent = 0, string $class = '')
    {
        $termArgs = array(
            'hide_empty' => false,
            'parent' => $parent
        );

        $terms = get_terms($tax->slug, $termArgs);
        $terms = self::sortTerms(get_terms($tax->slug, $termArgs));

        $inputType = $tax->type === 'single' ? 'radio' : 'checkbox';

        $html = '<ul';

        if (!empty($class)) {
            $html .= ' class="' . $class . '"';
        }

        $html .= '>';

        foreach ($terms as $term) {
            $isChecked = isset($_GET['filter'][$tax->slug]) && ($_GET['filter'][$tax->slug] === $term->slug || in_array($term->slug,
                        $_GET['filter'][$tax->slug]));
            $checked = checked(true, $isChecked, false);

            $html .= '<li>';
            $html .= '<label class="checkbox">';
            $html .= '<input type="' . $inputType . '" name="filter[' . $tax->slug . '][]" value="' . $term->slug . '" ' . $checked . '> ' . $term->name;
            $html .= '</label>';

            $html .= self::getMultiTaxDropdown($tax, $term->term_id);
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Get filter options as list (refined getMultiTaxDropdown())
     * @return string unordered list of terms as checkbox/radio
     */
    public static function getFilterOptionsByTax($tax, int $parent = 0, string $class = '')
    {
        $termArgs = array(
            'hide_empty' => false,
            'parent' => $parent
        );

        $terms = get_terms($tax->slug, $termArgs);

        if (!isset($terms) || !is_array($terms) || empty($terms)) {
            return;
        }

        $inputType = $tax->type === 'single' ? 'radio' : 'checkbox';

        $html = '<ul';

        if (!empty($class)) {
            $html .= ' class="' . $class . '"';
        }

        $html .= '>';

        foreach ($terms as $term) {
            $isChecked = isset($_GET['filter'][$tax->slug]) && ($_GET['filter'][$tax->slug] === $term->slug || in_array($term->slug,
                        $_GET['filter'][$tax->slug]));
            $checked = checked(true, $isChecked, false);

            $html .= '<li>';
            $html .= '<input id="filter-option-' . $term->slug . '" type="' . $inputType . '" name="filter[' . $tax->slug . '][]" value="' . $term->slug . '" ' . $checked . '>';
            $html .= '<label for="filter-option-' . $term->slug . '" class="checkbox">';
            $html .= $term->name;
            $html .= '</label>';

            $html .= self::getMultiTaxDropdown($tax, $term->term_id);
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Add where clause to post query based on active filters
     * @param  string $where Original where clause
     * @return string        Modified where clause
     */
    public function doPostDateFiltering($where, $query)
    {

        //Only run on frontend
        if (is_admin() || $query->is_main_query()) {
            return $where;
        }

        global $wpdb;

        $from   = null;
        $to     = null;

        if (isset($_GET[$this->moduleId . 'f']) && !empty($_GET[$this->moduleId . 'f'])) {
            $from = sanitize_text_field($_GET[$this->moduleId . 'f']);
        }

        if (isset($_GET[$this->moduleId . 't']) && !empty($_GET[$this->moduleId . 't'])) {
            $to = sanitize_text_field($_GET[$this->moduleId . 't']);
        }

        if (!is_null($from) && !is_null($to)) {
            $where .= " AND ($wpdb->posts.post_date >= '$from' AND $wpdb->posts.post_date <= '$to')";
        } elseif (!is_null($from) && is_null($to)) {
            $where .= " AND ($wpdb->posts.post_date >= '$from')";
        } elseif (is_null($from) && !is_null($to)) {
            $where .= " AND ($wpdb->posts.post_date <= '$to')";
        }

        return $where;
    }

    /**
     * Do post ordering for archives
     * @param  object $query Query
     * @return object        Modified query
     */
    public function doPostOrdering($query)
    {
        // Do not execute this in admin view
        if (is_admin() || !$query->is_main_query()) {
            return $query;
        }

        $isMetaQuery = false;

        $posttype = $query->get('post_type');
        if (empty($posttype)) {
            $posttype = 'post';
        }

        // Get orderby key, default to post_date
        $orderby = (isset($_GET['orderby']) && !empty($_GET['orderby'])) ? sanitize_text_field($_GET['orderby']) : get_field('archive_' . sanitize_title($posttype) . '_sort_key',
            'option');
        if (empty($orderby)) {
            $orderby = 'post_date';
        }

        if (in_array($orderby, array('post_date', 'post_modified', 'post_title'))) {
            $orderby = str_replace('post_', '', $orderby);
        } else {
            $isMetaQuery = true;
        }

        // Get orderby order, default to desc
        $order = (isset($_GET['order']) && !empty($_GET['order'])) ? sanitize_text_field($_GET['order']) : get_field('archive_' . sanitize_title($posttype) . '_sort_order',
            'option');
        if (empty($order) || !in_array(strtolower($order), array('asc', 'desc'))) {
            $order = 'desc';
        }

        $query->set('order', $order);

        // Return if not meta query
        if (!$isMetaQuery) {
            $query->set('orderby', $orderby);
            return $query;
        }

        if (isset($_GET['orderby']) && $_GET['orderby'] == 'meta_key' && isset($_GET['meta_key']) && !empty($_GET['meta_key'])) {
            $orderby = sanitize_text_field($_GET['meta_key']);
        }

        // Continue if meta query
        $query->set('meta_key', $orderby);
        $query->set('orderby', 'meta_value');

        return $query;
    }
}
