<div class="mb-3">
  <label class="form-label">Name</label>
  <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" class="form-control">
  @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
<div class="mb-3">
  <label class="form-label">Slug (để trống để tự sinh)</label>
  <input type="text" name="slug" value="{{ old('slug', $brand->slug ?? '') }}" class="form-control">
  @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
<div class="mb-3">
  <label class="form-label">Logo</label>
  <input type="file" name="logo" class="form-control" accept="image/*">
  @error('logo')<div class="text-danger small">{{ $message }}</div>@enderror
  @isset($brand)
    @if($brand->logo)
      <div class="mt-2">
        <img src="{{ asset('storage/' . $brand->logo) }}" alt="logo" style="height:60px">
      </div>
    @endif
  @endisset
  </div>

