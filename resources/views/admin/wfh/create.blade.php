@extends('layouts.main')

@section('title', 'Ajukan WFH')

@section('content')
<div class="container">
    <h1>Ajukan WFH</h1>
    <form action="{{ route('admin.wfh.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
    </form>
</div>
@endsection