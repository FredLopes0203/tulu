@extends ('backend.layouts.app')

@section ('title', 'TULU | Alert Management')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        User Location
    </h1>
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">User Location</h3>
            {{--<small>ASDF  {{$lat}}   {{$long}}</small>--}}

            <div class="box-tools pull-right">
                @include('backend.alert.tabs.location-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->


        <div class="box-body">
            <div id="mymapview" style="width: 100%; height: 460px; background-color: darkgrey"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection

<script>
    var myMap = function() {
        var latstr = "<?php echo $lat ?>";
        var lngstr = "<?php echo $long ?>";
        var lat = parseFloat(latstr);
        var lng = parseFloat(lngstr);

        var centerPos = new google.maps.LatLng(lat,lng);
        var mapProp= {
            center:centerPos,
            zoom:19,
        };

        var map=new google.maps.Map(document.getElementById("mymapview"),mapProp);
        var marker = new google.maps.Marker({position: centerPos});

        marker.setMap(map);
    }
</script>

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("https://maps.googleapis.com/maps/api/js?key=AIzaSyBsC_g9qzNWG2ER67DXe0NcsQf8NPUIbtg&callback=myMap") }}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
        });
    </script>
@endsection