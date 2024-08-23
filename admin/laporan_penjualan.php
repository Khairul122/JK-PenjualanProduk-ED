<?php
include 'header.php';
$date = date('Y-m-d'); // Format tanggal yang benar

if (isset($_POST['submit'])) {
    $date1 = mysqli_real_escape_string($conn, $_POST['date1']);
    $date2 = mysqli_real_escape_string($conn, $_POST['date2']);
}
?>
<style type="text/css">
    @media print {
        .print {
            display: none;
        }
    }
</style>
<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray; padding-bottom: 5px;"><b>Laporan Penjualan</b></h2>
    <div class="row print">
        <div class="col-md-9">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <table>
                    <tr>
                        <td><input type="date" name="date1" class="form-control" value="<?= $date; ?>"></td>
                        <td>&nbsp; - &nbsp;</td>
                        <td><input type="date" name="date2" class="form-control" value="<?= $date; ?>"></td>
                        <td> &nbsp;</td>
                        <td><input type="submit" name="submit" class="btn btn-primary" value="Tampilkan"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-3">
            <form action="exp_penjualan.php" method="POST">
                <table>
                    <tr>
                        <td><input type="hidden" name="date1" class="form-control" value="<?= isset($date1) ? $date1 : ''; ?>"></td>
                        <td><input type="hidden" name="date2" class="form-control" value="<?= isset($date2) ? $date2 : ''; ?>"></td>
                        <td><button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-save-file"></i> Export to Excel</button></td>
                        <td> &nbsp;</td>
                        <td><button type="button" class="btn btn-default" onclick="previewPDF()"><i class="glyphicon glyphicon-print"></i> Pratinjau PDF</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <br><br>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_POST['submit'])) {
            $result = mysqli_query($conn, "SELECT * FROM produksi WHERE terima = 1 AND tanggal BETWEEN '$date1' AND '$date2'");
            $no = 1;
            $total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_produk']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>{$row['qty']}</td>
                      </tr>";
                $total += $row['qty'];
                $no++;
            }
            echo "<tr>
                    <td colspan='4' class='text-right'><b>Total Jumlah Terjual = {$total}</b></td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<br><br><br><br><br>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<script>
function previewPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Lebar halaman PDF
    const pageWidth = doc.internal.pageSize.getWidth();

    // Menambahkan gambar sebelum teks "Laporan Penjualan"
    const img = new Image();
    img.src = '../image/home/logo-serambi.jpg';
    img.onload = function() {
        const imgWidth = 20; // Lebar gambar
        const imgHeight = 20; // Tinggi gambar
        const marginLeft = 10; // Jarak dari sisi kiri
        const marginTop = 10; // Jarak dari sisi atas

        doc.addImage(img, 'JPEG', marginLeft, marginTop, imgWidth, imgHeight);

        // Menempatkan teks "Laporan Penjualan" di tengah halaman
        doc.setFontSize(16);
        doc.text("Laporan Penjualan", pageWidth / 2, marginTop + imgHeight / 2 + 5, { align: "center" });

        // Menambahkan garis di bawah teks "Laporan Penjualan"
        doc.line(marginLeft, marginTop + imgHeight + 10, pageWidth - marginLeft, marginTop + imgHeight + 10);

        // Mengatur posisi awal untuk tabel
        let startY = marginTop + imgHeight + 20;

        // Membuat header tabel
        const headers = [["No", "Nama Produk", "Tanggal", "Qty"]];

        // Mengambil data dari tabel di HTML
        const rows = [];
        document.querySelectorAll("table.table-striped tbody tr").forEach((row, index) => {
            const cells = row.cells;
            if (cells.length === 4) {
                rows.push([
                    cells[0].textContent.trim(),
                    cells[1].textContent.trim(),
                    cells[2].textContent.trim(),
                    cells[3].textContent.trim()
                ]);
            }
        });

        // Menggunakan autoTable untuk membuat tabel dengan garis tebal
        doc.autoTable({
            head: headers,
            body: rows,
            startY: startY,
            theme: 'grid',
            headStyles: {
                fontStyle: 'bold',
                fillColor: [255, 255, 255], // Warna latar belakang putih
                textColor: [0, 0, 0], // Warna teks hitam
                lineWidth: 0.5, // Ketebalan garis header
                lineColor: [0, 0, 0] // Warna garis header hitam
            },
            styles: {
                lineWidth: 0.5, // Ketebalan garis isi tabel
                lineColor: [0, 0, 0] // Warna garis isi tabel hitam
            },
            margin: { top: 10, bottom: 10 }
        });

        // Membuka PDF di tab/jendela baru tanpa mengunduh
        doc.output('dataurlnewwindow');
    };
}
</script>


<?php
include 'footer.php';
?>
