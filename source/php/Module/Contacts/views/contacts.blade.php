<div class="{{ $classes }}" itemscope="person" itemtype="http://schema.org/Organization">

    @if ($thumbnail !== false)
    <img class="box-image" src="{{ $thumbnail[0] }}" alt="{{ $first_name }} {{ $last_name }}">
    @endif

    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <div class="box-content">
        <h5 itemprop="name">{{ $first_name }} {{ isset($last_name) && !empty($last_name) ? $last_name : '' }}</h5>
        <ul>
            @if ((isset($title) && !empty($title)) || (isset($organization) && !empty($organization)))
            <li class="card-title">
                <span itemprop="jobTitle">{{ (isset($title) && !empty($title)) ? $title : '' }}</span>
                <span itemprop="department">{{ (isset($organization) && !empty($organization)) ? $organization : '' }}</span>
            </li>
            @endif

            @if (isset($phone_number) && !empty($phone_number))
            <li><a itemprop="telephone" class="link-item" href="tel:{{ $phone_number }}">{{ $phone_number }}</a></li>
            @endif

            @if (isset($email) && !empty($email))
            <li><a itemprop="email" class="link-item truncate" href="mailto:{{ $email }}">{{ $email }}</a></li>
            @endif

            @if (!empty($module->post_content))
            <li class="small description">{!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content)) !!}</li>
            @endif
       </ul>
    </div>
</div>
