<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

	<ul>
	@if(! isset($items['error']))
		@foreach($items as $item)
			<li>
				@if(\Modularity\Module\Rss\Rss::getRssLink($item->get_link()))
					<a href="{{ \Modularity\Module\Rss\Rss::getRssLink($item->get_link()) }}">
						<span class="link-item title">{{ \Modularity\Module\Rss\Rss::getRssTitle($item->get_title()) }}</span>
					</a>
				@else
					<span class="title">{{ \Modularity\Module\Rss\Rss::getRssTitle($item->get_title()) }}</span>
				@endif

				@if($date && $item->get_date('U'))
					<time class="date text-sm text-dark-gray">{{ date_i18n(get_option('date_format'), $item->get_date('U')) }}</time>
				@endif

				@if($summary && \Modularity\Module\Rss\Rss::getRssSummary($item->get_description()))
					<p>{{ \Modularity\Module\Rss\Rss::getRssSummary($item->get_description()) }}</p>
				@endif

				@if($author && \Modularity\Module\Rss\Rss::getRssAuthor($item->get_author()))
					<p>{{ \Modularity\Module\Rss\Rss::getRssAuthor($item->get_author()) }}</p>
				@endif
            </li>
		@endforeach
	@else
		<li class="notice warning">
			<i class="pricon pricon-notice-warning"></i> {{ $items['error'] }}
		</li>
	@endif
	</ul>
</div>
