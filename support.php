<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>DtheHub</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
       <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <style>
        
        /* Fixed Bottom Navigation */
.nav {
position: fixed;
bottom: 0;
left: 50%;
transform: translateX(-50%);
width: 100%;
max-width: 410px;
display: flex;
justify-content: space-around;
background: white;
padding: 12px 7px;
border-radius: 8px 8px 0 0;
box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1);
z-index: 1000;
}

.nav a {
text-decoration: none;
color: gray;
font-size: 12px;
text-align: center;
display: flex;
flex-direction: column;
align-items: center;
gap: 3px;
flex: 1;
transition: color 0.3s ease;
}

.nav a i {
font-size: 20px;
color: gray;
transition: color 0.3s ease;
}

.nav a span {
font-size: 12px;
font-weight: 500;
}

.nav a.active i,
.nav a.active span {
color: green;
font-weight: bold;
}

    </style>
</head>


<body>

    <!-- loader -->
    <div id="loader">
        <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Contact
        </div>
        <div class="right">
            <a href="#" class="headerButton">
                <ion-icon name="call-outline"></ion-icon>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section mt-0">
            <div class="card">
                <div class="card-body">
                    <div class="p-1">
                        <div class="text-center">
                            <h2 class="text-primary">Get in Touch</h2>
                            <p>Fill the form to contact us</p>
                        </div>
                        <form id="contactForm">
                            <div class="form-group basic animated">
                                <div class="input-wrapper">
                                    <label class="label" for="name2">Your name</label>
                                    <input type="text" class="form-control" id="name2" placeholder="Your name">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>

                            <div class="form-group basic animated">
                                <div class="input-wrapper">
                                    <label class="label" for="email2">E-mail</label>
                                    <input type="text" class="form-control" id="email2" placeholder="E-mail">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>

                            <div class="form-group basic animated">
                                <div class="input-wrapper">
                                    <label class="label" for="textarea2">Message</label>
                                    <textarea id="textarea2" rows="4" class="form-control"
                                        placeholder="Message"></textarea>
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

<div class="section mt-1">
    <div class="card">
        <div class="card-body">
            <div class="p-1">
                <div class="text-center">
                    <h2 class="text-primary">Visit Us</h2>
                    <p class="card-text mb-2">
                        #1 New Haven Street<br>Yenagoa BYS Nigeria.
                        .
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-2">
                        <a href="https://wa.me/2348148622359" target="_blank" class="btn btn-success btn-icon rounded-circle" title="Chat on WhatsApp">
                            <ion-icon name="logo-whatsapp"></ion-icon>
                        </a>
                        <a href="tel:+2348148622359" class="btn btn-outline-primary btn-icon rounded-circle" title="Call Us">
                            <ion-icon name="call-outline"></ion-icon>
                        </a>
                        <a href="sms:+2348148622359" class="btn btn-outline-success btn-icon rounded-circle" title="Send SMS">
                            <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="section mt-1 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="p-1">
                        <div class="text-center">
                            <h2 class="text-primary mb-2">Social Profiles</h2>

                            <a href="https://facebook.com/dthehub" class="btn btn-facebook btn-icon me-05">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>

                            <a href="https://instagram.com/dthehub" class="btn btn-twitter btn-icon me-05">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>

                            <a href="#" class="btn btn-linkedin btn-icon me-05">
                                <ion-icon name="logo-linkedin"></ion-icon>
                            </a>

                           <a href="https://instagram.com/dthehub" class="btn btn-twitter btn-icon me-05">
                                <ion-icon name="logo-instagram"></ion-icon>
                            </a>

                          

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- * App Capsule -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name2').value.trim();
    const email = document.getElementById('email2').value.trim();
    const message = document.getElementById('textarea2').value.trim();

    if (!name || !email || !message) {
        Swal.fire('Oops', 'Please fill in all fields.', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('message', message);

    fetch('contact_handler.php', {
        method: 'POST',
        body: formData
    }).then(res => res.text())
      .then(res => {
          Swal.fire('Sent!', 'Your message has been received.', 'success');
          document.getElementById('contactForm').reset();
      })
      .catch(() => {
          Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
      });
});
</script>


<?php include 'footer.php'; ?>
</body>
</html>
