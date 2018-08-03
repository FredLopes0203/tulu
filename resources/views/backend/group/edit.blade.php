@extends ('backend.layouts.app')

@section ('title', 'Organization Management' . ' | ' . 'Edit Organization')

@section('after-styles')
    {{ Html::style('css/category.css') }}
    {{ Html::style('js/select2/select2.min.css') }}
    {{ Html::style('js/Crop/croppie.css') }}
@endsection

@section('page-header')
    <h1>
        Organization Management
        <small>Edit Organization</small>
    </h1>
@endsection

@section('content')
    @if (access()->hasRole(2))
        {{ Form::model($group, ['id' => 'groupupdateform', 'route' => ['admin.group.update', $group], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}
    @else
        {{ Form::model($group, ['id' => 'groupupdateform', 'route' => ['admin.organization.update', $group], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}
    @endif

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Organization Info</h3>
            </div><!-- /.box-header -->

            <div class="box-body">

                <div class="form-group">
                    {{ Form::label('name', 'Organiation Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'readonly'=>true, 'autofocus' => 'autofocus', 'placeholder' => 'Organization Name']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('addressline1', 'Address Line 1', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('address1', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => '41740 Tall Cedars Parkway']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('addressline2', 'Address Line 2', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('address2', null, ['class' => 'form-control', 'maxlength' => '191', 'autofocus' => 'autofocus', 'placeholder' => '']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('lblzip', 'Zip', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('zip', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => '20105']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('lblcountry', 'Country / region', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        <select style="width: 100%" required="required" class="form-control select2" onchange="print_state('state',this.selectedIndex);" id="country" name ="country">

                        </select>
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('lblcity', 'City', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-5">
                        {{ Form::text('city', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Aldie']) }}
                    </div><!--col-lg-10-->

                    {{ Form::label('lblstate', 'State', ['class' => 'col-lg-1 control-label']) }}

                    <div class="col-lg-4">
                        <select class="form-control select2" required="required" name ="state" id ="state">

                        </select>
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('photourl', 'Logo Image', ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        <div style="max-width: 300px;">
                            <div class="main-img-preview">
                                <img id="logoImgView" class="thumbnail logo-img-preview" src="{{asset($group->logo)}}" title="Preview Logo">
                            </div>
                        </div>

                        <button class="btn btn-primary" id="editBtn">Edit Logo Image</button>

                        {{--<div class="input-group img-preview">--}}
                            {{--<input id="LogoImg" class="form-control fake-shadow" placeholder="Choose File" disabled="disabled">--}}
                            {{--<div class="input-group-btn">--}}
                                {{--<div class="fileUpload btn btn-danger fake-shadow">--}}
                                    {{--<span><i class="glyphicon glyphicon-upload"></i> Upload Logo Image</span>--}}
                                    {{--<input id="logo-id" name="logo" type="file" class="attachment_upload">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="help-block">* Upload square image for your organization logo.</p>--}}
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
                                @if($group->status == 1)
                                    @if($group->approved == 0)
                                        {{ link_to_route('admin.organization.pending', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                    @else
                                        {{ link_to_route('admin.organization.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                    @endif
                                @else
                                    {{ link_to_route('admin.organization.deactivated', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                                @endif
                            @else
                                {{ link_to_route('admin.group.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                            @endif
                        </div><!--pull-left-->
                    </div><!--col-lg-10-->
                </div><!--form control-->
            </div><!-- /.box-body -->
        </div><!--box-->

            <div class="box-body">

                <div class="clearfix"></div>
            </div><!-- /.box-body -->

    {{ Form::close() }}

    <div id="myModal" class="profileimgmodal">
        <!-- Modal content -->
        <div class="profileimgmodal-content">
            <div style="max-width: 300px;">
                <div class="main-img-preview">
                    <img id="logopictureview" class="thumbnail logo-img-preview" src="{{asset($group->logo)}}" title="Preview Logo Image">
                </div>
                <div class="input-group img-preview">
                    <input id="LogoImg" class="form-control fake-shadow" placeholder="Choose File" disabled="disabled">
                    <div class="input-group-btn">
                        <div class="fileUpload btn btn-danger fake-shadow">
                            <span><i class="glyphicon glyphicon-upload"></i> Upload Logo Image</span>
                            <input id="logo-id" name="logo" type="file" class="attachment_upload">
                        </div>
                    </div>
                </div>
                <p class="help-block">* Upload square image for organization logo image.</p>
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
            $(".select2").select2();
            $(".select2-selection").css("height", "35px");
            $(".select2-selection__rendered").css("padding-top", "2px");
            $(".select2-selection__arrow").css("top", "4px");

            print_country("country")

            var countryVal = "<?php echo $group->country ?>";
            $("#country").val(countryVal);
            $("#country").change();
            var stateVal = "<?php echo $group->state ?>";
            $("#state").val(stateVal);

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

            $("#groupupdateform").submit(function(evt){
                var imgInput = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "imgData").val(imageData);
                $("#groupupdateform").append(imgInput);

                return true;
            });
        });
    </script>
@endsection
