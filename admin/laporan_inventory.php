<?php 
include 'header.php';
$date = date('Y-m-d');

if(isset($_POST['submit'])){
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
}
?>
<style type="text/css">
    @media print{
        .print{
            display: none;
        }
    }
</style>
<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray; padding-bottom: 5px;"><b>Laporan Inventory</b></h2>
    <div class="row print">
        <div class="col-md-9">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <table>
                    <tr>
                        <td><input type="date" name="date1" class="form-control" value="<?= $date; ?>"></td>
                        <td>&nbsp; - &nbsp;</td>
                        <td><input type="date" name="date2" class="form-control" value="<?= $date; ?>"></td>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="submit" class="btn btn-primary" value="Tampilkan"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-3">
            <form action="exp_pembatalan.php" method="POST">
                <table>
                    <tr>
                        <td><input type="hidden" name="date1" class="form-control" value="<?= $date1; ?>"></td>
                        <td><input type="hidden" name="date2" class="form-control" value="<?= $date2; ?>"></td>
                        <td><button type="button" class="btn btn-default" onclick="previewPDF()"><i class="glyphicon glyphicon-print"></i> Pratinjau PDF</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <br>
    <br>
    <table class="table table-striped">
        <tr>
            <th>No</th>
            <th>Nama Bahan Baku</th>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Tanggal</th>
        </tr>
        <?php 
        if(isset($_POST['submit'])){
            $result = mysqli_query($conn, "SELECT * FROM inventory WHERE tanggal between '$date1' and '$date2'");
            $no = 1;
            $total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['qty']; ?></td>
                    <td><?= $row['satuan']; ?></td>
                    <td><?= $row['tanggal']; ?></td>
                </tr>
        <?php 
                $total += $row['qty'];
                $no++;
            }
        ?>
            <tr>
                <td colspan="5" class="text-right"><b>Jumlah Semua Bahan Baku = <?= $total; ?></b></td>
            </tr>
        <?php } ?>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script>
function previewPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const pageWidth = doc.internal.pageSize.getWidth();
    const img = new Image();
    img.src = '../image/home/logo-serambi.jpg'; // Alamat gambar yang sesuai
    img.onload = function() {
        const imgWidth = 20;
        const imgHeight = 20;
        const marginLeft = 10;
        const marginTop = 10;

        doc.addImage(img, 'JPEG', marginLeft, marginTop, imgWidth, imgHeight);

        // Teks "Laporan Inventory" di tengah halaman
        doc.setFontSize(16);
        doc.text("Laporan Inventory", pageWidth / 2, marginTop + imgHeight / 2 + 5, { align: "center" });

        // Garis bawah teks "Laporan Inventory"
        doc.line(marginLeft, marginTop + imgHeight + 10, pageWidth - marginLeft, marginTop + imgHeight + 10);

        // Posisi awal tabel
        let startY = marginTop + imgHeight + 20;

        // Mengambil data dari tabel di HTML
        const rows = [];
        document.querySelectorAll("table.table-striped tr").forEach((row, index) => {
            const cells = row.cells;
            if (index > 0 && cells.length === 5) {
                rows.push([
                    cells[0].textContent.trim(),
                    cells[1].textContent.trim(),
                    cells[2].textContent.trim(),
                    cells[3].textContent.trim(),
                    cells[4].textContent.trim()
                ]);
            }
        });

        // Menggunakan autoTable untuk membuat tabel dengan garis tebal
        doc.autoTable({
            head: [['No', 'Nama Bahan Baku', 'Qty', 'Satuan', 'Tanggal']],
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

        doc.output('dataurlnewwindow');
    };
}
</script>

<?php 
include 'footer.php';
?>
