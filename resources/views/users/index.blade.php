@extends('layouts.app')

@section('title')
/ Users
@endsection

@section('content')
<section class="content container">
    @if (session('error'))
        <div id ="alert-message" class="alert alert-danger my-4 alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger pb-0 alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
        <div id ="alert-message" class="alert alert-success mb-4 alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success rounded me-2" onclick="createmodalShow(event)" data-bs-toggle="modal"
                data-bs-target="#create-user">Create New User</button>
    </div>

    <div class="table-responsive">
        {{ $dataTable->table(['class' => 'table table-bordered table-striped w-100']) }}
    </div>

    <!-- Create User Modal -->
    @include('users.create')

    <!-- Show User Modal -->
    @include('users.show')

    <!-- Edit User Modal -->
    @include('users.edit')

    <!-- Delete User Modal -->
    @include('users.delete')

</section>

@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        setTimeout(function() {
            $('.alert-success').fadeOut();
        }, {{ session('timeout') ?? 3000 }});
    </script>
@endpush
