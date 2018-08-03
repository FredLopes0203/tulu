@extends ('backend.layouts.app')

@section ('title', 'User Management | User Info')

@section('page-header')
    <h1>
        User Management
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">User Info</h3>

            <div class="box-tools pull-right">
                @include('backend.myuser.includes.partials.user-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <table class="table table-striped table-hover">
                <tr>
                    <th>Profile Image</th>
                    <td><img src="{{ $myuser->picture }}" class="user-profile-image" style="max-width: 150px;" /></td>
                </tr>

                <tr>
                    <th>First Name</th>
                    <td>{{ $myuser->first_name }}</td>
                </tr>

                <tr>
                    <th>Last Name</th>
                    <td>{{ $myuser->last_name }}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.email') }}</th>
                    <td>{{ $myuser->email }}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.status') }}</th>
                    <td>{!! $myuser->status_label !!}</td>
                </tr>

                <tr>
                    <th>Approve Status</th>
                    <td>{!! $myuser->approve_label !!}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.created_at') }}</th>
                    <td>{{ $myuser->created_at }} ({{ $myuser->created_at->diffForHumans() }})</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.last_updated') }}</th>
                    <td>{{ $myuser->updated_at }} ({{ $myuser->updated_at->diffForHumans() }})</td>
                </tr>
            </table>

        </div><!-- /.box-body -->
    </div><!--box-->
@endsection