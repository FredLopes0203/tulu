{{ Form::model($logged_in_user, ['id' => 'updateForm', 'route' => 'frontend.user.profile.update', 'class' => 'form-horizontal', 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}

    <div class="form-group">
        {{ Form::label('first_name', trans('validation.attributes.frontend.first_name'),
        ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::text('first_name', null,
            ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.first_name')]) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('last_name', trans('validation.attributes.frontend.last_name'),
        ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.last_name')]) }}
        </div>
    </div>

    @if ($logged_in_user->canChangeEmail())
        <div class="form-group">
            {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) }}
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> {{  trans('strings.frontend.user.change_email_notice') }}
                </div>

                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
            </div>
        </div>
    @endif

    <div class="form-group">
        {{ Form::label('photourl', 'Profile Image', ['class' => 'col-lg-4 control-label']) }}
        <div class="col-lg-8">
            {{--<div style="max-width: 300px;">--}}
                <div class="main-img-preview">
                    <img id="profileimgview" class="thumbnail logo-img-preview" src="{{ $logged_in_user->picture }}" title="Preview Profile Image">
                </div>
                {{--<div class="input-group img-preview">--}}
                    {{--<input id="LogoImg" class="form-control fake-shadow" placeholder="Choose File" disabled="disabled">--}}
                    {{--<div class="input-group-btn">--}}
                        {{--<div class="fileUpload btn btn-danger fake-shadow">--}}
                            {{--<span><i class="glyphicon glyphicon-upload"></i> Upload Profile Image</span>--}}
                            {{--<input id="logo-id" name="logo" type="file" class="attachment_upload">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<p class="help-block">* Upload square image for your profile image.</p>--}}
            {{--</div>--}}

            <button class="btn btn-primary" id="editBtn">Edit Profile Image</button>
        </div>


    </div>
<br>
<br>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <div style="margin-left: auto; margin-right: auto; width: 100px">
            {{ Form::submit('Save Profile', ['class' => 'btn btn-success', 'id' => 'update-profile']) }}
            </div>
        </div>
    </div>

{{ Form::close() }}