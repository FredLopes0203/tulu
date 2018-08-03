@extends ('backend.layouts.app')

@section ('title', $type == 1? 'TULU | View Admin' : 'TULU | View User')

@section('page-header')
    <h1>
        @if($type == 1)
            Admin Management
        @else
            User Management
        @endif
        <small>
            @if($type == 1)
                View Admin
            @else
                View User
            @endif
        </small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                @if($type == 1)
                    View Admin
                @else
                    View User
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
            <table class="table table-striped table-hover">
                <tr>
                    <th>Profile Image</th>
                    <td><img src="{{ $user->picture }}" class="user-profile-image" style="max-width: 150px;"/></td>
                </tr>

                <tr>
                    <th>First Name</th>
                    <td>{{ $user->first_name }}</td>
                </tr>

                <tr>
                    <th>Last Name</th>
                    <td>{{ $user->last_name }}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.email') }}</th>
                    <td>{{ $user->email }}</td>
                </tr>

                <tr>
                    <th>Account Type</th>
                    <td>
                        @if($user->isadmin == 0)
                            User
                        @else
                            @if($user->isinitial == 1)
                                Initial Admin
                            @else
                                Sub Admin
                            @endif
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Organization</th>
                    <td>{!! $user->organization_label !!}</td>
                </tr>


                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.status') }}</th>
                    <td>{!! $user->status_label !!}</td>
                </tr>

                <tr>
                    <th>Email Confirmed</th>
                    <td>{!! $user->confirmed_label !!}</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.created_at') }}</th>
                    <td>{{ $user->created_at }} ({{ $user->created_at->diffForHumans() }})</td>
                </tr>

                <tr>
                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.last_updated') }}</th>
                    <td>{{ $user->updated_at }} ({{ $user->updated_at->diffForHumans() }})</td>
                </tr>

                @if ($user->trashed())
                    <tr>
                        <th>{{ trans('labels.backend.access.users.tabs.content.overview.deleted_at') }}</th>
                        <td>{{ $user->deleted_at }} ({{ $user->deleted_at->diffForHumans() }})</td>
                    </tr>
                @endif
            </table>
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection