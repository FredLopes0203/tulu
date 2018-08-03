<table class="table table-striped table-hover">

    <tr>
        <th>Profile Image</th>
        <td><img src="{{ $logged_in_user->picture }}" class="user-profile-image" style="max-width: 150px;" /></td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.name') }}</th>
        <td>{{ $logged_in_user->name }}</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.email') }}</th>
        <td>{{ $logged_in_user->email }}</td>
    </tr>

    @if(access()->hasRole(2))
    <tr>
        <th>Organization</th>
        @if($organizationName != "")
            <td>{{$organizationName}}</td>
        @else
            <td style="color: red">No Organization Assigned</td>
        @endif

    </tr>
    @endif

    <tr>
        <th>Admin Type</th>
        @if(access()->hasRole(1))
            <td>Super Admin</td>
        @else
            @if($organizationName != "")
                @if($logged_in_user->isinitial == 0)
                    <td>Sub Admin</td>
                @else
                    <td>Initial Admin</td>
                @endif
            @else
                <td style="color: red">No Admin Permission</td>
            @endif
        @endif

    </tr>

    @if(access()->hasRole(2))
    <tr>
        <th>Approval Status</th>
        @if($logged_in_user->approve == 1)
            <td style="color: darkgreen">Approved</td>
        @else
            <td style="color: red">Pending</td>
        @endif
    </tr>
    @endif
    <tr>
        <th>Active Status</th>
        @if($logged_in_user->status == 1)
            <td style="color: darkgreen">Active</td>
        @else
            <td style="color: red">Inactive</td>
        @endif
    </tr>

    <tr>
        <th>{{ trans('labels.frontend.user.profile.created_at') }}</th>
        <td>{{ $logged_in_user->created_at }} ({{ $logged_in_user->created_at->diffForHumans() }})</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.last_updated') }}</th>
        <td>{{ $logged_in_user->updated_at }} ({{ $logged_in_user->updated_at->diffForHumans() }})</td>
    </tr>
</table>