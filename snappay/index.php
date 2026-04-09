<!DOCTYPE html>
<html>
<head>
    <title>Snap-to-Pay</title>
</head>
<body>
<h2>Snap Account Info</h2>
<form id="snapForm" enctype="multipart/form-data">
  <input type="file" accept="image/*" capture="camera" name="snap_image" required>
  <button type="submit">Snap & Verify</button>
</form>

<div id="verificationResult"></div>

<script src="js/snap.js"></script>
</body>
</html>
