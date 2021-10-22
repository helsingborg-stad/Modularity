@form([
    'method' => 'POST',
    'action' => $form->action
])
    <div class="o-grid">
      <div class="o-grid-12">
        @field([
          'type' => 'text',
          'attributeList' => [
              'type' => 'text',
              'name' => 'sitename',
          ],
          'label' => "Site name"
        ])
        @endfield

        @typography(["variant" => "meta"])
          {{ $lang->description->sitename }}
        @endtypography
      </div>

      <div class="o-grid-12">
        @field([
          'type' => 'text',
          'attributeList' => [
              'type' => 'text',
              'name' => 'siteslug',
          ],
          'label' => "Site slug"
        ])
        @endfield

        @typography(["variant" => "meta"])
          {{ $lang->description->siteslug }}
        @endtypography
        
      </div>

      <div class="o-grid-12">
        @field([
          'type' => 'text',
          'attributeList' => [
              'type' => 'text',
              'name' => 'useremail',
          ],
          'label' => "Email"
        ])
        @endfield

        @typography(["variant" => "meta"])
          {{ $lang->description->siteslug }}
        @endtypography
        
      </div>

      <div class="o-grid-12">
        @button([
          'text' => $lang->formsubmit,
          'color' => 'primary',
          'type' => 'basic'
        ])
        @endbutton
      </div>
    </div>
@endform