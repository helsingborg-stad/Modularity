<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <table class="table table-bordered" data-table-filter="{{ $listId }}">
        @if ($columns)
        <thead>
            <tr>
                <th><?php _e('File', 'modularity'); ?></th>
                @foreach ($columns as $column)
                <th>{{ $column['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        @endif

        <tbody>
            @if ($showFilters)
            <tr data-table-filter-exclude>
                <td colspan="{{ is_array($columns) ? count($columns) + 1 : 1 }}" class="no-padding no-border">
                    <input aria-label="<?php _e('Filter files', 'modularity'); ?>…" type="text" name="keyword" class="form-control gutter" placeholder="<?php _e('Filter files', 'modularity'); ?>…" style="margin: -1px;margin-top: 0;width: calc(100% + 2px);" data-table-filter-input="{{ $listId }}">
                </td>
            </tr>
            @endif

            @foreach ($files as $item)
            <tr>
                <td>
                    <a class="link-item" href="{{ $item['file']['url'] }}" title="{{ $item['file']['title'] }}">
                        {{ $item['file']['title'] }}
                        ({{ pathinfo($item['file']['url'], PATHINFO_EXTENSION) }}, {{ size_format(filesize(get_attached_file($item['file']['ID'])), 2) }})
                    </a>

                    @if (isset($item['file']['description']) && !empty($item['file']['description']))
                        {!! wpautop($item['file']['description']) !!}
                    @endif
                </td>

                @if (!empty($columns))
                @foreach ($columns as $column)
                <td>
                    {{ isset($item['columns'][$column['key']]) && !empty($item['columns'][$column['key']]) ? $item['columns'][$column['key']] : '' }}
                </td>
                @endforeach
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
