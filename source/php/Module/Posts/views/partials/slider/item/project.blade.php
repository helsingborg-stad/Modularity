@card([
    'image' => $post->thumbnail['src'],
    'link' => $post->permalink,
    'heading' => $post->postTitle,
    'content' => $post->project->taxonomies,
    'meta' => $post->project->category,
    'metaFirst' => true,
    'context' => ['archive', 'archive.list', 'archive.list.card'],
    'containerAware' => true,
    'hasPlaceholder' => !empty($post->hasPlaceholderImage),
    'attributeList' => ['style' => 'z-index:' . (999 - $key) . ';'],
    'classList' => ['u-height--100', 'project-card'],
    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
])
    @slot('afterContent')
        @if(!empty($post->project->statusBar))
        <div class="u-margin__top--auto u-width--100">
            {{-- TODO: fix tooltip overflow --}}
            @tooltip([
                'label' => $post->project->statusBar['label'],
                'placement' => 'bottom',
                'classList' => ['u-justify-content--end']
            ])
                {{-- {{ $post->project->statusBar['explainer'] }} --}}
            @endtooltip
            @progressBar([
                'value' => $post->project->statusBar['value'],
                'isCancelled' => $post->project->statusBar['isCancelled']
            ])
            @endprogressBar
        </div>
        @endif
    @endslot
@endcard