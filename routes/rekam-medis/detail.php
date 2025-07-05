<?php include '../layouts/header.php'; ?>

<h2>Detail Resep</h2>
<p><strong>Nama Pasien:</strong> <?= $pasien['nama'] ?></p>
<p><strong>Dokter:</strong> <?= $dokter['nama'] ?></p>
<p><strong>Tanggal:</strong> <?= $resep['tanggal'] ?></p>
<p><strong>Catatan:</strong> <?= $resep['catatan'] ?></p>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Nama Obat</th>
            <th>Dosis</th>
            <th>Jumlah</th>
            <th>Aturan Pakai</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resep_detail as $item): ?>
        <tr>
            <td><?= $item['nama_obat'] ?></td>
            <td><?= $item['dosis'] ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td><?= $item['aturan_pakai'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../layouts/footer.php'; ?>
