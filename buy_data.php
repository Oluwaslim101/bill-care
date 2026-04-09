<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Data Purchase Modal</title>
<style>
  /* Simple ActionSheet modal styles */
  #actionSheet {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0; right: 0;
    background: #fff;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
    max-height: 60vh;
    overflow-y: auto;
    padding: 20px;
    z-index: 1000;
  }
  #overlay {
    display:none;
    position: fixed; 
    top:0; left:0; right:0; bottom:0;
    background: rgba(0,0,0,0.4);
    z-index: 999;
  }
  button { margin-top: 10px; }
</style>
</head>
<body>

<button id="openModalBtn">Buy Data</button>

<div id="overlay"></div>

<div id="actionSheet">
  <h2>Purchase Data Plan</h2>

  <label for="networkSelect">Select Network:</label>
  <select id="networkSelect">
    <option value="">--Select--</option>
    <option value="mtn">MTN</option>
    <option value="glo">Glo</option>
    <option value="9mobile">9mobile</option>
    <option value="airtel">Airtel</option>
  </select>

  <br /><br />

  <label for="planSelect">Select Data Plan:</label>
  <select id="planSelect" disabled>
    <option value="">--Select Network First--</option>
  </select>

  <br /><br />

  <label for="mobileInput">Mobile Number:</label>
  <input type="tel" id="mobileInput" placeholder="Enter mobile number" />

  <br /><br />

  <button id="purchaseBtn" disabled>Purchase</button>
  <button id="closeModalBtn">Cancel</button>

  <div id="statusMsg" style="margin-top:15px; color:green;"></div>
</div>

<script>
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.getElementById('closeModalBtn');
const overlay = document.getElementById('overlay');
const actionSheet = document.getElementById('actionSheet');
const networkSelect = document.getElementById('networkSelect');
const planSelect = document.getElementById('planSelect');
const mobileInput = document.getElementById('mobileInput');
const purchaseBtn = document.getElementById('purchaseBtn');
const statusMsg = document.getElementById('statusMsg');

// Show modal
openBtn.onclick = () => {
  actionSheet.style.display = 'block';
  overlay.style.display = 'block';
  resetModal();
};

// Close modal
closeBtn.onclick = () => {
  actionSheet.style.display = 'none';
  overlay.style.display = 'none';
  statusMsg.textContent = '';
};

overlay.onclick = closeBtn.onclick;

// Reset modal inputs
function resetModal() {
  networkSelect.value = '';
  planSelect.innerHTML = '<option value="">--Select Network First--</option>';
  planSelect.disabled = true;
  mobileInput.value = '';
  purchaseBtn.disabled = true;
  statusMsg.textContent = '';
}

// Fetch plans when network changes
networkSelect.onchange = async () => {
  const network = networkSelect.value;
  planSelect.disabled = true;
  purchaseBtn.disabled = true;
  planSelect.innerHTML = '<option>Loading...</option>';
  statusMsg.textContent = '';

  if (!network) {
    planSelect.innerHTML = '<option value="">--Select Network First--</option>';
    return;
  }

  try {
    const res = await fetch(`process_data.php?action=getPlans&network=${network}`);
    const data = await res.json();

    if (data.error) {
      planSelect.innerHTML = `<option>${data.error}</option>`;
      return;
    }

    // Populate plan select
    planSelect.innerHTML = '<option value="">--Select Data Plan--</option>';
    data.plans.forEach(plan => {
      // plan.code is the data plan code to send to Nellobyte API
      planSelect.innerHTML += `<option value="${plan.code}">${plan.description} - ₦${plan.price}</option>`;
    });
    planSelect.disabled = false;

  } catch (e) {
    planSelect.innerHTML = '<option>Error fetching plans</option>';
  }
};

// Enable purchase button when plan and number valid
function checkPurchaseReady() {
  purchaseBtn.disabled = !(planSelect.value && mobileInput.value.trim().length >= 10);
}

planSelect.onchange = checkPurchaseReady;
mobileInput.oninput = checkPurchaseReady;

// Purchase action
purchaseBtn.onclick = async () => {
  const network = networkSelect.value;
  const dataPlanCode = planSelect.value;
  const mobileNumber = mobileInput.value.trim();

  if (!network || !dataPlanCode || !mobileNumber) {
    statusMsg.style.color = 'red';
    statusMsg.textContent = 'Please complete all fields';
    return;
  }

  statusMsg.style.color = 'black';
  statusMsg.textContent = 'Processing purchase... Please wait.';

  try {
    const res = await fetch('process_data.php?action=purchaseData', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({network, mobileNumber, dataPlanCode})
    });
    const data = await res.json();

    if (data.error) {
      statusMsg.style.color = 'red';
      statusMsg.textContent = 'Error: ' + data.error;
      return;
    }

    if (data.status === "success") {
      statusMsg.style.color = 'green';
      statusMsg.innerHTML = 'Purchase successful!<br />' + data.receipt;
    } else {
      statusMsg.style.color = 'red';
      statusMsg.textContent = 'Purchase failed: ' + (data.message || 'Unknown error');
    }

  } catch (e) {
    statusMsg.style.color = 'red';
    statusMsg.textContent = 'Network or server error';
  }
};

</script>

</body>
</html>
