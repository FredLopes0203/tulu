@extends('backend.layouts.app')

@section('after-styles')
    {{ Html::style('css/category.css') }}
    {{ Html::style('js/Crop/croppie.css') }}
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">My Account Settings</h3>
        </div><!-- /.box-header -->


        <div class="panel-body">

            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.profile') }}</a>
                    </li>

                    <li role="presentation">
                        <a href="#edit" aria-controls="edit" role="tab" data-toggle="tab">{{ trans('labels.frontend.user.profile.update_information') }}</a>
                    </li>

                    @if ($logged_in_user->canChangePassword())
                        <li role="presentation">
                            <a href="#password" aria-controls="password" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.change_password') }}</a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane mt-30 active" id="profile">
                        @include('frontend.user.account.tabs.profile')
                    </div><!--tab panel profile-->

                    <div role="tabpanel" class="tab-pane mt-30" id="edit">
                        @include('frontend.user.account.tabs.edit')
                    </div><!--tab panel profile-->

                    @if ($logged_in_user->canChangePassword())
                        <div role="tabpanel" class="tab-pane mt-30" id="password">
                            @include('frontend.user.account.tabs.change-password')
                        </div><!--tab panel change password-->
                    @endif

                </div><!--tab content-->

            </div><!--tab panel-->

        </div><!--panel body-->
    </div><!--box-->

    <div id="myModal" class="profileimgmodal">
        <!-- Modal content -->
        <div class="profileimgmodal-content">
            <div style="max-width: 300px;">
                <div class="main-img-preview">
                    <img id="profilepictureview" class="thumbnail logo-img-preview" src="{{ $logged_in_user->picture }}" title="Preview Profile Image">
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
    {{ Html::script("js/Country/countries.js") }}
    {{ Html::script("js/Crop/croppie.js") }}
    <script>
        $(document).ready(function() {
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
