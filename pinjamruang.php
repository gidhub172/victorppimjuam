<?php
require 'koneksidatabase.php';

// ==========================================
// PROSES HAPUS DATA
// ==========================================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['hapus'])) {

    $idHapus = $_POST['id'] ?? '';

    if ($idHapus === '' || !ctype_digit((string)$idHapus)) {
        die("ID peminjaman tidak valid.");
    }

    $stmtHapus = $conn->prepare("DELETE FROM a WHERE Id = ?");

    if (!$stmtHapus) {
        die("Gagal menyiapkan query: " . $conn->error);
    }

    $stmtHapus->bind_param("i", $idHapus);

    if ($stmtHapus->execute()) {
        $jumlahTerhapus = $stmtHapus->affected_rows;
        $stmtHapus->close();

        if ($jumlahTerhapus > 0) {
            header("Location: pinjamruang.php");
            exit;
        } else {
            die("Data tidak ditemukan.");
        }
    } else {
        $error = $stmtHapus->error;
        $stmtHapus->close();
        die("Gagal menghapus data: " . $error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">

    <title>Pinjam Ruang</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <h1 align="center"
        style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
        PINJAM RUANG
    </h1>

    <!-- ==========================================
         TABEL PEMINJAMAN
    =========================================== -->

    <table class="daftar" id="myTable">

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Ruang</th>
            <th>Jam mulai</th>
            <th>Jam selesai</th>
            <th></th>
                    <th>
            <button class="button1"
                type="button"
                onclick="window.location.href='form.php'">
                Daftar
            </button>
        </th>
        </tr>
<p></p>
        <?php
        $sql = "SELECT Id, Nama, RuangTerpilih, JamMulai, JamSelesai
                FROM a
                ORDER BY Id DESC";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            $no = 0;

            while ($row = $result->fetch_assoc()):
                $no++;
        ?>

        <tr>
            <td>
                <?php echo $no; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Nama']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['RuangTerpilih']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['JamMulai']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['JamSelesai']); ?>
            </td>

            <!-- TOMBOL EDIT -->
            <td align="center">
                <button
                    class="button1"
                    type="button"
                    onclick="window.location.href='edit.php?id=<?php echo urlencode((string)$row['Id']); ?>'">
                    Edit
                </button>
            </td>

            <!-- TOMBOL HAPUS -->
            <td align="center">
                <form method="POST"
                      action="pinjamruang.php"
                      class="form-hapus">

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo htmlspecialchars((string)$row['Id']); ?>">

                    <button
                        class="button1"
                        type="submit"
                        name="hapus"
                        value="1">
                        Hapus
                    </button>

                </form>
            </td>
        </tr>

        <?php
            endwhile;
        else:
        ?>

        <tr>
            <td colspan="7" align="center">
                Kosong
            </td>
        </tr>

        <?php endif; ?>

    </table>

    <br>

    <!-- ==========================================
         TABEL DAFTAR RUANGAN
    =========================================== -->

    <table class="daftar">

        <tr>
            <th>Ruang</th>
            <th>Deskripsi</th>
        </tr>

        <?php
        $sqlRuang = "SELECT Ruang, Deskripsi FROM b";
        $resultRuang = $conn->query($sqlRuang);

        if ($resultRuang):
            while ($row = $resultRuang->fetch_assoc()):
        ?>

        <tr>
            <td>
                <?php echo htmlspecialchars($row['Ruang']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Deskripsi']); ?>
            </td>
        </tr>

        <?php
            endwhile;
        endif;
        ?>

    </table>

    <!-- ==========================================
         KONFIRMASI HAPUS SWEETALERT2
    =========================================== -->

    <script>
    document.querySelectorAll('.form-hapus').forEach(function(form) {

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Yakin mau dihapus?',
                text: 'Data peminjaman ini akan dihapus permanen!',
                icon: 'warning',

                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',

                reverseButtons: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#30d664'
            }).then(function(result) {

                if (result.isConfirmed) {
                    // Pastikan parameter hapus ikut dikirim.
                    const inputHapus = document.createElement('input');
                    inputHapus.type = 'hidden';
                    inputHapus.name = 'hapus';
                    inputHapus.value = '1';

                    form.appendChild(inputHapus);

                    // Kirim form tanpa memicu konfirmasi ulang.
                    HTMLFormElement.prototype.submit.call(form);
                }

            });
        });

    });
    </script>

</body>
</html>
