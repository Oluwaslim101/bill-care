// firebase-messaging-sw.js
importScripts("https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/10.11.0/firebase-messaging.js");

firebase.initializeApp({
apiKey: "AIzaSyAZVqr_NfS3lWLxazKvtpsfJEinKBEU3Xc",
  authDomain: "swift-contract.firebaseapp.com",
  projectId: "swift-contract",
  storageBucket: "swift-contract.firebasestorage.app",
  messagingSenderId: "360309982598",
  appId: "1:360309982598:web:ab342b393ff04ca9685807",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  const { title, body } = payload.notification;

  const notificationOptions = {
    body: body,
    icon: '/icon.png'
  };

  self.registration.showNotification(title, notificationOptions);
});
