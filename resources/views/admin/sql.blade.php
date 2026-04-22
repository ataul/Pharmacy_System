@extends('layouts.app')

@section('title')
/ SQL
@endsection

@section('content')
    <section class="content container">
        @if (session('error'))
        <div id ="alert-message" class="alert alert-danger my-4 alert-dismissible">
            {{ session('error') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger pb-0 alert-dismissible">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
    </div>
    @endif

    @if(session('success'))
        <div id ="alert-message" class="alert alert-success mb-4 mb-0 alert-dismissible">
            {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
        </div>
    @endif
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>SQL Runner</h6>
                    </div>
                    <div class="card-body px-4 pt-0 pb-2">
                        @if ($error)
                            <div class="alert alert-danger text-white" role="alert">
                                {{ $error }}
                            </div>
                        @endif

                        <form action="{{ route('setup.sql') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="sql">Enter SQL Query</label>
                                <textarea name="sql" id="sql" rows="5" class="form-control" placeholder="SELECT * FROM users LIMIT 10;">{{ $sql }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Run</button>
                        </form>

                        @if ($results !== null)
                            <div class="mt-4">
                                <h6>Results</h6>
                                @if (is_array($results))
                                    @if (count($results) > 0)
                                        <div class="table-responsive p-0">
                                            <table class="table align-items-center mb-0">
                                                <thead>
                                                    <tr>
                                                        @foreach ((array) $results[0] as $key => $value)
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ $key }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results as $row)
                                                        <tr>
                                                            @foreach ((array) $row as $value)
                                                                <td class="align-middle text-sm">
                                                                    <span class="text-secondary text-xs font-weight-bold">{{ $value }}</span>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info text-white" role="alert">
                                            No results found.
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-success text-white" role="alert">
                                        Query executed successfully. (Result: {{ var_export($results, true) }})
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
