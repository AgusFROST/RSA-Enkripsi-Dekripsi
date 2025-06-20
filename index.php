<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="asset/bootstrap.min.css">

    <title>RSA Enkripsi & Dekripsi</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">RSA Enkripsi & Dekripsi</h2>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="message" class="form-label">Pesan</label>
                <input type="text" class="form-control" id="message" name="message" required>
            </div>
            <div class="mb-3">
                <label for="public_key" class="form-label">Public Key (e = Eksponen Publik, n = Modulus)</label>
                <input type="text" class="form-control" id="public_key" name="public_key" value="65537, 3233" required>
            </div>
            <div class="mb-3">
                <label for="private_key" class="form-label">Private Key (d = Eksponen Privat, n = Modulus)</label>
                <input type="text" class="form-control" id="private_key" name="private_key" value="2753,3233" required>
            </div>
            <div class="form-group mb-3">
                <select name="action" class="form-select" required>
                    <option value="encrypt">Encrypt</option>
                    <option value="decrypt">Decrypt</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>


        <!--             
            Jika p = 61 dan q = 53

            Hitung modulus: 
            n = p * q = 61 * 53 = 3233

            Hitung totien Euler :
            φ(n) = (p-1) * (q-1) = 60 * 52 = 3120

            Pilih eksponen publik e yang relatif prima dengan 3120, misalnya e = 65537 (nilai umum digunakan).

            Hitung eksponen privat d dengan syarat d * e ≡ 1 (mod φ(n)), dan hasilnya d = 2753.

            -->

        <?php
        // Fungsi untuk enkripsi dengan RSA
        function encryptRSA($message, $e, $n)
        {
            $cipher = [];
            $message = str_split($message);
            foreach ($message as $char) {
                $m = ord($char); // Konversi karakter ke ASCII
                $c = bcpowmod($m, $e, $n); // Enkripsi: c = m^e mod n
                array_push($cipher, $c);
            }
            return implode(' ', $cipher); // Menggabungkan menjadi string
        }

        // Fungsi untuk dekripsi dengan RSA
        function decryptRSA($cipherText, $d, $n)
        {
            $cipherArray = explode(' ', $cipherText); // Pisahkan cipher menjadi array
            $message = '';
            foreach ($cipherArray as $c) {
                $m = bcpowmod($c, $d, $n); // Dekripsi: m = c^d mod n
                $message .= chr($m); // Konversi ASCII ke karakter
            }
            return $message;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $message = trim($_POST['message']);
            $action = $_POST['action'];

            // Mengambil input kunci publik dan privat
            list($e, $nPublic) = array_map('intval', explode(',', $_POST['public_key']));
            list($d, $nPrivate) = array_map('intval', explode(',', $_POST['private_key']));

            // Validasi kunci publik dan privat
            if ($nPublic != $nPrivate) {
                echo '<div class="alert alert-danger mt-3">Error: Kunci Publik dan Privat harus memiliki modulus (n) yang sama.</div>';
            } else {
                if ($action == 'encrypt') {
                    $cipherText = encryptRSA($message, $e, $nPublic);
                    echo '<div class="alert alert-success mt-3">Pesan
                    Enkripsi : ' . $cipherText . '</div>';
                } elseif ($action == 'decrypt') {
                    $decryptedMessage = decryptRSA($message, $d, $nPrivate);
                    echo '<div class="alert alert-success mt-3">Pesan Dekripsi: ' . $decryptedMessage . '</div>';
                }
            }
        }
        ?>
    </div>

    <script src="asset/bootstrap.bundle.min.js"></script>
</body>

</html>