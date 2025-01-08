<?php

namespace Modularity\Private;

class AcfPrivateSettings
{
    public function __construct()
    {
        add_action('post_submitbox_misc_actions', array($this, 'addUserVisibilitySelect'), 10);
        add_action('attachment_submitbox_misc_actions', array($this, 'addUserVisibilitySelect'), 10);
        add_action('save_post', array($this, 'saveUserVisibilitySelect'));
        add_action('edit_attachment', array($this, 'saveUserVisibilitySelect'));
    }

    public function saveUserVisibilitySelect($postId)
    {
        if (empty($_POST['user-group-visibility'])) {
            delete_post_meta($postId, 'user-group-visibility');
            return;
        }

        $combined = array_combine($_POST['user-group-visibility'], $_POST['user-group-visibility']);

        update_post_meta($postId, 'user-group-visibility', $combined);
    }

    public function addUserVisibilitySelect()
    {
        global $post;
    
        if (
            empty($post->post_type) ||
            empty($terms = get_terms(
                [
                    'taxonomy' => 'user_group',
                    'hide_empty' => false
                ]
            ))
        ) {
            return;
        }
    
        $checked = get_post_meta($post->ID, 'user-group-visibility', true) ?: [];
    
        echo '
        <div id="user-group-visibility" class="misc-pub-section">
            <label>' . __('User group visibility', 'municipio') . '</label>
            <br><br>
        ';
    
        foreach ($terms as $term) {
            echo '
            <label style="display: block; margin-bottom: 5px;">
                <input type="checkbox" name="user-group-visibility[]" value="' . $term->slug . '" ' . (in_array($term->slug, $checked) ? 'checked' : '') . '>
                ' . $term->name . '
            </label>
            ';
        }
        
        echo '
        </div>
        ';
    }
    
}
