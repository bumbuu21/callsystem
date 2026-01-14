@extends('layouts.app')

@section('content')
<h2>Users</h2>

<table>
    <tr>
        <th>Name</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <tr>
        <td>Admin</td>
        <td>Administrator</td>
        <td>Edit | Delete</td>
    </tr>
</table>
@endsection
