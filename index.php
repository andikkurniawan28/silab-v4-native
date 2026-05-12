<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
    'Koordinator QC', 
    'Mandor Off Farm', 
    'Analis Off Farm', 
    'Mandor On Farm', 
    'Analis On Farm', 
    'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);
include('header_rev.php'); 

?>

<!-- VIEW -->
<div class="container-fluid">
    <h4 class="mb-3">Home</h4>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm text-center text-dark" id="dashboardTable">
                <thead class="table-dark">
                    <tr>
                        <th>Jam</th>
                        <th>Brix NPP</th>
                        <th>Pol NPP</th>
                        <th>HK NPP</th>
                        <th>%R NPP</th>
                        <th>Pol Ampas</th>
                        <th>HK Tetes</th>
                        <th>IU GKP</th>
                        <th>Tebu Tergiling</th>
                        <th>Flow NM</th>
                        <th>Flow IMB</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
const today = new Date().toISOString().slice(0,10);

// 🔥 ambil dari API
fetch('dashboard_engine.php?date=' + today)
.then(res => res.json())
.then(data => renderTable(data))
.catch(err => {
    console.error(err);
    alert('Gagal load dashboard');
});

function renderTable(data) {

    const map = {};

    /* =========================================
       INSERT DATA KE MAP
    ========================================= */
    function insert(arr, key) {
        (arr || []).forEach(row => {

            // pastikan format key per jam
            const hourKey = row.created_at.substring(0,13);

            if (!map[hourKey]) {
                map[hourKey] = {};
            }

            map[hourKey][key] = parseFloat(row.value) || 0;
        });
    }

    insert(data.brix_npp, 'brix');
    insert(data.pol_npp, 'pol');
    insert(data.hk_npp, 'hk');
    insert(data.rendemen_npp, 'rendemen');
    insert(data.hk_tetes, 'hk_tetes');
    insert(data.iu_gkp, 'iu_gkp');
    insert(data.pol_ampas, 'pol_ampas');
    insert(data.tebu_tergiling, 'tebu');
    insert(data.flow_nm, 'nm');
    insert(data.flow_imb, 'imb');

    const tbody = document.querySelector('#dashboardTable tbody');
    tbody.innerHTML = '';

    /* =========================================
       LOOP 24 JAM SHIFT
       06:00 → 05:00
    ========================================= */
    const baseDate = new Date(today + 'T06:00:00');

    for(let i = 0; i < 24; i++) {

        const current = new Date(baseDate);
        current.setHours(current.getHours() + i);

        const hourKey =
            current.getFullYear() + '-' +
            String(current.getMonth()+1).padStart(2,'0') + '-' +
            String(current.getDate()).padStart(2,'0') + ' ' +
            String(current.getHours()).padStart(2,'0');

        const row = map[hourKey] || {};

        tbody.innerHTML += `
            <tr>
                <td>${formatJam(current)}</td>
                <td>${val(row.brix)}</td>
                <td>${val(row.pol)}</td>
                <td>${val(row.hk)}</td>
                <td>${val(row.rendemen)}</td>
                <td>${val(row.pol_ampas)}</td>
                <td>${val(row.hk_tetes)}</td>
                <td>${val(row.iu_gkp)}</td>
                <td>${val(row.tebu)}</td>
                <td>${val(row.nm)}</td>
                <td>${val(row.imb)}</td>
            </tr>
        `;
    }
}

/* =========================================
   HELPERS
========================================= */

function val(v){
    return (v || v === 0)
        ? Number(v).toFixed(2)
        : '';
}

function formatJam(dateObj){

    const h1 = String(dateObj.getHours()).padStart(2,'0');

    const next = new Date(dateObj);
    next.setHours(next.getHours() + 1);

    const h2 = String(next.getHours()).padStart(2,'0');

    return `${h1}:00 - ${h2}:00`;
}
</script>

<?php include('footer.php'); ?>