var Modularity = Modularity || {};
Modularity.Posts = Modularity.Posts || {};

Modularity.Posts.LoadMoreButton = (function ($) {
    function LoadMoreButton() {
        this.init();
    }

    LoadMoreButton.prototype.init = function() {
        $(document).on('click', '.js-mod-posts-load-more', function(e) {
            var button = $(e.target).closest('.js-mod-posts-load-more');
           
            var attributes = JSON.parse(window.atob(button.attr('data-mod-posts-load-more')));
            //Make sure required attributes exists
            var requiredKeys = ['postsPerPage', 'offset', 'target', 'ajaxUrl', 'nonce', 'bladeTemplate'];
            if (!this.attributesExists(requiredKeys, Object.keys(attributes))) {
                return;
            }

            //Make sure target exists
            var target = $(attributes.target)[0];
            if (typeof(target) === 'undefined') {
                throw 'Error: Could not find target "' + attributes.target + '"';
                return;
            }

            this.toggleLoader(button);
            this.loadMorePosts(button, target, attributes);

        }.bind(this));
    };

    LoadMoreButton.prototype.toggleLoader = function(button)
    {
        if (button.hasClass('hidden')) {
            button.removeClass('hidden');

            this.removeLoader(button);

            return;
        }

        button.addClass('hidden');

        //Create loader
        button.after('<div class="loading"><div></div><div></div><div></div><div></div></div>');
    };
    
    /**
     * Load more posts
     * @param button
     * @param target
     * @param attributes
     * @param rawdata
     */
    LoadMoreButton.prototype.loadMorePosts = function(button, target, attributes)
    {

        var data = attributes;
        data.action = 'mod_posts_load_more';
        
        $.ajax({
            type : "post",
            url : data.ajaxUrl,
            data : data,
            dataType: "html",
            success : function(posts, status) {

                if (status === 'success') {
                    
                    //Append posts
                    $(target).append(JSON.parse(posts));

                    //Remove button if number of posts is less then queried post count
                    if (attributes.postsPerPage > posts.length) {
                        this.removeLoader(button);
                        button.remove();
                        return;
                    }

                    this.toggleLoader(button);

                    //Increment offset
                    attributes.offset = parseInt(attributes.offset) + parseInt(attributes.postsPerPage);
                    button.attr('data-mod-posts-load-more', window.btoa(JSON.stringify(attributes)));
                    return;
                }

                if (status  === 'nocontent') {
                    this.removeLoader(button);
                    button.after('<p>No more posts to show…</p>');
                    button.remove();
                    return;
                }

            }.bind(this),
            error : function(jqXHR, status, error) {
                console.log(error);
            }
        });
    }

    LoadMoreButton.prototype.removeLoader = function(button) {
        if ($(button.next()).hasClass('loading')) {
            button.next().remove();
        }
    };

    LoadMoreButton.prototype.attributesExists = function(requiredKeys, attributeKeys) {
        var exists = true;

        requiredKeys.forEach(function(key) {
            if (!attributeKeys.includes(key)) {
                exists = false;

                throw 'ValidationError: Missing required data-attribute key "' + key + '"';
            }
        });

        return exists;
    };

    return new LoadMoreButton();

})(jQuery);
