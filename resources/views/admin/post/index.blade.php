@extends('admin.layouts.master')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('Posts') }}</h1>
    <a href="{{ route('admin_post_create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm"><i class="fas fa-plus"></i> {{ __('Add Item') }}
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="dtable">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Photo') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="photo-container">
                                @if($item->photo)
                                    <a href="{{ asset('uploads/'.$item->photo) }}" class="magnific"><img src="{{ asset('uploads/'.$item->photo) }}" alt="{{ $item->title }}"></a>
                                @else
                                    <span class="text-muted">Aucune photo</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->rPostCategory ? $item->rPostCategory->name : 'Aucune catégorie' }}</td>
                        <td>
                            <a href="{{ route('admin_post_edit',$item->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('admin_post_destroy',$item->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ __('Are you sure?') }}')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection