@extends ('backend.layouts.app')

@if($type == 0)
    @section ('title', 'User Management' . ' | ' . 'Create User')
@else
    @section ('title', 'Admin Management' . ' | ' . 'Create Admin')
@endif


@section('after-styles')
    {{ Html::style('css/category.css') }}
    {{ Html::style('js/select2/select2.min.css') }}
    {{ Html::style('js/Crop/croppie.css') }}
@endsection

@section('page-header')
    @if($type == 0)
        <h1>
            User Management
            <small>Create User</small>
        </h1>
    @else
        <h1>
            Admin Management
            <small>Create Admin</small>
        </h1>
    @endif
@endsection

@section('content')

    @if($type == 0)
        {{ Form::open(['route' => 'admin.access.user.store', 'id' => 'usercreateform', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    @else
        {{ Form::open(['route' => 'admin.access.manager.store', 'id' => 'usercreateform', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    @endif

    <div class="box box-success">
        <div class="box-header with-border">
            @if($type == 0)
                <h3 class="box-title">User Info</h3>
            @else
                <h3 class="box-title">Admin Info</h3>
            @endif
        </div><!-- /.box-header -->

        <div class="box-body">

            <div class="form-group">
                {{ Form::label('organization', 'Organization', ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <select style="width: 100%" required="required" class="form-control select2" id="organization" name ="organization">
                        <option value="0" selected="selected" value="0" disabled="disabled">--- Choose Organization ---</option>
                        @foreach($organizations as $organization)
                            <option value="{{$organization->id}}">{{$organization->name}}</option>
                        @endforeach
                    </select>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('firstname', 'First Name', ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    @if($type == 0)
                        {{ Form::text('firstname', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'User First Name']) }}
                    @else
                        {{ Form::text('firstname', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Admin First Name']) }}
                    @endif

                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('lastname', 'Last Name', ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    @if($type == 0)
                        {{ Form::text('lastname', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'User Last Name']) }}
                    @else
                        {{ Form::text('lastname', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Admin Last Name']) }}
                    @endif
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('email', 'Email', ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">

                    @if($type == 0)
                        {{ Form::text('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'User Email']) }}
                    @else
                        {{ Form::text('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Admin Email']) }}
                    @endif
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('phonenumber', 'Phone Number', ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('phonenumber', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Phone Number']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('photourl', 'Profile Image', ['class' => 'col-lg-2 control-label']) }}
                <div class="col-lg-10">
                    <div style="max-width: 300px;">
                        <div class="main-img-preview">
                            <img id="logoImgView" class="thumbnail logo-img-preview" src="{{asset('img/samplelogo.png')}}" title="Preview Logo">
                        </div>
                    </div>

                    <button class="btn btn-primary" id="editBtn">Edit Profile Image</button>
                </div>
            </div>

            <div class="form-group" style="margin-top:50px;">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="pull-right">
                        {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md', 'style' => 'width:100px !important;']) }}
                    </div><!--pull-right-->
                </div><!--col-lg-10-->
                {{--<div class="col-lg-2">--}}
                {{--</div>--}}
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="pull-left">
                        @if($type == 0)
                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                        @else
                            {{ link_to_route('admin.access.manager.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                        @endif

                    </div><!--pull-left-->
                </div><!--col-lg-10-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    {{--<div class="box box-info">--}}
    <div class="box-body">

        <div class="clearfix"></div>
    </div><!-- /.box-body -->
    {{--</div><!--box-->--}}

    {{ Form::close() }}

    <div id="myModal" class="profileimgmodal">
        <!-- Modal content -->
        <div class="profileimgmodal-content">
            <div style="max-width: 300px;">
                <div class="main-img-preview">
                    <img id="logopictureview" class="thumbnail logo-img-preview" src="{{asset('img/samplelogo.png')}}" title="Preview Profile Image">
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
                <p class="help-block">* Upload square image for profile image.</p>
                <button class="btn btn-success" style="width: 145px;" id="editSave">Save</button>
                <button class="btn btn-danger" id="editCancel" style="width: 145px; float:right;">Cancel</button>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
    {{ Html::script("js/select2/select2.full.min.js") }}
    {{ Html::script("js/Crop/croppie.js") }}
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
            var saveAvailable = 0;


            var modal = document.getElementById('myModal');
            var editBtn = document.getElementById("editBtn");
            var saveBtn = document.getElementById("editSave");
            var cancelBtn = document.getElementById("editCancel");

            var $imgView = $('#logopictureview');
            var $imageView = $('#logoImgView');
            var imageData = "";

            $logoCrop = $imgView.croppie({
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

                $logoCrop.croppie('bind');
                $logoCrop.croppie('setZoom', 10.0);
            }

            saveBtn.onclick = function() {
                event.preventDefault();

                $logoCrop.croppie('result', {
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
                            $('#logopictureview').attr('src', e.target.result);

                            $logoCrop.croppie('bind', {
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

                }
                else
                {
                    readURL(this);
                }
            });


            $("#usercreateform").submit(function(evt){
                var orgVal = $("#organization").val();

                if(orgVal == null || orgVal == 0)
                {
                    swal({
                        title: "Choose Organization.",
                        type: "error",
                        confirmButtonClass: 'btn btn-danger',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                if(imageData == "")
                {
                    swal({
                        title: "Choose image for the profile.",
                        type: "error",
                        confirmButtonClass: 'btn btn-danger',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }



                var imgInput = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "imgData").val(imageData);
                $("#usercreateform").append(imgInput);

                return true;
            });
        });
    </script>
@endsection
