<?php
session_start();
//Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "inventaris");
//Menambah barang baru
if(isset($_POST['addnewbarang'])){
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn,"insert into stock (nama_barang, deskripsi, stock) values ('$nama_barang', '$deskripsi', '$stock')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Menambah mutasi masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where id_barang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $stocksumqty = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (id_barang, keterangan, qty) values ('$barangnya', '$keterangan', '$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$stocksumqty' where id_barang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//Menambah mutasi keluar
if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where id_barang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    
    if($stocksekarang>=$qty){
        $stocksumqty = $stocksekarang-$qty;
        $addtomasuk = mysqli_query($conn,"insert into keluar (id_barang, penerima, qty) values ('$barangnya', '$penerima', '$qty')");
        $updatestockmasuk = mysqli_query($conn,"update stock set stock='$stocksumqty' where id_barang='$barangnya'");
        if($addtomasuk&&$updatestockmasuk){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        echo '
            <script>
                alert("Stock saat ini tidak mencukupi.");
                window.location.href="keluar.php";
            </script>
            ';
    }
}

//Update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    
    $update = mysqli_query($conn,"update stock set nama_barang='$nama_barang', deskripsi='$deskripsi' where id_barang='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn,"delete from stock where id_barang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};

//Update barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where id_barang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn,"select * from masuk where id_masuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg+$selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where id_barang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$keterangan' where id_masuk='$idm'");
        if($kurangistocknya&&$updatenya){
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg-$selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where id_barang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$keterangan' where id_masuk='$idm'");
        if($kurangistocknya&&$updatenya){
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
}

//Menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn,"select * from stock where id_barang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock-$qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where id_barang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where id_masuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//Update barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where id_barang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn,"select * from keluar where id_keluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg-$selisih;

        if($selisih<=$stockskrg){
            $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where id_barang='$idb'");
            $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima='$penerima' where id_keluar='$idk'");
            if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php');
            }
        } else {
            echo '
            <script>
                alert("Stock saat ini tidak mencukupi.");
                window.location.href="keluar.php";
            </script>
            ';
        }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg+$selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where id_barang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima='$penerima' where id_keluar='$idk'");
        if($kurangistocknya&&$updatenya){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}

//Menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn,"select * from stock where id_barang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock+$qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where id_barang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where id_keluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}

?>