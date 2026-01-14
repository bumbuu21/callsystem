@extends('layouts.app')

@section('content')
<h2>Call List</h2>

<table border="1" width="100%">
    <tr>
        <th>Date</th>
        <th>Citizen</th>
        <th>Type</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <tr>
        <td>2026-01-13</td>
        <td>Bat</td>
        <td>Complaint</td>
        <td>New</td>
        <td><a href="#">View</a></td>
    </tr>
</table>
@endsection
