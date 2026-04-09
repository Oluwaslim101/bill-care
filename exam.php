<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>WAEC & JAMB Results Checker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4 text-center">Check WAEC or JAMB Result</h2>

  <form id="resultForm" method="POST" action="exam_verify.php">
    <div class="mb-3">
      <label for="examType" class="form-label">Select Exam Type</label>
      <select class="form-select" id="examType" name="exam_type" required onchange="toggleFields()">
        <option value="">Choose...</option>
        <option value="waec">WAEC</option>
        <option value="jamb">JAMB</option>
      </select>
    </div>

    <div id="waecFields" style="display:none;">
      <div class="mb-3">
        <label for="waec_exam_number" class="form-label">Exam Number</label>
        <input type="text" class="form-control" name="waec_exam_number" required>
      </div>
      <div class="mb-3">
        <label for="waec_exam_year" class="form-label">Exam Year</label>
        <input type="number" class="form-control" name="waec_exam_year" required>
      </div>
      <div class="mb-3">
        <label for="waec_pin" class="form-label">WAEC PIN</label>
        <input type="text" class="form-control" name="waec_pin" required>
      </div>
    </div>

    <div id="jambFields" style="display:none;">
      <div class="mb-3">
        <label for="jamb_reg_number" class="form-label">JAMB Registration Number</label>
        <input type="text" class="form-control" name="jamb_reg_number" required>
      </div>
      <div class="mb-3">
        <label for="jamb_year" class="form-label">Exam Year</label>
        <input type="number" class="form-control" name="jamb_year" required>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Check Result</button>
  </form>
</div>

<script>
function toggleFields() {
  const examType = document.getElementById('examType').value;
  document.getElementById('waecFields').style.display = examType === 'waec' ? 'block' : 'none';
  document.getElementById('jambFields').style.display = examType === 'jamb' ? 'block' : 'none';
}
</script>

</body>
</html>