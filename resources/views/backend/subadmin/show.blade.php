@extends ('backend.layouts.app')

@section ('title', 'Subadmin Management | Subadmin Info')

@section('page-header')
    <h1>
        Subadmin Management
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Subadmin Info</h3>

            <div class="box-tools pull-right">
                @include('backend.subadmin.includes.partials.subadmin-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <table class="table table-striped table-hover">
                <tr>
                    <th>Profile Image</th>
                    <td><img src="{{ $subadmin->picture }}" class="user-profile-image" style="max-width: 150px;" /></td>
                </tr>

                <tr>
                    <th>First Name</th>
                    <td>{{ $subadmin->first_name }}</td>
                </tr>

                <tr>
                    <th>Last Name</th>
                    <td>{{ $subadmin->last_name }}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.email') }}</th>
                    <td>{{ $subadmin->email }}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.status') }}</th>
                    <td>{!! $subadmin->status_label !!}</td>
                </tr>

                <tr>
                    <th>Approve Status</th>
                    <td>{!! $subadmin->approve_label !!}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.created_at') }}</th>
                    <td>{{ $subadmin->created_at }} ({{ $subadmin->created_at->diffForHumans() }})</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.last_updated') }}</th>
                    <td>{{ $subadmin->updated_at }} ({{ $subadmin->updated_at->diffForHumans() }})</td>
                </tr>

                {{--@if ($subadmin->trashed())--}}
                {{--<tr>--}}
                {{--<th>{{ trans('labels.backend.access.users.tabs.content.overview.deleted_at') }}</th>--}}
                {{--<td>{{ $subadmin->deleted_at }} ({{ $user->deleted_at->diffForHumans() }})</td>--}}
                {{--</tr>--}}
                {{--@endif--}}
            </table>

        </div><!-- /.box-body -->
    </div><!--box-->
@endsection