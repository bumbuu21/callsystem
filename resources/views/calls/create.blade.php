@extends('layouts.app')

@section('content')
<h2>Register Call</h2>

<form>
    <label>Date</label>
    <input type="datetime-local">

    <label>Citizen Name</label>
    <input type="text">

    <label>Phone</label>
    <input type="text">

    <label>Call Type</label>
    <select>
        <option>Question</option>
        <option>Complaint</option>
    </select>

    <label>Category</label>
    <select>
        <option>Registry Office</option>
    </select>

    <label>Description</label>
    <textarea></textarea>

    <button type="submit">Save</button>
</form>
@endsection
