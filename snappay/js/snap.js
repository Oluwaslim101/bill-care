

document.getElementById('snapForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);

    fetch('snap_upload.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            const color = data.name_match ? 'green' : 'orange';
            const nameStatus = data.name_match ? '✅ Name Verified' : '⚠ Name Mismatch';
            
            document.getElementById('verificationResult').innerHTML = `
                <p>Account: ${data.account_number}</p>
                <p>OCR Name: ${data.account_name}</p>
                <p>Bank Name: ${data.resolved_name}</p>
                <p style="color:${color}">${nameStatus}</p>
                <p>Verification Status: ${data.verification_result}</p>
                ${data.name_match ? `<button onclick="proceedPayment('${data.account_number}', '${data.account_name}')">Proceed</button>` : '<button onclick="retrySnap()">Retry Snap</button>'}
            `;
        } else {
            document.getElementById('verificationResult').innerHTML = `
                <p>${data.message}</p>
                <button onclick="retrySnap()">Retry Snap</button>
            `;
        }
    });
});

function proceedPayment(account_number, account_name){
    window.location.href = `https://swiftaffiliates.cloud/app/process_withdrawal.php?account_number=${account_number}&account_name=${account_name}`;
}

function retrySnap(){
    document.getElementById('verificationResult').innerHTML = '';
    document.querySelector('input[name="snap_image"]').value = '';
}
