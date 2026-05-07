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

// 🔥 ambil dari API kamu
fetch('dashboard_engine.php?date=' + today)
.then(res => res.json())
.then(data => renderTable(data))
.catch(err => {
    console.error(err);
    alert('Gagal load dashboard');
});

function renderTable(data) {
    const map = {};

    function insert(arr, key) {
        (arr || []).forEach(row => {
            if (!map[row.created_at]) {
                map[row.created_at] = { created_at: row.created_at };
            }
            map[row.created_at][key] = parseFloat(row.value) || 0;
        });
    }

    insert(data.brix_npp, 'brix');
    insert(data.pol_npp, 'pol');
    insert(data.hk_npp, 'hk');
    insert(data.rendemen_npp, 'rendemen');
    insert(data.hk_tetes, 'hk_tetes');
    insert(data.iu_gkp, 'iu_gkp');
    insert(data.tebu_tergiling, 'tebu');
    insert(data.flow_nm, 'nm');
    insert(data.flow_imb, 'imb');

    const tbody = document.querySelector('#dashboardTable tbody');
    tbody.innerHTML = '';

    // sort by time
    const rows = Object.values(map).sort((a,b) => 
        new Date(a.created_at) - new Date(b.created_at)
    );

    rows.forEach(r => {
        tbody.innerHTML += `
            <tr>
                <td>${formatJam(r.created_at)}</td>
                <td>${val(r.brix)}</td>
                <td>${val(r.pol)}</td>
                <td>${val(r.hk)}</td>
                <td>${val(r.rendemen)}</td>
                <td>${val(r.hk_tetes)}</td>
                <td>${val(r.iu_gkp)}</td>
                <td>${val(r.tebu)}</td>
                <td>${val(r.nm)}</td>
                <td>${val(r.imb)}</td>
            </tr>
        `;
    });
}

/* ================= HELPERS ================= */

function val(v){
    return (v || v === 0) ? Number(v).toFixed(2) : '-';
}

function formatJam(datetime){
    const d = new Date(datetime);
    const h1 = String(d.getHours()).padStart(2,'0');
    const h2 = String((d.getHours()+1)%24).padStart(2,'0');
    return `${h1}:00 - ${h2}:00`;
}
</script>

<?php include('footer.php'); ?>