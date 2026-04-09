// Get the dynamic user ID from PHP session or any other source
let userId = "<?php echo $_SESSION['user_id']; ?>"; // This line retrieves the user ID from PHP session or you can fetch from localStorage if already set.

if (!userId) {
  console.log('User ID is missing!');
} else {
  // Initialize Firebase
  const firebaseConfig = {
    apiKey: "AIzaSyAZVqr_NfS3lWLxazKvtpsfJEinKBEU3Xc",
    authDomain: "swift-contract.firebaseapp.com",
    projectId: "swift-contract",
    storageBucket: "swift-contract.appspot.com",
    messagingSenderId: "360309982598",
    appId: "1:360309982598:web:ab342b393ff04ca9685807",
    measurementId: "YOUR_MEASUREMENT_ID"
  };

  const app = firebase.initializeApp(firebaseConfig);
  const messaging = firebase.messaging();

  // Get the FCM Token
  messaging.getToken({ vapidKey: 'BOLInBsxaGDiVUkZca2lbeFKf_4nlhl6fOxALXWmD0POU67r44Rvh6XLQ2NfTV27XThFHhelI5u02-yYxGCRua8' })
    .then((currentToken) => {
      if (currentToken) {
        // Send the token and user_id dynamically to the server
        $.ajax({
          url: 'save_token.php',  // The PHP script that will save the token
          type: 'POST',
          data: { 
            fcm_token: currentToken,  // Send the token as data
            user_id: userId           // Pass the dynamically fetched user ID
          },
          success: function(response) {
            console.log('FCM Token saved successfully!');
          },
          error: function(err) {
            console.log('Error saving FCM token:', err);
          }
        });
      } else {
        console.log('No FCM Token available. Request permission to generate one.');
      }
    })
    .catch((err) => {
      console.log('Error getting FCM token:', err);
    });
}
