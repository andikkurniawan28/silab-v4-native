function hitungBJB() {

    berat_ayakan1 = document.getElementById("berat_ayakan1").value;
    berat_ayakan2 = document.getElementById("berat_ayakan2").value;
    berat_ayakan3 = document.getElementById("berat_ayakan3").value;
    berat_ayakan4 = document.getElementById("berat_ayakan4").value;
    berat_ayakan5 = document.getElementById("berat_ayakan5").value;
    berat_ayakan6 = document.getElementById("berat_ayakan6").value;

    berat_ayakan_plus_shs1 = document.getElementById("berat_ayakan_plus_shs1").value;
    berat_ayakan_plus_shs2 = document.getElementById("berat_ayakan_plus_shs2").value;
    berat_ayakan_plus_shs3 = document.getElementById("berat_ayakan_plus_shs3").value;
    berat_ayakan_plus_shs4 = document.getElementById("berat_ayakan_plus_shs4").value;
    berat_ayakan_plus_shs5 = document.getElementById("berat_ayakan_plus_shs5").value;
    berat_ayakan_plus_shs6 = document.getElementById("berat_ayakan_plus_shs6").value;

    berat_shs1 = berat_ayakan_plus_shs1 - berat_ayakan1;
    berat_shs2 = berat_ayakan_plus_shs2 - berat_ayakan2;
    berat_shs3 = berat_ayakan_plus_shs3 - berat_ayakan3;
    berat_shs4 = berat_ayakan_plus_shs4 - berat_ayakan4;
    berat_shs5 = berat_ayakan_plus_shs5 - berat_ayakan5;
    berat_shs6 = berat_ayakan_plus_shs6 - berat_ayakan6;

    faktor_berat = document.getElementById("faktor_berat1").innerHTML;
    faktor_ayakan1 = document.getElementById("faktor_ayakan1").innerHTML;
    faktor_ayakan2 = document.getElementById("faktor_ayakan2").innerHTML;
    faktor_ayakan3 = document.getElementById("faktor_ayakan3").innerHTML;
    faktor_ayakan4 = document.getElementById("faktor_ayakan4").innerHTML;
    faktor_ayakan5 = document.getElementById("faktor_ayakan5").innerHTML;
    faktor_ayakan6 = document.getElementById("faktor_ayakan6").innerHTML;

    jumlah_all = berat_shs1 + berat_shs2 + berat_shs3 + berat_shs4 + berat_shs5 + berat_shs6;

    fraksi1 = berat_shs1 * 100 / jumlah_all * faktor_ayakan1;
    fraksi2 = berat_shs2 * 100 / jumlah_all * faktor_ayakan2;
    fraksi3 = berat_shs3 * 100 / jumlah_all * faktor_ayakan3;
    fraksi4 = berat_shs4 * 100 / jumlah_all * faktor_ayakan4;
    fraksi5 = berat_shs5 * 100 / jumlah_all * faktor_ayakan5;
    fraksi6 = berat_shs6 * 100 / jumlah_all * faktor_ayakan6;

    varZ = fraksi1 + fraksi2 + fraksi3 + fraksi4 + fraksi5 + fraksi6;

    berat_shs_koreksi1 = berat_shs1 * faktor_berat;
    berat_shs_koreksi2 = berat_shs2 * faktor_berat;
    berat_shs_koreksi3 = berat_shs3 * faktor_berat;
    berat_shs_koreksi4 = berat_shs4 * faktor_berat;
    berat_shs_koreksi5 = berat_shs5 * faktor_berat;
    berat_shs_koreksi6 = berat_shs6 * faktor_berat;

    jumlah1 = berat_shs_koreksi1 * faktor_ayakan1;
    jumlah2 = berat_shs_koreksi2 * faktor_ayakan2;
    jumlah3 = berat_shs_koreksi3 * faktor_ayakan3;
    jumlah4 = berat_shs_koreksi4 * faktor_ayakan4;
    jumlah5 = berat_shs_koreksi5 * faktor_ayakan5;
    jumlah6 = berat_shs_koreksi6 * faktor_ayakan6;

    bjb = 100 / varZ * 10;
    // bjb = 1000 / (jumlah1 + jumlah2 + jumlah3 + jumlah4 + jumlah5 + jumlah6)

    document.getElementById("berat_shs1").innerHTML = berat_shs1.toFixed(2);
    document.getElementById("berat_shs2").innerHTML = berat_shs2.toFixed(2);
    document.getElementById("berat_shs3").innerHTML = berat_shs3.toFixed(2);
    document.getElementById("berat_shs4").innerHTML = berat_shs4.toFixed(2);
    document.getElementById("berat_shs5").innerHTML = berat_shs5.toFixed(2);
    document.getElementById("berat_shs6").innerHTML = berat_shs6.toFixed(2);

    document.getElementById("berat_shs_koreksi1").innerHTML = berat_shs_koreksi1.toFixed(2);
    document.getElementById("berat_shs_koreksi2").innerHTML = berat_shs_koreksi2.toFixed(2);
    document.getElementById("berat_shs_koreksi3").innerHTML = berat_shs_koreksi3.toFixed(2);
    document.getElementById("berat_shs_koreksi4").innerHTML = berat_shs_koreksi4.toFixed(2);
    document.getElementById("berat_shs_koreksi5").innerHTML = berat_shs_koreksi5.toFixed(2);
    document.getElementById("berat_shs_koreksi6").innerHTML = berat_shs_koreksi6.toFixed(2);

    document.getElementById("jumlah1").innerHTML = jumlah1.toFixed(2);
    document.getElementById("jumlah2").innerHTML = jumlah2.toFixed(2);
    document.getElementById("jumlah3").innerHTML = jumlah3.toFixed(2);
    document.getElementById("jumlah4").innerHTML = jumlah4.toFixed(2);
    document.getElementById("jumlah5").innerHTML = jumlah5.toFixed(2);
    document.getElementById("jumlah6").innerHTML = jumlah6.toFixed(2);

    document.getElementById("value").value = bjb.toFixed(2);

    const diameter_lubang1 = 1.7;
    const diameter_lubang2 = 1.18;
    const diameter_lubang3 = 0.85;
    const diameter_lubang4 = 0.6;
    const diameter_lubang5 = 0.3;

    const rerata_partikel1 = (diameter_lubang1 + 1.8) / 2;
    const rerata_partikel2 = (diameter_lubang1 + diameter_lubang2) / 2;
    const rerata_partikel3 = (diameter_lubang2 + diameter_lubang3) / 2;
    const rerata_partikel4 = (diameter_lubang3 + diameter_lubang4) / 2;
    const rerata_partikel5 = (diameter_lubang4 + diameter_lubang5) / 2;
    const base_plate = (diameter_lubang5 + 0.25) / 2;

    let fd1 = berat_shs1 * rerata_partikel1;
    let fd2 = berat_shs2 * rerata_partikel2;
    let fd3 = berat_shs3 * rerata_partikel3;
    let fd4 = berat_shs4 * rerata_partikel4;
    let fd5 = berat_shs5 * rerata_partikel5;
    let fdbp = berat_shs6 * base_plate;

    let jumlah_fd = (fd1 + fd2 + fd3 + fd4 + fd5 + fdbp);
    let ma = jumlah_fd / jumlah_all;

    let fm1 = ma - rerata_partikel1;
    let fm2 = ma - rerata_partikel2;
    let fm3 = ma - rerata_partikel3;
    let fm4 = ma - rerata_partikel4;
    let fm5 = ma - rerata_partikel5;
    let fbp = ma - base_plate;

    let x1 = berat_shs1 * (fm1 * fm1);
    let x2 = berat_shs2 * (fm2 * fm2);
    let x3 = berat_shs3 * (fm3 * fm3);
    let x4 = berat_shs4 * (fm4 * fm4);
    let x5 = berat_shs5 * (fm5 * fm5);
    let xbp = berat_shs6 * (fbp * fbp);

    let xtotal = (x1 + x2 + x3 + x4 + x5 + xbp);
    let sd = Math.sqrt(xtotal / jumlah_all);
    let cv = (sd * 100) / ma;

    console.log("berat_shs1 =", berat_shs1);
    console.log("berat_shs2 =", berat_shs2);
    console.log("berat_shs3 =", berat_shs3);
    console.log("berat_shs4 =", berat_shs4);
    console.log("berat_shs5 =", berat_shs5);
    console.log("berat_shs6 =", berat_shs6);

    console.log("fd1 =", fd1);
    console.log("fd2 =", fd2);
    console.log("fd3 =", fd3);
    console.log("fd4 =", fd4);
    console.log("fd5 =", fd5);
    console.log("fdbp =", fdbp);
    console.log("jumlah_fd =", jumlah_fd);

    console.log("ma =", ma);

    console.log("fm1 =", fm1);
    console.log("fm2 =", fm2);
    console.log("fm3 =", fm3);
    console.log("fm4 =", fm4);
    console.log("fm5 =", fm5);

    console.log("fbp =", fbp);

    console.log("x1 =", x1);
    console.log("x2 =", x2);
    console.log("x3 =", x3);
    console.log("x4 =", x4);
    console.log("x5 =", x5);
    console.log("xbp =", xbp);

    console.log("xtotal =", xtotal);
    console.log("berat_shs =", jumlah_all);
    console.log("sd =", sd);
    console.log("cv =", cv);


    document.getElementById("cv").value = cv.toFixed(2);

}