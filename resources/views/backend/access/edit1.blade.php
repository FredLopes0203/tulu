@extends ('backend.layouts.app')

@section ('title', $type == 1? 'TULU | Edit Admin' : 'TULU | Edit User')

@section('after-styles')
    {{ Html::style('js/select2/select2.min.css') }}
    {{ Html::style("js/iCheck/blue.css") }}
    {{ Html::style('css/category.css') }}
    {{--{{ Html::style('https://unpkg.com/cropperjs/dist/cropper.css') }}--}}
    {{ Html::style('js/JCropper/jquery.Jcrop.css') }}
@endsection

@section('page-header')
    <h1>
        @if($type == 1)
            Admin Management
        @else
            User Management
        @endif
        <small>
            @if($type == 1)
                Edit Admin
            @else
                Edit User
            @endif
        </small>
    </h1>
@endsection

@section('content')
    {{ Form::model($user, ['route' => ['admin.access.user.update', $user], 'class' => 'form-horizontal', 'role' => 'form',  'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">
                    @if($type == 1)
                        Edit Admin
                    @else
                        Edit User
                    @endif
                </h3>

                <div class="box-tools pull-right">
                    @if($type == 1)
                        @include('backend.access.includes.partials.admin-header-buttons')
                    @else
                        @include('backend.access.includes.partials.user-header-buttons')
                    @endif
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('lblemail', trans('validation.attributes.backend.access.users.email'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::email('email', $user->email, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'readonly'=>true, 'placeholder' => trans('validation.attributes.backend.access.users.email')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('lblfirstname', 'First Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('first_name', $user->first_name, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'First Name']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('lbllastname', 'Last Name',
                     ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('last_name', $user->last_name, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => 'Last Name']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->


                <div class="form-group">
                    {{ Form::label('lblphone', 'Phone Number', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::number('phonenumber',  $user->phonenumber, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Phone Number']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('lblorganization', 'Organization', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        <select style="width: 100%" required="required" class="form-control select2" id="organization" name ="organization">
                            <option value="0" selected="selected" disabled="disabled">--- Choose Organization ---</option>
                            @foreach($organizations as $organization)
                                <option value="{{$organization->id}}"
                                        @if($user->organization > 0)
                                            @if($user->organization == $organization->id)
                                                selected = "selected"
                                            @endif
                                        @endif
                                >
                                    {{$organization->name}}
                                </option>
                            @endforeach
                        </select>
                    </div><!--col-lg-10-->
                </div><!--form control-->

                @if($user->isadmin == 1)

                    <div class="form-group">
                        {{ Form::label('lblinital', 'Initial Admin', ['class' => 'col-lg-2 control-label']) }}

                        <div class="col-lg-1" style="margin-top: 7px">
                                {{ Form::checkbox('initialadmin', '1', $user->isinitial == 1) }}


                        </div><!--col-lg-1-->
                    </div><!--form control-->
                @endif



                <div class="form-group">
                    {{ Form::label('photourl', 'Profile Image', ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-5">
                        <div class="main-img-preview">
                            <img id="profilepictureview" class="thumbnail logo-img-preview" src="{{ $user->picture }}" title="Preview Profile Image">
                        </div>
                        <div class="input-group img-preview">
                            <input id="LogoImg" class="form-control fake-shadow" placeholder="Choose File" disabled="disabled">
                            <div class="input-group-btn">
                                <div class="fileUpload btn btn-danger fake-shadow">
                                    <span><i class="glyphicon glyphicon-upload"></i> Upload Profile Image</span>
                                    <input id="logo-id" name="logo" type="file" class="attachment_upload">
                                </div>
                            </div>
                        </div>
                        <p class="help-block">* Upload square image for your profile image.</p>
                    </div>

                    <div class="col-lg-5">
                        <div id="preview-pane">
                            <div class="preview-container">
                                <img src="{{ $user->picture }}" class="jcrop-preview" alt="Preview" />
                            </div>
                        </div>
                    </div>
                </div>




                <div class="form-group" style="margin-top:50px;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="pull-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md', 'style' => 'width:100px !important;']) }}
                        </div><!--pull-right-->
                    </div><!--col-lg-10-->
                    {{--<div class="col-lg-2">--}}
                    {{--</div>--}}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="pull-left">
                            @if(access()->hasRole(1))
                                @if($user->status == 1)
                                    @if($user->approve == 0)
                                        @if($user->isadmin == 1)
                                            {{ link_to_route('admin.access.manager.pending', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                        @else
                                            {{ link_to_route('admin.access.user.pending', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                        @endif
                                    @else
                                        @if($user->isadmin == 1)
                                            {{ link_to_route('admin.access.manager.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                        @else
                                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                        @endif
                                    @endif
                                @else
                                    @if($user->isadmin == 1)
                                        {{ link_to_route('admin.access.manager.deactivated', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                    @else
                                        {{ link_to_route('admin.access.user.deactivated', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                    @endif
                                @endif
                            @endif
                        </div><!--pull-left-->
                    </div><!--col-lg-10-->
                </div><!--form control-->



            </div><!-- /.box-body -->
        </div><!--box-->
    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
    {{ Html::script("js/select2/select2.full.min.js") }}
    {{ Html::script("js/login.js") }}
    {{--{{ Html::script("https://unpkg.com/cropperjs/dist/cropper.js") }}--}}
    {{--{{ Html::script("js/imagecropper/cropper.js") }}--}}
    {{--{{ Html::script("js/imagecropper/jquery-cropper.js") }}--}}
    {{ Html::script("js/JCropper/jquery.JCrop.min.js") }}
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $(".select2-selection").css("height", "35px");
            $(".select2-selection__rendered").css("padding-top", "2px");
            $(".select2-selection__arrow").css("top", "4px");

            var logo = document.getElementById('logo-id');
            logo.className = 'attachment_upload';
            logo.onchange = function() {
                document.getElementById('LogoImg').value = this.value.substring(12);
            };

            var $image = $('#profilepictureview');
//            $image.cropper({
//                aspectRatio: 1 / 1,
//                crop: function(event) {
//                    console.log(event.detail.x);
//                    console.log(event.detail.y);
//                    console.log(event.detail.width);
//                    console.log(event.detail.height);
//                    console.log(event.detail.rotate);
//                    console.log(event.detail.scaleX);
//                    console.log(event.detail.scaleY);
//                }
//            });

// Get the Cropper.js instance after initialized
            var jcrop_api,
                boundx,
                boundy,

                // Grab some information about the preview pane
                $preview = $('#preview-pane'),
                $pcnt = $('#preview-pane .preview-container'),
                $pimg = $('#preview-pane .preview-container img'),

                xsize = $pcnt.width(),
                ysize = $pcnt.height();

            $('#profilepictureview').Jcrop({
                onChange: updatePreview,
                onSelect: updatePreview,
                aspectRatio: 1 / 1
            },function(){
                // Use the API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                // Store the API in the jcrop_api variable
                jcrop_api = this;

                // Move the preview into the jcrop container for css positioning
                $preview.appendTo(jcrop_api.ui.holder);
            });

            function updatePreview(c)
            {
                if (parseInt(c.w) > 0)
                {
                    var rx = xsize / c.w;
                    var ry = ysize / c.h;

                    $pimg.css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                }
            };



            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var selectedImage = new Image();
                        selectedImage.src =  e.target.result;

                        selectedImage.onload = function () {
                            /*if(this.width == this.height)
                            {*/
                                $('.logo-img-preview').attr('src', e.target.result);

                            $('#profilepictureview').Jcrop({
                                onChange: updatePreview,
                                onSelect: updatePreview,
                                aspectRatio: 1 / 1
                            },function(){
                                // Use the API to get the real image size
                                var bounds = this.getBounds();
                                boundx = bounds[0];
                                boundy = bounds[1];
                                // Store the API in the jcrop_api variable
                                jcrop_api = this;

                                // Move the preview into the jcrop container for css positioning
                                $preview.appendTo(jcrop_api.ui.holder);
                            });
//                            $image.cropper({
//                                aspectRatio: 1 / 1,
//                                crop: function(event) {
//                                    console.log(event.detail.x);
//                                    console.log(event.detail.y);
//                                    console.log(event.detail.width);
//                                    console.log(event.detail.height);
//                                    console.log(event.detail.rotate);
//                                    console.log(event.detail.scaleX);
//                                    console.log(event.detail.scaleY);
//                                }
//                            });
                            /*}
                            else
                            {
                                swal({
                                    title: "Not a square image!",
                                    text: "Please upload square image for your oraganization logo.",
                                    type: "error",
                                    showCancelButton: false,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: true,
                                    logStr: ""
                                }, function (confirmed) {

                                });

                                $('.logo-img-preview').attr('src', window.location.origin + '/img/profile/sample.png');
                                $('#LogoImg').text("");
                                $('#LogoImg').val("");
                                $('#logo-id').val("");
                            }*/
                        };
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#logo-id").change(function() {
                if(this.value == "")
                {
                    $('.logo-img-preview').attr('src', window.location.origin + '/img/profile/sample.png');
                    $('#LogoImg').text("");
                    $('#LogoImg').val("");
                    $('#logo-id').val("");
                    console.log(window.location.origin + '/img/profile/sample.png');
                }
                else
                {
                    readURL(this);
                }
            });


        });
    </script>

@endsection
