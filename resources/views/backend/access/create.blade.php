@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.access.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.access.users.create') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.user-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('first_name', 'Real Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('real_name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Real Name']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('last_name', 'SuperHero Name',
                     ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('hero_name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => 'SuperHero Name']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('email', trans('validation.attributes.backend.access.users.email'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.email')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('password', trans('validation.attributes.backend.access.users.password'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.password')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('password_confirmation', trans('validation.attributes.backend.access.users.password_confirmation'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.password_confirmation')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('age', 'Age', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::number('age', '', ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Age']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('age', 'Credit Card', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::number('cardnum', '', ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Credit Card Number']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('sex', 'Sex', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10" style="margin-top: 7px">
                        <input class="sexradio" type="radio" value="1" name="sex" id="sex-1" required /> <label for="sex-1">Male</label>
                        <input class="sexradio" style="margin-left: 20px" type="radio" value="2" name="sex" id="sex-2" /> <label for="sex-2">Female</label>
                    </div>
                </div>


                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.active'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-1" style="margin-top: 7px">
                        {{ Form::checkbox('status', '1', true) }}
                    </div><!--col-lg-1-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-1" style="margin-top: 7px">
                        {{ Form::checkbox('confirmed', '1', true) }}
                    </div><!--col-lg-1-->
                </div><!--form control-->

                @if (! config('access.users.requires_approval'))
                    <div class="form-group">
                        <label class="col-lg-2 control-label">{{ trans('validation.attributes.backend.access.users.send_confirmation_email') }}<br/>
                            <small>{{ trans('strings.backend.access.users.if_confirmed_off') }}</small>
                        </label>

                        <div class="col-lg-1" style="margin-top: 7px">
                            {{ Form::checkbox('confirmation_email', '1') }}
                        </div><!--col-lg-1-->
                    </div><!--form control-->
                @endif

                {{--<div class="form-group">--}}
                    {{--{{ Form::label('associated_roles', trans('validation.attributes.backend.access.users.associated_roles'), ['class' => 'col-lg-2 control-label']) }}--}}

                    {{--<div class="col-lg-3" style="margin-top: 7px">--}}
                        {{--@if (count($roles) > 0)--}}
                            {{--@foreach($roles as $role)--}}
                                {{--<input class="roleradio" type="radio" value="{{ $role->id }}" name="assignees_roles" id="role-{{ $role->id }}" {{ is_array(old('assignees_roles')) && in_array($role->id, old('assignees_roles')) ? 'selected' : '' }} /> <label for="role-{{ $role->id }}">{{ $role->name }}</label>--}}
                                {{--<a href="#" data-role="role_{{ $role->id }}" class="show-permissions small">--}}
                                    {{--(--}}
                                        {{--<span class="show-text">{{ trans('labels.general.show') }}</span>--}}
                                        {{--<span class="hide-text hidden">{{ trans('labels.general.hide') }}</span>--}}
                                        {{--{{ trans('labels.backend.access.users.permissions') }}--}}
                                    {{--)--}}
                                {{--</a>--}}
                                {{--<br/>--}}
                                {{--<div class="permission-list hidden" data-role="role_{{ $role->id }}">--}}
                                    {{--@if ($role->all)--}}
                                        {{--{{ trans('labels.backend.access.users.all_permissions') }}<br/><br/>--}}
                                    {{--@else--}}
                                        {{--@if (count($role->permissions) > 0)--}}
                                            {{--<blockquote class="small">--}}{{----}}
                                        {{----}}{{--@foreach ($role->permissions as $perm)--}}{{----}}
                                            {{----}}{{--{{$perm->display_name}}<br/>--}}
                                                {{--@endforeach--}}
                                            {{--</blockquote>--}}
                                        {{--@else--}}
                                            {{--{{ trans('labels.backend.access.users.no_permissions') }}<br/><br/>--}}
                                        {{--@endif--}}
                                    {{--@endif--}}
                                {{--</div><!--permission list-->--}}
                            {{--@endforeach--}}
                        {{--@else--}}
                            {{--{{ trans('labels.backend.access.users.no_roles') }}--}}
                        {{--@endif--}}
                    {{--</div><!--col-lg-3-->--}}
                {{--</div><!--form control-->--}}
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}

    <script>
        $(document).ready(function() {
            /*$('.sexradio').change(function(){
                var selectedSex = $('.sexradio:checked').val();
                alert(selectedSex);
            });

            $('.roleradio').change(function(){
                var selectedRole = $('.roleradio:checked').val();
                alert(selectedRole);
            });*/
        });
    </script>
@endsection
