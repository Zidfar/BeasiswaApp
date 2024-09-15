<?php
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM pendaftaran WHERE id = $id";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<div class='alert alert-success'>Data berhasil dihapus</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting record: " . $conn->error . "</div>";
    }
    header("Location: dashboard.php");
    exit();
}
