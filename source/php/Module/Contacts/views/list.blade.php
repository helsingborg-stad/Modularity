<div class="box no-padding">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <div class="accordion accordion-icon accordion-list">

	<?php
		$uniqueId = rand(0, 1000);
	?>

    @foreach ($contacts as $key => $contact)
    <section class="accordion-section" itemscope="person" itemtype="http://schema.org/Organization">
        <input type="radio" name="active-section" id="accordion-contacts-{{ $ID }}-{{ $key }}-{{ $uniqueId }}">
        <button class="accordion-toggle" for="accordion-contacts-{{ $ID }}-{{ $key }}-{{ $uniqueId }}">
            <h6 itemprop="name">{{ $contact['full_name'] }}</h6>
        </button>
        <div class="accordion-content">

            <ul>

                @if ((isset($contact['work_title']) && !empty($contact['work_title'])) || (isset($contact['administration_unit']) && !empty($contact['administration_unit'])))
                    <li class="card-title">
                        <span itemprop="jobTitle">{{ (isset($contact['work_title']) && !empty($contact['work_title'])) ? $contact['work_title'] : '' }}</span>
                        <span itemprop="department">{{ (isset($contact['administration_unit']) && !empty($contact['administration_unit'])) ? $contact['administration_unit'] : '' }}</span>
                    </li>
                @endif

                @if (isset($contact['phone']) && !empty($contact['phone']))
                @foreach ($contact['phone'] as $phone)
                    <li><a itemprop="telephone" class="link-item" href="tel:{{ $phone['number'] }}">{{ $phone['number'] }}</a></li>
                @endforeach
                @endif

                @if (isset($contact['email']) && !empty($contact['email']))
                <li><a itemprop="email" class="link-item truncate" href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>
                @endif

                @if (!empty($module->post_content))
                    <li class="small description">{!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content, $this->ID)) !!}</li>
                @endif
                </ul>

                @if (isset($contact['address']) && !empty($contact['address']))
                <div class="gutter gutter-top small">
                    {!! $contact['address'] !!}
                </div>
                @endif

                @if (isset($contact['visiting_address']) && !empty($contact['visiting_address']))
                <div class="gutter gutter-top small">
                    {!! $contact['visiting_address'] !!}
                </div>
                @endif

                @if (isset($contact['other']) && !empty($contact['other']))
                <div class="gutter gutter-top small">
                    {!! $contact['other'] !!}
                </div>
                @endif
           </ul>
        </div>
    </section>
    @endforeach
    </div>
</div>
