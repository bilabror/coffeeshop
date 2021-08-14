<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h1>SCAN BARCODE</h1>
  <div id="qr-reader" style="width: 600px;height:400px;"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
  <script>
    $(document).ready(function() {
      let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", {
          fps: 10, qrbox: 250
        });
      html5QrcodeScanner.render(onScanSuccess);



    })

    function onScanSuccess(decodedText, decodedResult) {
      window.location.href = `<?=site_url('order-vqrcode/') ?>${decodedText}`;
    }
  </script>
</body>
</html>