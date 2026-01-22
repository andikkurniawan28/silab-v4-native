<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">
<?php
    setlocale(LC_ALL, 'IND');
    $bulan_indonesia = [
        "01" => "Januari",
        "02" => "Februari",
        "03" => "Maret",
        "04" => "April",
        "05" => "Mei",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "Agustus",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember",
    ];
?>
<head>
    <title>Sistem Informasi Laboratorium</title>
	<link rel="icon" type="image/png" href="/Silab-v3/public/admin_template/img/QC.png"/>
    <style type="text/css">
        p {
            margin: 0;
            padding: 0;
        }

        .ft10 {
            font-size: 16px;
            font-family: Times;
            color: #006fc0;
        }

        .ft11 {
            font-size: 18px;
            font-family: Times;
            color: #000000;
        }

        .ft12 {
            font-size: 10px;
            font-family: Times;
            color: #006fc0;
        }

        .ft13 {
            font-size: 16px;
            font-family: Times;
            color: #006fc0;
        }

        .ft14 {
            font-size: 14px;
            font-family: Times;
            color: #006fc0;
        }

        .ft15 {
            font-size: 16px;
            font-family: Times;
            color: #000000;
        }

        .ft16 {
            font-size: 16px;
            font-family: Times;
            color: #000000;
        }

        .ft17 {
            font-size: 12px;
            font-family: Times;
            color: #000000;
        }

        .ft18 {
            font-size: 10px;
            font-family: Times;
            color: #000000;
        }

        .ft19 {
            font-size: 14px;
            font-family: Times;
            color: #000000;
        }

        .ft110 {
            font-size: 12px;
            font-family: Times;
            color: #000000;
        }

        .ft111 {
            font-size: 11px;
            font-family: Times;
            color: #000000;
        }

        .ft112 {
            font-size: 11px;
            font-family: Times;
            color: #000000;
        }

        .ft113 {
            font-size: 14px;
            line-height: 19px;
            font-family: Times;
            color: #006fc0;
        }

        .ft114 {
            font-size: 16px;
            line-height: 20px;
            font-family: Times;
            color: #000000;
        }

        .ft115 {
            font-size: 16px;
            line-height: 23px;
            font-family: Times;
            color: #000000;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <br />
</head>

<body bgcolor="#A0A0A0" vlink="blue" link="blue">
    <div id="page1-div" style="position:relative;width:892px;height:1263px;">
        <img width="892" height="1263" src="/Silab-V3/public/admin_template/img/coa_tetes_3_tangki.png" alt="background image" />
        <p style="position:absolute;top:68px;left:85px;white-space:nowrap" class="ft10"><b>&#160;</b></p>
        <p style="position:absolute;top:68px;left:457px;white-space:nowrap" class="ft10"><b>&#160;</b></p>
        <p style="position:absolute;top:68px;left:763px;white-space:nowrap" class="ft10"><b>&#160;</b></p>
        <p style="position:absolute;top:93px;left:830px;white-space:nowrap" class="ft11"><b>&#160;</b></p>
        <p style="position:absolute;top:120px;left:457px;white-space:nowrap" class="ft11"><b>&#160;</b></p>
        <p style="position:absolute;top:148px;left:85px;white-space:nowrap" class="ft11"><b>&#160;</b></p>
        <p style="position:absolute;top:1229px;left:112px;white-space:nowrap" class="ft12"></p>
        <p style="position:absolute;top:184px;left:340px;white-space:nowrap" class="ft10">
            <b>LAPORAN&#160;HASIL&#160;ANALISA&#160;</b></p>
        <p style="position:absolute;top:205px;left:337px;white-space:nowrap" class="ft13">
            <i><b>CERTIFICATE&#160;OF&#160;ANALYSIS&#160;</b></i></p>
        <p style="position:absolute;top:225px;left:457px;white-space:nowrap" class="ft113"><b>&#160;<br />&#160;</b>
        </p>
        <p style="position:absolute;top:263px;left:85px;white-space:nowrap" class="ft15">No.&#160;</p>
        <p style="position:absolute;top:263px;left:139px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:263px;left:193px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:263px;left:248px;white-space:nowrap" class="ft15">
            :&#160;<?= $nomor_dokumen ?><b>&#160;</b></p>
        <p style="position:absolute;top:286px;left:85px;white-space:nowrap" class="ft17"><i>No&#160;</i></p>
        <p style="position:absolute;top:306px;left:85px;white-space:nowrap" class="ft15">Contoh&#160;<b>&#160;</b></p>
        <p style="position:absolute;top:306px;left:193px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:306px;left:248px;white-space:nowrap" class="ft15">
            :&#160;Tetes&#160;Tangki&#160;</p>
        <p style="position:absolute;top:330px;left:85px;white-space:nowrap" class="ft17"><i>Sample&#160;</i></p>
        <p style="position:absolute;top:350px;left:85px;white-space:nowrap" class="ft15">Tanggal&#160;Terima&#160;</p>
        <p style="position:absolute;top:350px;left:248px;white-space:nowrap" class="ft15">
            :&#160;<?= date("d", strtotime($created_at)) ?>&#160;<?= $bulan_indonesia[date("m", strtotime($created_at))] ?>&#160;<?= date("Y", strtotime($created_at)) ?>&#160;</p>
        <p style="position:absolute;top:374px;left:85px;white-space:nowrap" class="ft17">
            <i>Date&#160;of&#160;received&#160;</i></p>
        <p style="position:absolute;top:394px;left:85px;white-space:nowrap" class="ft15">Tanggal&#160;Pengujian&#160;
        </p>
        <p style="position:absolute;top:394px;left:248px;white-space:nowrap" class="ft15">
            :&#160;<?= date("d", strtotime($created_at)) ?>&#160;<?= $bulan_indonesia[date("m", strtotime($created_at))] ?>&#160;<?= date("Y", strtotime($created_at)) ?>&#160;</p>
        <p style="position:absolute;top:417px;left:85px;white-space:nowrap" class="ft17">
            <i>Date&#160;of&#160;analysis&#160;</i></p>
        <p style="position:absolute;top:447px;left:421px;white-space:nowrap" class="ft16">
            <b>H&#160;A&#160;S&#160;I&#160;L&#160;</b></p>
        <p style="position:absolute;top:468px;left:457px;white-space:nowrap" class="ft114"><b>&#160;<br />&#160;</b>
        </p>
        <p style="position:absolute;top:510px;left:101px;white-space:nowrap" class="ft16"><b>No&#160;</b></p>
        <p style="position:absolute;top:530px;left:103px;white-space:nowrap" class="ft17"><i>No&#160;</i></p>
        <p style="position:absolute;top:510px;left:172px;white-space:nowrap" class="ft16"><b>Parameter&#160;</b></p>
        <p style="position:absolute;top:530px;left:181px;white-space:nowrap" class="ft17"><i>Parameter&#160;</i></p>
        <p style="position:absolute;top:510px;left:312px;white-space:nowrap" class="ft16"><b>Satuan&#160;</b></p>
        <p style="position:absolute;top:530px;left:326px;white-space:nowrap" class="ft17"><i>Unit&#160;</i></p>
        <p style="position:absolute;top:510px;left:479px;white-space:nowrap" class="ft16">
            <b>Hasil&#160;Pengujian&#160;</b></p>
        <p style="position:absolute;top:530px;left:507px;white-space:nowrap" class="ft17"><i>Test&#160;Result</i></p>
        <p style="position:absolute;top:527px;left:573px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:510px;left:730px;white-space:nowrap" class="ft16"><b>Metode&#160;</b></p>
        <p style="position:absolute;top:530px;left:736px;white-space:nowrap" class="ft17"><i>Method&#160;</i></p>
        <p style="position:absolute;top:549px;left:407px;white-space:nowrap" class="ft16"><b>Tangki&#160;1&#160;</b>
        </p>
        <p style="position:absolute;top:549px;left:510px;white-space:nowrap" class="ft16"><b>Tangki&#160;2&#160;</b>
        </p>
        <p style="position:absolute;top:549px;left:608px;white-space:nowrap" class="ft16"><b>Tangki&#160;3&#160;</b>
        </p>

        <p style="position:absolute;top:570px;left:108px;white-space:nowrap" class="ft15">1&#160;</p>
        <p style="position:absolute;top:570px;left:197px;white-space:nowrap" class="ft15">Brix&#160;</p>
        <p style="position:absolute;top:570px;left:332px;white-space:nowrap" class="ft15">%&#160;</p>
        <p style="position:absolute;top:570px;left:422px;white-space:nowrap" class="ft15"><?php if($samples[0]->brix != NULL): ?> <?= number_format($samples[0]->brix, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:570px;left:524px;white-space:nowrap" class="ft15"><?php if($samples[1]->brix != NULL): ?> <?= number_format($samples[1]->brix, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:570px;left:623px;white-space:nowrap" class="ft15"><?php if($samples[2]->brix != NULL): ?> <?= number_format($samples[2]->brix, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:570px;left:717px;white-space:nowrap" class="ft15">Brixwegger&#160;</p>

        <p style="position:absolute;top:591px;left:108px;white-space:nowrap" class="ft15">2&#160;</p>
        <p style="position:absolute;top:591px;left:193px;white-space:nowrap" class="ft15">TSAI&#160;</p>
        <p style="position:absolute;top:591px;left:332px;white-space:nowrap" class="ft15">%&#160;</p>
        <p style="position:absolute;top:591px;left:422px;white-space:nowrap" class="ft15"><?php if($samples[0]->tsai != NULL): ?> <?= number_format($samples[0]->tsai, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:591px;left:524px;white-space:nowrap" class="ft15"><?php if($samples[1]->tsai != NULL): ?> <?= number_format($samples[1]->tsai, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:591px;left:623px;white-space:nowrap" class="ft15"><?php if($samples[2]->tsai != NULL): ?> <?= number_format($samples[2]->tsai, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:591px;left:715px;white-space:nowrap" class="ft15">Lane-Eynon&#160;</p>

        <p style="position:absolute;top:612px;left:108px;white-space:nowrap" class="ft15">3&#160;</p>
        <p style="position:absolute;top:612px;left:157px;white-space:nowrap" class="ft15">Optical&#160;Density&#160;</p>
        <p style="position:absolute;top:612px;left:336px;white-space:nowrap" class="ft15">-&#160;</p>
        <p style="position:absolute;top:612px;left:419px;white-space:nowrap" class="ft15"><?php if($samples[0]->od != NULL):?> <?= number_format($samples[0]->od, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:612px;left:520px;white-space:nowrap" class="ft15"><?php if($samples[1]->od != NULL):?> <?= number_format($samples[1]->od, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:612px;left:618px;white-space:nowrap" class="ft15"><?php if($samples[2]->od != NULL):?> <?= number_format($samples[2]->od, 2) ?> <?php endif ?>&#160;</p>
        <p style="position:absolute;top:612px;left:698px;white-space:nowrap" class="ft15">Spektrofotometri&#160;</p>

        <p style="position:absolute;top:632px;left:85px;white-space:nowrap" class="ft18">&#160;</p>
        <p style="position:absolute;top:649px;left:85px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:650px;left:106px;white-space:nowrap" class="ft19">Catatan&#160;</p>
        <p style="position:absolute;top:650px;left:193px;white-space:nowrap" class="ft19">
            :&#160;Hasil&#160;analisa&#160;hanya&#160;berdasarkan&#160;sampel&#160;yang&#160;diterima</p>
        <p style="position:absolute;top:651px;left:561px;white-space:nowrap" class="ft110">&#160;</p>
        <p style="position:absolute;top:670px;left:85px;white-space:nowrap" class="ft17"><i>&#160;</i></p>
        <p style="position:absolute;top:671px;left:106px;white-space:nowrap" class="ft111">
            <i>Notice&#160;&#160;&#160;</i></p>
        <p style="position:absolute;top:671px;left:193px;white-space:nowrap" class="ft112">
            :<i>&#160;This&#160;report&#160;is&#160;based&#160;on&#160;testing&#160;sample&#160;only</i></p>
        <p style="position:absolute;top:670px;left:433px;white-space:nowrap" class="ft17"><i>&#160;</i></p>
        <p style="position:absolute;top:690px;left:625px;white-space:nowrap" class="ft115">
            &#160;<br />&#160;<br />&#160;<br />&#160;<br />&#160;</p>
        <p style="position:absolute;top:809px;left:112px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:832px;left:625px;white-space:nowrap" class="ft15">
            Malang,&#160;<?= date("d") ?>&#160;<?= $bulan_indonesia[date("m")] ?>&#160;<?= date("Y") ?><b>&#160;</b></p>
        <p style="position:absolute;top:856px;left:214px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:355px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:376px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:430px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:484px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:538px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:593px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:646px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:856px;left:701px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:187px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:328px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:350px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:404px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:458px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:512px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:566px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:620px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:674px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:880px;left:728px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:904px;left:457px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:928px;left:387px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:928px;left:528px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:85px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:226px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:248px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:301px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:356px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:409px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:464px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:517px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:572px;white-space:nowrap" class="ft15">&#160;</p>
        <p style="position:absolute;top:952px;left:640px;white-space:nowrap" class="ft16">
            <b>&#160;&#160;&#160;&#160;&#160;Tri&#160;Sunu&#160;Hardi&#160;</b></p>
        <p style="position:absolute;top:976px;left:85px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:226px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:248px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:301px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:356px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:409px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:464px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:517px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:572px;white-space:nowrap" class="ft16"><b>&#160;</b></p>
        <p style="position:absolute;top:976px;left:652px;white-space:nowrap" class="ft15">
            Kabag.&#160;Pabrikasi&#160;</p>
    </div>
</body>

</html>
