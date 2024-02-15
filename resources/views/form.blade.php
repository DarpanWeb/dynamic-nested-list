@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 style="text-align:center; padding:10px">Nested List Form</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('updateList') }}">
                @csrf
                <table class="table">
                    <tbody>
                        @foreach($firstLevelItems as $item)
                            @if(is_null($item->parent_id))
                                @include('nested-list', ['items' => $items, 'item' => $item])
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary mt-3">Update Items</button>
            </form>
        </div>
    </div>
</div>
@endsection