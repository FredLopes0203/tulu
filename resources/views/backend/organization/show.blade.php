@extends ('backend.layouts.app')

@section ('title', 'Organization Management | Organization Info')

@section('page-header')
    <h1>
        Organization Management
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Organization Info</h3>

            <div class="box-tools pull-right">
                @include('backend.organization.includes.partials.back-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

            <div class="box-body">

                <table class="table table-striped table-hover">
                    <tr>
                        <th>Logo</th>
                        <td><img src="{{asset($organization->logo)}}" class="user-profile-image" style="max-width: 150px;" /></td>
                    </tr>

                    <tr>
                        <th>Organization Name</th>
                        <td>{{ $organization->name }}</td>
                    </tr>

                    <tr>
                        <th>Org ID#</th>
                        <td>
                            @if($organization->groupid == "" || $organization->groupid == null)
                                Not assignged
                            @else
                                {{ $organization->groupid }}
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th>Organization Address</th>
                        <td>{{ $organization->full_address }}</td>
                    </tr>

                    <tr>
                        <th>{{ trans('labels.backend.access.users.tabs.content.overview.status') }}</th>
                        <td>{!! $organization->status_label !!}</td>
                    </tr>

                    <tr>
                        <th>Approve Status</th>
                        <td>{!! $organization->approve_label !!}</td>
                    </tr>

                    <tr>
                        <th>{{ trans('labels.backend.access.users.tabs.content.overview.created_at') }}</th>
                        <td>{{ $organization->created_at }} ({{ $organization->created_at->diffForHumans() }})</td>
                    </tr>

                    <tr>
                        <th>{{ trans('labels.backend.access.users.tabs.content.overview.last_updated') }}</th>
                        <td>{{ $organization->updated_at }} ({{ $organization->updated_at->diffForHumans() }})</td>
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