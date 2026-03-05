<?php
session_start();
include "include/koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include "include/header.php"; ?>
<?php include "include/sidebar.php"; ?>

<div class="col-md-10 p-4">
    <h2>Data Client</h2>
    <hr>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Nama</th> 
            <th>Email</th>
            <th>No HP</th>
            <th>Total Booking</th>
        </tr>

        <?php
        $no = 1;
                $query = mysqli_query($conn, "
                    SELECT nama_client, email, no_hp, COUNT(id) as total_booking
                    FROM booking
                    GROUP BY nama_client, email, no_hp
                    ORDER BY total_booking DESC");

        while ($row = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_client']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['no_hp']; ?></td>
                        <td><?= $row['total_booking']; ?></td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>

        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>