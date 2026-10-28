<?php
require 'koneksidatabase.php';

// Proses data ketika tombol Selesai diklik
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $ruang = $_POST['Ruang'] ?? '';
    $waktu1 = $_POST['Waktu1'] ?? '';
    $waktu2 = $_POST['Waktu2'] ?? '';

    // Validasi input
    if ($nama === '' || $ruang === '' || $waktu1 === '' || $waktu2 === '') {
        echo "<script>alert('Semua data harus diisi!');</script>";
    } elseif ($waktu1 >= $waktu2) {
        echo "<script>alert('Waktu selesai harus lebih besar dari waktu mulai!');</script>";
    } else {
        // Simpan data dengan prepared statement
        $stmt = $conn->prepare(
            "INSERT INTO a (Nama, RuangTerpilih, JamMulai, JamSelesai)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $nama, $ruang, $waktu1, $waktu2);

        if ($stmt->execute()) {
            // Data berhasil disimpan, langsung pindah halaman
            header("Location: pinjamruang.php");
            exit;
        } else {
            echo "Gagal menyimpan data: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    }
}

// Ambil daftar ruangan dari database
// Sesuaikan nama tabel dan kolom dengan database kamu
$result = $conn->query("SELECT Ruang FROM b");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Daftar</title>
</head>
<body>

    <form id="myForm" method="POST" action="" align="center">
        <h1 align="center">Daftar</h1>

        <h3 align="center" style="color: darkslategray;">
            Jika anda ingin meminjam ruangan, silakan lengkapi data pada form di bawah.
        </h3>

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama"
               placeholder="Nama" required>
        <p></p>

        <label for="Ruang">Pilih ruangan:</label>
        <select id="Ruang" name="Ruang" required>
            <?php
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $ruang = htmlspecialchars($row['Ruang'], ENT_QUOTES, 'UTF-8');
                    echo "<option value=\"$ruang\">$ruang</option>";
                }
            }
            ?>
        </select>
        <p></p>

        <label for="Waktu1">Pilih waktu:</label>

        <select id="Waktu1" name="Waktu1" required>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
        </select>

        <label> - </label>

        <select id="Waktu2" name="Waktu2" required>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
        </select>

        <p></p>

        <button
            style="background-color: red; color: yellow; border-color: white;"
            type="button"
            onclick="window.location.href='pinjamruang.php'">
            Kembali
        </button>

        <button
            style="background-color: green; color: yellow; border-color: white;"
            type="submit">
            Selesai
        </button>

    </form>

    <script>
        const waktuMulai = document.getElementById('Waktu1');
        const waktuSelesai = document.getElementById('Waktu2');

        function perbaruiWaktuSelesai() {
            const mulai = waktuMulai.value;

            for (const option of waktuSelesai.options) {
                option.disabled =
                    option.value !== '13:00' && option.value <= mulai;
            }

            if (waktuSelesai.value &&
                waktuSelesai.value <= mulai) {
                waktuSelesai.value = '13:00';
            }
        }

        waktuMulai.addEventListener('change', perbaruiWaktuSelesai);

        perbaruiWaktuSelesai();
    </script>


</body>
</html>

































































































































































































































































































