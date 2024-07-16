@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Produk</h3>
                            <a href="{{ route('gudang.products.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            {{-- <a href="{{ route('gudang.products.product_images.index', $product)}}" class="btn btn-success shadow-sm mr-2 float-right"> <i class="fa fa-image"></i> Upload Gambar Produk</a> --}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('gudang.products.update', $product) }}">
                                @csrf
                                @method('put')
                                <div class="form-group row border-bottom pb-4">
                                    <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="sku"
                                            value="{{ old('sku', $product->sku) }}" id="sku" readonly>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $product->name) }}" id="name" readonly>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="current_qty" class="col-sm-2 col-form-label">Stok Saat Ini</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="current_qty"
                                            value="{{ $inventory->qty ?? '0' }}" id="current_qty" readonly>
                                    </div>
                                </div>

                                <div class="form-group row border-bottom pb-4">
                                    <label for="additional_qty" class="col-sm-2 col-form-label">Tambah Stok</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="additional_qty"
                                            value="{{ old('additional_qty') }}" id="additional_qty" min="0"
                                            step="1">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('style-alt')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('script-alt')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.select-multiple').select2();
    </script>
@endpush
