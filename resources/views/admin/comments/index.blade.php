@extends('layouts.app')

@section('title', 'Quản lý bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý bình luận</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sản phẩm có bình luận</h3>
            </div>
            <div class="card-body">
                @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">STT</th>
                                <th style="width: 100px">Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th style="width: 120px" class="text-center">Tổng bình luận</th>
                                <th style="width: 300px">Bình luận mới nhất</th>
                                <th style="width: 150px">Thời gian</th>
                                <th style="width: 120px" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                            @php
                                $latestComment = $product->comments->first();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ preg_match('/^https?:\/\//', $product->image) ? $product->image : asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td class="text-center">
                                    <span  style="font-size: 20px;">
{{ $product->comments_count }}
                                    </span>
                                </td>
                                <td>
                                    @if($latestComment)
                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $latestComment->content }}">
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $latestComment->user->name ?? 'Khách' }}:
                                            </small>
                                            <br>
                                            "{{ Str::limit($latestComment->content, 80) }}"
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($latestComment)
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i> {{ $latestComment->created_at->diffForHumans() }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.comments.show', $product->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="icon fas fa-info"></i>
                    Chưa có sản phẩm nào có bình luận.
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
