
<?php
require 'koneksidatabase.php';

$id = $_GET['id'] ?? '';
$pesan = '';

// ==================================================
// DATA DEFAULT
// ==================================================

$data = [
    'Nama' => '',
    'RuangTerpilih' => '',
    'JamMulai' => '07:00',
    'JamSelesai' => '08:00'
];

// ==================================================
// VALIDASI ID
// ==================================================

if ($id === '' || !ctype_digit((string)$id)) {
    die("ID peminjaman tidak valid.");
}

// ==================================================
// PROSES HAPUS
// ==================================================


// ==================================================
// PROSES UPDATE
// ==================================================

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['hapus'])) {

    $id = $_POST['id'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $ruang = $_POST['Ruang'] ?? '';
    $waktu1 = $_POST['Waktu1'] ?? '';
    $waktu2 = $_POST['Waktu2'] ?? '';

    if (
        $id === '' ||
        !ctype_digit((string)$id) ||
        $nama === '' ||
        $ruang === '' ||
        $waktu1 === '' ||
        $waktu2 === ''
    ) {
        $pesan = "Semua data harus diisi!";
    } elseif ($waktu1 >= $waktu2) {
        $pesan = "Waktu selesai harus lebih besar dari waktu mulai!";
    } else {

        $sql = "UPDATE a
                SET Nama = ?,
                    RuangTerpilih = ?,
                    JamMulai = ?,
                    JamSelesai = ?
                WHERE Id = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Gagal menyiapkan update data.");
        }

        $stmt->bind_param(
            "ssssi",
            $nama,
            $ruang,
            $waktu1,
            $waktu2,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: pinjamruang.php");
            exit;
        } else {
            $pesan = "Gagal mengubah data.";
        }

        $stmt->close();
    }
}

// ==================================================
// AMBIL DATA LAMA BERDASARKAN ID
// ==================================================

$stmtData = $conn->prepare(
    "SELECT Nama, RuangTerpilih, JamMulai, JamSelesai
     FROM a
     WHERE Id = ?"
);

$stmtData->bind_param("i", $id);
$stmtData->execute();

$resultData = $stmtData->get_result();

if ($resultData->num_rows === 0) {
    $stmtData->close();
    die("Data peminjaman tidak ditemukan.");
}

$data = $resultData->fetch_assoc();
$stmtData->close();

// Pertahankan input yang dikirim jika validasi gagal
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['hapus'])) {
    $data['Nama'] = $_POST['nama'] ?? $data['Nama'];
    $data['RuangTerpilih'] = $_POST['Ruang'] ?? $data['RuangTerpilih'];
    $data['JamMulai'] = $_POST['Waktu1'] ?? $data['JamMulai'];
    $data['JamSelesai'] = $_POST['Waktu2'] ?? $data['JamSelesai'];
}

// ==================================================
// AMBIL DAFTAR RUANGAN
// ==================================================

$resultRuangan = $conn->query(
    "SELECT Ruang FROM b ORDER BY Ruang ASC"
);

if (!$resultRuangan) {
    die("Gagal mengambil daftar ruangan.");
}

// ==================================================
// DAFTAR WAKTU
// ==================================================

$daftarMulai = [
    '07:00', '08:00', '09:00',
    '10:00', '11:00', '12:00'
];

$daftarSelesai = [
    '08:00', '09:00', '10:00',
    '11:00', '12:00', '13:00'
];

// Pastikan waktu lama tetap tersedia jika di luar daftar
if (!in_array($data['JamMulai'], $daftarMulai, true)) {
    $daftarMulai[] = $data['JamMulai'];
    sort($daftarMulai);
}

if (!in_array($data['JamSelesai'], $daftarSelesai, true)) {
    $daftarSelesai[] = $data['JamSelesai'];
    sort($daftarSelesai);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">

    <title>Edit Peminjaman Ruangan</title>
</head>

<body>

<form id="myForm" method="POST" align="center">

    <input
        type="hidden"
        name="id"
        value="<?php echo htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'); ?>"
    >

    <h1>Edit Peminjaman</h1>

    <h3 style="color: darkslategray;">
        Silakan ubah data peminjaman ruangan di bawah ini.
    </h3>

    <p>
        <label for="nama">Nama acara:</label>
        <input
            type="text"
            id="nama"
            name="nama"
            placeholder="Nama acara"
            value="<?php echo htmlspecialchars($data['Nama'], ENT_QUOTES, 'UTF-8'); ?>"
            required
        >
    </p>

    <p>
        <label for="Ruang">Pilih ruangan:</label>

        <select id="Ruang" name="Ruang" required>
            <?php while ($row = $resultRuangan->fetch_assoc()): ?>
                <option
                    value="<?php echo htmlspecialchars($row['Ruang'], ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo ($row['Ruang'] === $data['RuangTerpilih']) ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($row['Ruang'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        <label for="Waktu1">Waktu:</label>

        <select id="Waktu1" name="Waktu1" required>
            <?php foreach ($daftarMulai as $waktu): ?>
                <option
                    value="<?php echo htmlspecialchars($waktu, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo ($waktu === $data['JamMulai']) ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($waktu); ?>
                </option>
            <?php endforeach; ?>
        </select>

        -

        <select id="Waktu2" name="Waktu2" required>
            <?php foreach ($daftarSelesai as $waktu): ?>
                <option
                    value="<?php echo htmlspecialchars($waktu, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo ($waktu === $data['JamSelesai']) ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($waktu); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <?php if ($pesan !== ''): ?>
        <p style="color: darkblue;">
            <?php echo htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <p>
        <button
            type="button"
            style="background-color: orange; color: black; border-color: white;"
            onclick="window.location.href='pinjamruang.php'"
        >
            Kembali
        </button>


        <button
            type="submit"
            style="background-color: green; color: yellow; border-color: white;"
        >
            Selesai
        </button>
    </p>

</form>

<script>
const dropdown1 = document.getElementById('Waktu1');
const dropdown2 = document.getElementById('Waktu2');

function aturWaktuSelesai() {
    const mulai = dropdown1.value;

    for (const option of dropdown2.options) {
        option.disabled = option.value <= mulai;
    }

    // Jangan mengubah pilihan lama jika masih valid.
    if (dropdown2.value <= mulai) {
        const pilihanValid = Array.from(dropdown2.options)
            .find(option => !option.disabled);

        if (pilihanValid) {
            dropdown2.value = pilihanValid.value;
        }
    }
}

dropdown1.addEventListener('change', aturWaktuSelesai);

// Terapkan aturan saat halaman pertama kali dibuka.
aturWaktuSelesai();
</script>

</body>
</html>








































































































































































































































































































