@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Sửa sản phẩm #{{ $product->id }}</h1>
        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-outline-primary">Quản lý biến thể</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold">Vui lòng kiểm tra lỗi:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update',$product->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf @method('PUT')

        <div class="col-md-8">
            <label class="form-label">Tên sản phẩm *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$product->name) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giá (VND) *</label>
            <input type="number" name="price" min="0" step="1" class="form-control" value="{{ old('price',$product->price) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Danh mục *</label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id',$product->category_id)==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thương hiệu *</label>
            <select name="brand_id" class="form-select" required>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" @selected(old('brand_id',$product->brand_id)==$b->id)>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label class="form-label">Ảnh hiện tại</label>
            <div class="mb-2">
                @if($product->image)
                    @if(Str::startsWith($product->image, ['http://','https://']))
                        <img src="{{ $product->image }}" alt="" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                    @else
                        <img src="{{ asset('storage/'.$product->image) }}" alt="" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                    @endif
                @else
                    <span class="text-muted">Chưa có ảnh</span>
                @endif
            </div>
            <label class="form-label">Đổi ảnh</label>
            <input type="file" name="image" class="form-control">
           
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh phụ hiện có</label>
            @if($product->images && $product->images->count())
                <div class="d-flex flex-wrap gap-3">
                    @foreach($product->images as $img)
                        <div class="border rounded p-2" style="width:140px;">
                            @php
                                $imgUrl = Str::startsWith($img->image_url, ['http://','https://'])
                                    ? $img->image_url
                                    : asset('storage/'.$img->image_url);
                            @endphp
                            <img src="{{ $imgUrl }}" class="img-fluid rounded mb-2" style="width:100%;height:100px;object-fit:cover;" alt="Extra image {{ $loop->iteration }}">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remove_image_{{ $img->id }}" name="remove_images[]" value="{{ $img->id }}">
                                <label class="form-check-label small text-danger" for="remove_image_{{ $img->id }}">
                                    Xoá ảnh này
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Chưa có ảnh phụ nào.</p>
            @endif
        </div>

        <div class="col-12">
            <label class="form-label d-flex justify-content-between">
                <span>Thêm ảnh phụ mới</span>
                <small class="text-muted">Có thể chọn nhiều ảnh (tối đa 10 mỗi lần)</small>
            </label>
            <input type="file" name="extra_images[]" class="form-control" multiple>
            <small class="text-muted">Ảnh phụ sẽ hiển thị trong gallery sản phẩm.</small>
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" rows="5" class="form-control">{{ old('description',$product->description) }}</textarea>
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection
