@extends('layouts.app')

@section('title', 'Voter')

@section('content')
    <div class="row">

        @can('voter-create')
            <div class="col-md-12">
                <a href="{{ route('app.voter.create') }}" class="btn btn-primary mb-3">Add Voter</a>
            </div>
        @endcan

        <div class="col-md-12">
            @include('includes.alert')
        </div>

        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Voter</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($voters as $voter)
                                    <tr>
                                        <td>{{ $voter->name }}</td>
                                        <td>{{ $voter->user->email }}</td>
                                        <td>

                                            @can('voter-update')
                                                <a href="{{ route('app.voter.edit', $voter->id) }}"
                                                    class="btn btn-warning">Edit</a>
                                            @endcan

                                            <a href="{{ route('app.voter.show', $voter->id) }}" class="btn btn-info">Show</a>

                                            @can('voter-delete')
                                                <form action="{{ route('app.voter.destroy', $voter->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endsection
