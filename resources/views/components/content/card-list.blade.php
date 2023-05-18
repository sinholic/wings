<div>
    <?php 
        // Check if we in a nested table view with model_url or not
        $back_button = isset(Route::current()->parameters['model_url']) ? true : false;
    ?>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    <?php  //Check if we add the options for enble add and the enable add is true ?>
    @if(isset($options['enable_add']) && $options['enable_add'])
        <div class="row mb-3">
            <div class="col-md-12">
                <?php
                // 
                ?>
                @if($back_button)
                    <?php $model_url = Route::current()->parameters['model_url']; ?>
                    {{ link_to_route(Route::current()->controller->back_from_list, 'Back', null,['class' => 'btn btn-warning']) }}
                @endif
                @if(is_array($options['enable_add']))
                    @if($show_add)
                        <a href="{{ route($options['enable_add']['action'], ($options['enable_add']['params'] ?? null)) }}" class="btn btn-primary">Add</a>
                    @endif
                @else
                    <a href="{{ route($route_as_name.'.create') }}" class="btn btn-primary">Add</a>
                @endif
            </div>
        </div>
    @endif
    <div class="row">
        @foreach($datas as $data)
            <div class="col-md-2">
                <div class="card">
                    @foreach($contents as $content)
                        @if(is_array($content))
                            <?php 
                                $field      =   $content['field'];
                                $ref_field  =   $content['ref_field'] ?? [];
                                $key        =   $content['key'] ?? NULL;
                                $rel        =   $content['rel'] ?? NULL;
                                $rel_key    =   $content['rel_key'] ?? NULL;
                                $rel_val    =   $content['rel_val'] ?? NULL;
                            ?> 
                            @if(isset($key))
                                <td>{{ $data->$field->$key ?? '' }}</td>
                            @else
                                @if(isset($content['type']))
                                    @switch($content['type'])
                                        @case('image')
                                            <img class="card-img-top" src="{{ $data->$field }}" alt="No Image">
                                        @break
                                        
                                        @case('price')
                                            <p class="card-text">
                                                @if (isset($ref_field))
                                                    <p><del>{{ $data->{$ref_field[0]} }} {{ number_format($data->$field,2,",",".").',-' }}</del></p>
                                                    <p>{{ $data->{$ref_field[0]} }} {{ number_format($data->$field - ($data->$field * ($data->{$ref_field[1]} / 100)),2,",",".").',-' }}</p>
                                                @else
                                                    <p>{{ $data->{$ref_field[0]} }} {{ $data->$field }}</p>
                                                @endif
                                            </p>
                                            </div>
                                        @break

                                        @case('title')
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $data->$field }}</h5>
                                        @break

                                    @endswitch
                                @else
                                    <td>{{ $data->$field ?? '' }}</td>
                                @endif
                            @endif
                        @else
                            <td>{{ $data->$content ?? '' }}</td>
                        @endif
                    @endforeach
                @if(isset($options['enable_action']) && $options['enable_action'])
                    <div class="card-footer">
                        @if(isset($options['enable_edit']) && $options['enable_edit'])
                            <a href="{{ route($route_as_name.'.edit', $data->id) }}" class="btn btn-block btn-sm btn-warning">Edit</a>
                        @endif
                        @if(isset($options['enable_delete']) && $options['enable_delete'])
                            <a onclick="return confirm('Are you sure to delete?!');" href="{{ route($route_as_name.'.destroy', $data->id) }}" class="btn btn-block btn-sm btn-danger">Delete</a>
                        @endif
                        @if(isset($options['enable_detail']) && $options['enable_detail'])
                            <button type="button" class="btn btn-block btn-sm btn-primary dynamic-modal-trigger" data-json="{{ json_encode($data) }}" data-toggle="modal" data-target="#dynamicModal">Detail</button>
                        @endif
                        @if(isset($options['enable_buy']) && $options['enable_buy'])
                            <button type="button" data-json="{{ json_encode($data) }}" data-qty="1" class="btn btn-block btn-sm btn-info add-to-cart">Buy</button>
                        @endif
                        @if(isset($options['button_extends']))
                            @foreach($options['button_extends'] as $button_extend)
                                <?php 
                                    // Get the params to edit or using and nested routes
                                    $params             = $button_extend['params'] ?? 'id';
                                    // Show the button when the value in variable $when/$when->$when_key equal to $when_value 
                                    $when               = $button_extend['when'] ?? '';
                                    $when_key           = $button_extend['when_key'] ?? '';
                                    $when_value         = $button_extend['when_value'] ?? '';
                                    // Hide the button when the value in variable $hide_when/$hide_when->$hide_when_key equal to $hide_when_value 
                                    $hide_when          = $button_extend['hide_when'] ?? '';
                                    $hide_when_key      = $button_extend['hide_when_key'] ?? '';
                                    $hide_when_value    = $button_extend['hide_when_value'] ?? '';
                                    // Set the variable if we want to skip to check the `when` datas
                                    $skip_when          = true;
                                    $skip_hide_when     = true;
                                    // Set the variable for showing or not showing the button
                                    $state_show         = false;
                                    $route_button       = null;
                                ?>
                                @if($when != ''  && !$skip_when)
                                    @if($when_key != '')
                                        @if(is_array($when))
                                            <?php $state_true = false; ?>
                                            @foreach($when as $key => $value)
                                                <?php 
                                                    $check_key          = $when_key[$key]; 
                                                    $check_value        = $when_value[$key];
                                                    if ($check_key      == 'count_more') {
                                                        $state_show     = $data->$value->count() > $check_value ? true : false;
                                                    }elseif ($check_key == 'count_less') {
                                                        $state_show     = $data->$value->count() < $check_value ? true : false;
                                                    }elseif ($check_key == 'count_equal') {
                                                        $state_show     = $data->$value->count() == $check_value ? true : false;
                                                    }else {
                                                        $state_show     = $data->$value->$check_key == $check_value ? true : false;
                                                    }
                                                    if ($state_true && $state_show) {
                                                        $state_true = $state_show;
                                                        $state_show = true;
                                                    }else{
                                                        $state_true = $state_show;
                                                        $state_show = false;
                                                    }
                                                ?>
                                            @endforeach
                                        @else
                                            @if(is_array($when_value))
                                            <?php $state_show = in_array($data->$when->$when_key,$when_value) ? true : false; ?>
                                            @else
                                            <?php $state_show = $data->$when->$when_key == $when_value ? true : false ?>
                                            @endif
                                        @endif
                                    @else
                                        @if(is_array($when))
                                            <?php $state_true = false; ?>
                                            @foreach($when as $key => $value)
                                                <?php 
                                                    $check_value    = $when_value[$key];
                                                    $state_show     = $data->$value == $check_value ? true : false;
                                                    if ($state_true && $state_show) {
                                                        $state_true = $state_show;
                                                        $state_show = true;
                                                    }else{
                                                        $state_true = $state_show;
                                                        $state_show = false;
                                                    }
                                                ?>
                                            @endforeach
                                        @else
                                        <?php $state_show = $data->$when == $when_value ? true : false ?>
                                        @endif
                                    @endif
                                @endif
                                @if($hide_when != ''  && !$skip_hide_when)
                                    @if($hide_when_key != '')
                                        @if(is_array($hide_when))
                                            <?php $state_true = false; ?>
                                            @foreach($hide_when as $key => $value)
                                                <?php 
                                                    $check_key          = $hide_when_key[$key]; 
                                                    $check_value        = $hide_when_value[$key];
                                                    if ($check_key      == 'count_more') {
                                                        $state_show     = $data->$value->count() > $check_value ? false : true;
                                                    }elseif ($check_key == 'count_less') {
                                                        $state_show     = $data->$value->count() < $check_value ? false : true;
                                                    }elseif ($check_key == 'count_equal') {
                                                        $state_show     = $data->$value->count() == $check_value ? false : true;
                                                    }else {
                                                        $state_show     = $data->$value->$check_key == $check_value ? false : true;
                                                    }
                                                    if (!$state_true && !$state_show) {
                                                        $state_true = $state_show;
                                                        $state_show = false;
                                                    }else{
                                                        $state_true = $state_show;
                                                        $state_show = true;
                                                    }
                                                ?>
                                            @endforeach
                                        @else
                                            @if(is_array($hide_when_value))
                                            <?php $state_show = in_array($data->$hide_when->$hide_when_key,$hide_when_value) ? false : true; ?>
                                            @else
                                            <?php $state_show = $data->$hide_when->$hide_when_key == $hide_when_value ? false : true; ?>
                                            @endif
                                        @endif
                                    @else
                                        <?php $state_show = $data->$hide_when == $hide_when_value ? false : true ?>
                                    @endif
                                @endif
                                @if($state_show)
                                    @if(is_array($params))
                                        <?php 
                                            $params['model']    = $data->id;
                                            $route_button       = route($button_extend['action'], $params)
                                        ?>
                                    @else
                                        <?php
                                            $route_button       = route($button_extend['action'], $data->$params);
                                        ?>
                                    @endif
                                    <a href="{{ $route_button }}" class="btn btn-block btn-sm btn-{{ $button_extend['class'] ?? 'primary' }}">{{ ucwords($button_extend['label']) }}</a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                @endif
                </div>
            </div>
        @endforeach
        <div class="modal fade" id="dynamicModal" tabindex="-1" role="dialog" aria-labelledby="dynamicModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dynamicModalLabel">Judul Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Isi modal -->
                        <div id="dynamicModalBody"></div>
                        <button type="button" data-json="{{ json_encode($data) }}" data-qty="1" class="btn btn-block btn-sm btn-info add-to-cart">Buy</button>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>