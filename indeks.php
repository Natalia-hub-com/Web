<?php
session_start();

// ================= DATA PRODUK =================
$produk = [
    1 => ["nama"=>"Lipstick Matte", "harga"=>90000, "gambar"=>"lipstik_matte.webp"],
    2 => ["nama"=>"Bedak Glow", "harga"=>105000, "gambar"=>"bedak_glow.jpg"],
    3 => ["nama"=>"Foundation Smooth", "harga"=>80000, "gambar"=>"foundation.webp"],
    4 => ["nama"=>"Maskara Lash", "harga"=>65000, "gambar"=>"maskara.jpg"],
	5 => ["nama"=>"Primer", "harga"=>85000, "gambar"=>"primer.jpg"],
	6 => ["nama"=>"Blush on", "harga"=>75000, "gambar"=>"blush_on.jpg"],
	7 => ["nama"=>"Eyliner", "harga"=>65000, "gambar"=>"eyeliner.jpg"],
	8 => ["nama"=>"Eyeshadow", "harga"=>95000,  "gambar"=>"eyeshadow.jpg"],
];

// ================= FUNGSI RUPIAH =================
function rupiah($angka){
    return "Rp " . number_format($angka,0,',','.');
}

// ================= TAMBAH KE KERANJANG =================
if(isset($_GET['tambah'])){
    $id = $_GET['tambah'];
    $_SESSION['keranjang'][$id] = ($_SESSION['keranjang'][$id] ?? 0) + 1;
    header("Location: ".$_SERVER['PHP_SELF']);
}

// ================= KURANG =================
if(isset($_GET['kurang'])){
    $id = $_GET['kurang'];
    $_SESSION['keranjang'][$id]--;
    if($_SESSION['keranjang'][$id] <= 0){
        unset($_SESSION['keranjang'][$id]);
    }
    header("Location: ".$_SERVER['PHP_SELF']);
}

// ================= RESET =================
if(isset($_GET['reset'])){
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
}

// ================= TOTAL =================
$total = 0;
if(isset($_SESSION['keranjang'])){
    foreach($_SESSION['keranjang'] as $id => $qty){
        $total += $produk[$id]['harga'] * $qty;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasir Kosmetik NATALIA</title>
    <style>
        body {
            font-family: Arial;
            background: #ffeef5;
        }
        h1 {
            text-align:center;
            color:#d63384;
        }
        .container {
            width:90%;
            margin:auto;
        }
        .produk {
            display:inline-block;
            width:200px;
            background:white;
            margin:10px;
            padding:10px;
            text-align:center;
            border-radius:10px;
            box-shadow:0 0 10px #ccc;
        }
        .produk img {
            width:100px;
        }
        .btn {
            padding:5px 10px;
            background:#d63384;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }
        table {
            width:100%;
            background:white;
            margin-top:20px;
            border-collapse:collapse;
        }
        table th, td {
            padding:10px;
            border-bottom:1px solid #ccc;
        }
        .total {
            font-size:20px;
            font-weight:bold;
        }
        .bayar {
            margin-top:10px;
        }
        .struk {
            background:white;
            padding:20px;
            margin-top:20px;
        }
    </style>
</head>
<body>

<h1>KASIR TOKO KOSMETIK NATALIA</h1>

<div class="container">

<!-- ================= PRODUK ================= -->
<h2>Daftar Produk</h2>
<?php foreach($produk as $id => $p){ ?>
    <div class="produk">
        <img src="<?= $p['gambar'] ?>">
        <h3><?= $p['nama'] ?></h3>
        <p><?= rupiah($p['harga']) ?></p>
        <a class="btn" href="?tambah=<?= $id ?>">Tambah</a>
    </div>
<?php } ?>

<!-- ================= KERANJANG ================= -->
<h2>Keranjang</h2>
<table>
<tr>
<th>Produk</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Subtotal</th>
<th>Aksi</th>
</tr>

<?php if(!empty($_SESSION['keranjang'])){ ?>
    <?php foreach($_SESSION['keranjang'] as $id => $qty){ 
        $sub = $produk[$id]['harga'] * $qty;
    ?>
    <tr>
        <td><?= $produk[$id]['nama'] ?></td>
        <td><?= rupiah($produk[$id]['harga']) ?></td>
        <td><?= $qty ?></td>
        <td><?= rupiah($sub) ?></td>
        <td>
            <a class="btn" href="?tambah=<?= $id ?>">+</a>
            <a class="btn" href="?kurang=<?= $id ?>">-</a>
        </td>
    </tr>
    <?php } ?>
<?php }else{ ?>
<tr><td colspan="5"></td></tr>

<?php } ?>


<tr>
    <td colspan="4" class="total">TOTAL =</td>
    <td colspan="2" class="total"><?= rupiah($total) ?></td>
</tr>
</table>

<!-- ================= PEMBAYARAN ================= -->
<form method="POST">
    <div class="bayar">
        <input type="number" name="bayar" placeholder="Masukkan uang" required>
        <button class="btn" type="submit">Bayar</button>
        <a class="btn" href="?reset=1">Reset</a>
    </div>
</form>

<!-- ================= STRUK ================= -->
<?php
if(isset($_POST['bayar'])){
    $bayar = $_POST['bayar'];
    $kembalian = $bayar - $total;
?>
<div class="struk">
    <h2>STRUK PEMBAYARAN</h2>
    <hr>
    <?php foreach($_SESSION['keranjang'] as $id => $qty){ ?>
        <p><?= $produk[$id]['nama'] ?> x<?= $qty ?></p>
    <?php } ?>
    <hr>
    <p>Total: <?= rupiah($total) ?></p>
    <p>Bayar: <?= rupiah($bayar) ?></p>
    <p>Kembalian: <?= rupiah($kembalian) ?></p>
    <hr>
    <button onclick="window.print()">Cetak</button>
</div>
<?php } ?>

</div>

</body>
</html>