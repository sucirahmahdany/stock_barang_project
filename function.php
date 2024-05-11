<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");

//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $kodebarang = $_POST['kodebarang'];
    $namabarang = $_POST['namabarang'];
    $satuan = $_POST['satuan'];
    $saldoawal = $_POST['saldoawal'];
    $keterangan = $_POST['keterangan'];

    $addtotable = mysqli_query($conn,"insert into stock (kodebarang, namabarang, satuan, saldoawal, keterangan) values('$kodebarang','$namabarang','$satuan','$saldoawal','$keterangan')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:login.php');
    }
};


//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $namapenerima = $_POST['namapenerima'];
    $jumlahbarang = $_POST['jumlahbarang'];
    $saldomasuk = $_POST['saldomasuk'];
    
    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['saldoawal'];
    $tambahsaldo = $stocksekarang+$jumlahbarang;

    $cekstockmasuk = mysqli_query($conn,"select * from masuk where jumlahbarang='$jumlahbarang'");
    $ambildatamasuk = mysqli_fetch_array($cekstockmasuk);

    $stockmasuk = $ambildatamasuk['jumlahbarang'];
    $updatemasuk = $stockmasuk;
    

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, namapenerima, jumlahbarang) values ('$barangnya','$namapenerima','$jumlahbarang')");
    $updatestockmasuk = mysqli_query($conn,"update stock set saldo='$tambahsaldo', saldomasuk='$updatemasuk' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}


//menambah barang keluar
if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $nomorhal = $_POST['nomorhal'];
    $jumlahbarang = $_POST['jumlahbarang'];
    $unit = $_POST['unit'];
    $saldokeluar = $_POST['saldokeluar'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['saldo'];
    $tambahsaldo = $stocksekarang-$jumlahbarang;

    $cekstockkeluar = mysqli_query($conn,"select * from keluar where jumlahbarang='$jumlahbarang'");
    $ambildatakeluar = mysqli_fetch_array($cekstockkeluar);

    $stockkeluar = $ambildatakeluar['jumlahbarang'];
    $updatekeluar = $stockkeluar;
    

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, nomorhal, jumlahbarang, unit) values ('$barangnya','$penerima','$nomorhal','$jumlahbarang','$unit')");
    $updatestockkeluar = mysqli_query($conn,"update stock set saldo='$tambahsaldo', saldokeluar='$updatekeluar' where idbarang='$barangnya'");
    if($addtokeluar&&$updatestockkeluar){
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}

//update info barang stok
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idbarang'];
    $kodebarang = $_POST['kodebarang'];
    $namabarang = $_POST['namabarang'];
    $satuan = $_POST['satuan'];
    $saldo = $_POST['saldo'];
    $keterangan = $_POST['keterangan'];

    $update = mysqli_query($conn,"update stock set kodebarang='$kodebarang', namabarang='$namabarang', satuan='$satuan', saldo='$saldo', keterangan='$keterangan' where idbarang ='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idbarang'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb =$_POST['idbarang'];
    $idm =$_POST['idmasuk'];
    $jumlahbarang =$_POST['jumlahbarang'];
    $namapenerima =$_POST['namapenerima'];
    
    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['saldo'];

    $jumlahbrgskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $jumlahbrgnya = mysqli_fetch_array($jumlahbrgskrg);
    $jumlahbrgskrg = $jumlahbrgnya['jumlahbarang'];

    if($jumlahbarang>$jumlahbrgskrg){
        $selisih = $jumlahbarang-$jumlahbrgskrg;
        $kurangin = $stockskrg+$selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set saldo='$kurangin' where idbarang='$idb'");
        $updatebrgmasuknya = mysqli_query($conn,"update masuk set jumlahbarang='$jumlahbarang', namapenerima='$namapenerima' where idmasuk='$idm'");
            if($kurangistocknya&&$updatebrgmasuknya){
                header('location:masuk.php');
            }else {
                echo 'Gagal';
                header('location:masuk.php');
            }
    } else {
        $selisih = $jumlahbrgskrg-$jumlahbarang;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set saldo='$kurangin' where idbarang='$idb'");
        $updatebrgmasuknya = mysqli_query($conn,"update masuk set jumlahbarang='$jumlahbarang', namapenerima='$namapenerima' where idmasuk='$idm'");
            if($kurangistocknya&&$updatebrgmasuknya){
                header('location:masuk.php');
            }else {
                echo 'Gagal';
                header('location:masuk.php');
            }
    }
}


//menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idbarang'];
    $idm = $_POST['idmasuk'];
    $jumlahbarang =$_POST['jumlahbrg'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['saldo'];

    $selisih = $stok-$jumlahbarang;

    $update = mysqli_query($conn,"update stock set saldo='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    }else {
        header('location:masuk.php'); 
    }
}

//mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idbarang'];
    $idk = $_POST['idkeluar'];
    $jumlahbarang = $_POST['jumlahbarang'];
    $penerima =$_POST['penerima'];
    $nomorhal =$_POST['nomorhal'];
    $unit =$_POST['unit'];
    
    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['saldo'];

    $jumlahbrgskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $jumlahbrgnya = mysqli_fetch_array($jumlahbrgskrg);
    $jumlahbrgskrg = $jumlahbrgnya['jumlahbarang'];

    if($jumlahbarang>$jumlahbrgskrg){
        $selisih = $jumlahbarang-$jumlahbrgskrg;
        $kurangin = $stockskrg-$selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set saldo='$kurangin' where idbarang='$idb'");
        $updatebrgkeluar = mysqli_query($conn,"update keluar set jumlahbarang='$jumlahbarang', penerima='$penerima', nomorhal='$nomorhal', unit='$unit where idkeluar='$idk'");
            if($kurangistocknya&&$updatebrgkeluar){
                header('location:keluar.php');
            }else {
                echo 'Gagal';
                header('location:keluar.php');
            }
    } else {
        $selisih = $jumlahbrgskrg-$jumlahbarang;
        $kurangin = $stockskrg+$selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set saldo='$kurangin' where idbarang='$idb'");
        $updatebrgkeluar = mysqli_query($conn,"update keluar set jumlahbarang='$jumlahbarang', penerima='$penerima', nomorhal='$nomorhal', unit='$unit where idkeluar='$idk'");
            if($kurangistocknya&&$updatebrgkeluar){
                header('location:keluar.php');
            }else {
                echo 'Gagal';
                header('location:keluar.php');
            }
    }
}


//menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idbarang'];
    $idk = $_POST['idkeluar'];
    $jumlahbarang =$_POST['jumlahbrg'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['saldo'];

    $selisih = $stok+$jumlahbarang;

    $update = mysqli_query($conn,"update stock set saldo='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    }else {
        header('location:keluar.php'); 
    }
}
?>