<div class="row">
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="price" class="form-label">Harga Produk</label>
            <input type="number" class="form-control" name="price" value="{{ old('price', $product->price) }}"
                id="price">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="qty" class="form-label">Jumlah Produk</label>
            <input type="number" class="form-control" name="qty"
                value="{{ old('qty', $product->productInventory ? $product->productInventory->qty : null) }}"
                id="qty">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="input_weight" class="form-label">Berat Produk</label>
            <input type="number" step="0.01" class="form-control" name="input_weight"
                value="{{ old('input_weight', $product->weight) }}" id="input_weight">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="weight_unit_id" class="form-label">Satuan Berat</label>
            <select class="form-control" name="weight_unit_id" id="weight_unit_id">
                @foreach ($weightUnits as $weightUnit)
                    <option value="{{ $weightUnit->id }}" data-weight="{{ $weightUnit->weight }}"
                        {{ old('weight_unit_id', $product->weight_unit_id) == $weightUnit->id ? 'selected' : '' }}>
                        {{ $weightUnit->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="weight" class="form-label">Berat Total (kg)</label>
            <input type="number" step="0.01" class="form-control" name="weight"
                value="{{ old('weight', $product->weight) }}" id="weight" readonly>
        </div>
    </div>
</div>
@push('script-alt')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputWeight = document.getElementById('input_weight');
            const weightUnitSelect = document.getElementById('weight_unit_id');
            const totalWeight = document.getElementById('weight');

            function calculateWeight() {
                const weight = parseFloat(inputWeight.value) || 0;
                const selectedOption = weightUnitSelect.options[weightUnitSelect.selectedIndex];
                const unitWeight = parseFloat(selectedOption.dataset.weight) || 1;

                const calculatedWeight = weight * unitWeight;
                totalWeight.value = calculatedWeight.toFixed(2);
            }

            inputWeight.addEventListener('input', calculateWeight);
            weightUnitSelect.addEventListener('change', calculateWeight);

            // Initial calculation
            calculateWeight();
        });
    </script>
@endpush
