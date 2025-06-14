<div class="modal-dialog modal-lg" role="document">
    <form action="{{ url('/bobot') }}" method="POST" id="form-bobot-kriteria">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atur Bobot Kriteria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();"></button>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="row">
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <canvas id="chart-bobot" height="250"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">
                            @foreach($kriterias as $kriteria)
                                <div class="form-group">
                                    <label>{{ ucfirst($kriteria->kriteria) }}</label>
                                    <input type="range" step="0.01" min="0" max="1" class="form-control-range kriteria-slider"
                                        name="bobot[{{ $kriteria->id }}]" value="{{ $kriteria->bobot }}"
                                        data-id="{{ $kriteria->id }}">
                                    <span id="bobot-text-{{ $kriteria->id }}">{{ $kriteria->bobot }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="location.reload();">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan">Simpan</button>
            </div>
        </div>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const originalData = @json($kriterias);
    let chart;

    function updateChart(data) {
        if (chart) chart.destroy();
        const ctx = document.getElementById('chart-bobot').getContext('2d');
        chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.map(k => k.kriteria),
                datasets: [{
                    data: data.map(k => k.bobot),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    function normalizeSliders(changedId) {
        const sliders = $('.kriteria-slider');
        let total = 0;
        let changedVal = parseFloat($(`input[data-id="${changedId}"]`).val());
        let others = [];

        sliders.each(function () {
            const id = $(this).data('id');
            if (id != changedId) {
                others.push({ id: id, el: $(this), val: parseFloat($(this).val()) });
            }
        });

        let remaining = 1 - changedVal;
        let totalOthers = others.reduce((sum, o) => sum + o.val, 0);

        others.forEach(o => {
            let newVal = totalOthers === 0 ? remaining / others.length : (o.val / totalOthers) * remaining;
            o.el.val(newVal.toFixed(4));
            $(`#bobot-text-${o.id}`).text(newVal.toFixed(4));
        });

        $(`#bobot-text-${changedId}`).text(changedVal.toFixed(4));

        // update chart preview
        let dataPreview = sliders.map(function () {
            return {
                kriteria: $(this).closest('.form-group').find('label').text(),
                bobot: parseFloat($(this).val())
            }
        }).get();

        updateChart(dataPreview);
    }

    $(document).ready(function () {
        $('.kriteria-slider').on('input', function () {
            normalizeSliders($(this).data('id'));
        });

        updateChart(originalData);
    });
    $('#myModal').on('shown.bs.modal', function () {
    $('.kriteria-slider').each(function () {
        const id = $(this).data('id');
        const original = originalData.find(k => k.id == id);
        if (original) {
            $(this).val(original.bobot);
            $(`#bobot-text-${id}`).text(original.bobot);
        }
    });

    // Perbarui chart dengan data awal (dari originalData)
    let initialData = originalData.map(k => ({
        kriteria: k.kriteria,
        bobot: parseFloat(k.bobot)
    }));
    updateChart(initialData);
});

    $('#btn-simpan').on('click', function () {
        const form = $('#form-bobot-kriteria');
        const url = form.attr('action');
        const data = form.serialize();

        $.post(url, data)
            .done(function (response) {
                // Optional: tampilkan notifikasi
                //alert('Data berhasil disimpan!');
                $('#myModal').modal('hide');
                setTimeout(() => location.reload(), 500);
            })
            .fail(function (xhr) {
                alert('Gagal menyimpan data.');
                console.error(xhr.responseText);
            });
    });


</script>