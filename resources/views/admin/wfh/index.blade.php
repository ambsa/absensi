@extends('layouts.main')

@section('title', 'Pengajuan WFH')

@section('content')
<div class="container">
    <h1>Pengajuan WFH</h1>
    <a href="{{ route('admin.wfh.create') }}" class="btn btn-primary">Ajukan WFH</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wfhs as $wfh)
            <tr>
                <td>{{ $wfh->tanggal }}</td>
                <td>{{ ucfirst($wfh->status) }}</td>
                <td>
                    <a href="{{ route('wfh.show', $wfh->id_wfh) }}" class="btn btn-info">Detail</a>
                    @if ($wfh->status === 'pending')
                        <a href="{{ route('wfh.edit', $wfh->id_wfh) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('wfh.destroy', $wfh->id_wfh) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
