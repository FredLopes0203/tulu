@extends ('backend.layouts.app')

@section ('title', $type == 1? 'TULU | Edit Admin' : 'TULU | Edit User')

@section('after-styles')
    {{ Html::style('js/select2/select2.min.css') }}
    {{ Html::style("js/iCheck/blue.css") }}
    {{ Html::style('css/category.css') }}
    {{--{{ Html::style('https://unpkg.com/cropperjs/dist/cropper.css') }}--}}
    {{--{{ Html::style('js/JCropper/jquery.Jcrop.css') }}--}}
    {{ Html::style('js/Crop/croppie.css') }}
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
    {{ Form::model($user, ['id' => 'updateForm', 'route' => ['admin.access.user.update', $user], 'class' => 'form-horizontal', 'role' => 'form',  'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}

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
                    <div class="col-lg-10">

                            <div class="main-img-preview">
                                <img id="profileimgview" class="thumbnail logo-img-preview" src="{{ $user->picture }}" title="Preview Profile Image">
                                {{--<img id="profilepictureview" class="thumbnail logo-img-preview" src="{{ $user->picture }}" title="Preview Profile Image">--}}
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

                        <button class="btn btn-primary" id="editBtn">Edit Profile Image</button>
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

        <div id="myModal" class="profileimgmodal">
            <!-- Modal content -->
            <div class="profileimgmodal-content">
                <div style="max-width: 300px;">
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
                    <button class="btn btn-success" style="width: 145px;" id="editSave">Save</button>
                    <button class="btn btn-danger" id="editCancel" style="width: 145px; float:right;">Cancel</button>
                </div>
            </div>
        </div>
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
    {{ Html::script("js/select2/select2.full.min.js") }}
    {{ Html::script("js/login.js") }}
    {{--{{ Html::script("https://unpkg.com/cropperjs/dist/cropper.js") }}--}}
    {{--{{ Html::script("js/imagecropper/cropper.js") }}--}}
    {{--{{ Html::script("js/imagecropper/jquery-cropper.js") }}--}}
    {{--{{ Html::script("js/JCropper/jquery.JCrop.min.js") }}--}}
    {{ Html::script("js/Crop/croppie.js") }}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $(".select2").select2();
            $(".select2-selection").css("height", "35px");
            $(".select2-selection__rendered").css("padding-top", "2px");
            $(".select2-selection__arrow").css("top", "4px");

            var saveAvailable = 0;

            var logo = document.getElementById('logo-id');
            logo.className = 'attachment_upload';
            logo.onchange = function() {
                document.getElementById('LogoImg').value = this.value.substring(12);
            };


            var modal = document.getElementById('myModal');
            var editBtn = document.getElementById("editBtn");
            var saveBtn = document.getElementById("editSave");
            var cancelBtn = document.getElementById("editCancel");

            var $imgView = $('#profilepictureview')
            var $imageView = $('#profileimgview')
            var imageData = "";

            $profileCrop = $imgView.croppie({
                enableExif: true,
                enforceBoundaryboolean: false,
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'square'
                },
                boundary: {
                    width: 300,
                    height: 300
                },
            });

            editBtn.onclick = function() {
                event.preventDefault();

                modal.style.display = "block";

                $profileCrop.croppie('bind');
                $profileCrop.croppie('setZoom', 10.0);
            }

            saveBtn.onclick = function() {
                event.preventDefault();

                $profileCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {
                    saveAvailable = 1;
                    $imageView.attr('src', resp);
                    imageData = resp;

                    modal.style.display = "none";
                });
            }

            cancelBtn.onclick = function() {
                event.preventDefault();
                modal.style.display = "none";
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var selectedImage = new Image();
                        selectedImage.src =  e.target.result;
                        console.log(e.target.result);
                        selectedImage.onload = function ()
                        {
                            $('#profilepictureview').attr('src', e.target.result);

                            $profileCrop.croppie('bind', {
                                url: e.target.result
                            }).then(function(){
                                console.log('jQuery bind complete');
                            });
                        };
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#logo-id").change(function() {
                if(this.value == "")
                {
//                    $('.logo-img-preview').attr('src', window.location.origin + '/img/profile/sample.png');
//                    $('#LogoImg').text("");
//                    $('#LogoImg').val("");
//                    $('#logo-id').val("");
//                    console.log(window.location.origin + '/img/profile/sample.png');
                }
                else
                {
                    readURL(this);
                }
            });

            $("#updateForm").submit(function(evt){
                var imgInput = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "imgData").val(imageData);
                $("#updateForm").append(imgInput);

                return true;
            });
        });
    </script>

@endsection
