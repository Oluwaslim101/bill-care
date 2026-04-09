<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit();
}

$full_name  = $user['full_name'];
$balance    = $user['balance'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';

$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt  = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

$giftcards = $sql->query("SELECT id, name, image_url FROM giftcards WHERE status = 'active'")
                 ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Gift Card Purchase & Exchange</title>

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#13ec80",
                "primary-dark": "#0eb560",
                "background-light": "#f6f8f7",
                "background-dark": "#102219",
                "surface-light": "#ffffff",
                "surface-dark": "#1a2e24",
            },
            fontFamily: {
                "display": ["Manrope", "sans-serif"]
            }
        },
    },
}
</script>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display transition-colors">

<div class="max-w-md mx-auto min-h-screen flex flex-col shadow-xl">

    <!-- HEADER -->
    <div class="flex items-center p-4 justify-between bg-background-light dark:bg-background-dark sticky top-0 z-20">
        <div onclick="history.back()" class="cursor-pointer">
            <span class="material-symbols-outlined">arrow_back</span>
        </div>
        <h2 class="text-lg font-bold flex-1 text-center">Gift Cards</h2>
        <div>
            <button class="flex items-center rounded-full h-9 bg-surface-light dark:bg-surface-dark border px-3">
                <span class="material-symbols-outlined text-primary mr-2">account_balance_wallet</span>
                <span class="font-bold">₦<?= number_format($balance, 2) ?></span>
            </button>
        </div>
    </div>

    <!-- MAIN SCROLL AREA -->
    <div class="flex-1 overflow-y-auto scrollbar-hide px-4 pb-32">

        <!-- TAB -->
        <div class="py-2">
            <div class="flex h-12 w-full items-center justify-center rounded-xl bg-slate-200 dark:bg-surface-dark p-1">
                <label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 bg-primary shadow-sm text-background-dark">
                    <span class="truncate font-bold text-sm">Trade Cards</span>
                </label>
            </div>
        </div>

        <!-- PROMO BANNER -->
        <div class="py-4">
            <div class="w-full rounded-2xl bg-gradient-to-r from-background-dark to-surface-dark p-5 relative overflow-hidden">
                <span class="inline-block px-2 py-1 rounded bg-primary/20 text-primary text-xs font-bold mb-2">
                    LIMITED OFFER
                </span>
                <h3 class="text-white text-xl font-bold mb-1">Get 5% Cashback</h3>
                <p class="text-slate-300 text-sm">On all gaming gift cards this weekend.</p>
            </div>
        </div>
        
        <!-- SEARCH BAR -->
        <div class="px-4 py-3 bg-background-light dark:bg-background-dark">
            <div class="flex bg-surface-light dark:bg-surface-dark rounded-xl border">
                <div class="flex items-center pl-3">
                    <span class="material-symbols-outlined text-slate-400">search</span>
                </div>
                <input 
                    id="giftcardSearch"
                    class="w-full bg-transparent px-3 py-2 outline-none"
                    placeholder="Search brands like Amazon, Apple..."
                    type="text"
                />
            </div>
        </div>

        <!-- ALL BRANDS -->
        <h2 class="font-bold text-lg mb-3">All Brands</h2>

        <div id="giftcardList" class="flex flex-col gap-3">
            <?php foreach ($giftcards as $card): ?>
            <div class="giftcard-item flex items-center p-3 rounded-xl bg-surface-light dark:bg-surface-dark border shadow-sm cursor-pointer"
                 data-id="<?= $card['id'] ?>"
                 data-name="<?= htmlspecialchars($card['name']) ?>"
                 data-image="<?= htmlspecialchars($card['image_url']) ?>">
                <div class="size-12 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                    <?php if (!empty($card['image_url'])): ?>
                        <img src="<?= htmlspecialchars($card['image_url']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="text-white font-bold"><?= strtoupper(substr($card['name'], 0, 2)) ?></span>
                    <?php endif; ?>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-bold"><?= htmlspecialchars($card['name']) ?></p>
                    <p class="text-xs text-slate-500">Gift Card</p>
                </div>
                <button class="bg-primary text-background-dark px-3 py-1 rounded-lg text-sm trade-btn">Trade</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <button id="toggleGiftcards" class="bg-primary text-background-dark px-4 py-2 rounded-lg text-sm font-bold">
                Show All
            </button>
        </div>

        <!-- TRADE PROMPT -->
        <div class="px-4 py-6">
            <div class="rounded-xl border border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-white/5 p-4 flex items-center gap-4">
                <div class="size-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-300 shrink-0">
                    <span class="material-symbols-outlined">currency_exchange</span>
                </div>
                <div class="flex-1">
                    <p class="text-slate-900 dark:text-white font-bold text-sm">Have unwanted cards?</p>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">Trade them for cash instantly.</p>
                </div>
                <button class="text-primary font-bold text-sm">Trade Now</button>
            </div>
        </div>

    </div>

    <!-- BOTTOM NAV -->
    <div class="fixed bottom-0 w-full max-w-md bg-surface-light dark:bg-surface-dark border-t border-slate-200 dark:border-white/5 px-6 py-3 flex justify-between items-center z-30">
        <button class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">home</span>
            <span class="text-[10px] font-medium">Home</span>
        </button>
        <button class="flex flex-col items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[24px] font-variation-settings-fill">card_giftcard</span>
            <span class="text-[10px] font-medium">Cards</span>
        </button>
        <button class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
            <div class="size-12 -mt-8 rounded-full bg-primary flex items-center justify-center shadow-lg border-4 border-background-light dark:border-background-dark">
                <span class="material-symbols-outlined text-background-dark text-[28px]">qr_code_scanner</span>
            </div>
        </button>
        <button class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">bar_chart</span>
            <span class="text-[10px] font-medium">Activity</span>
        </button>
        <button class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">person</span>
            <span class="text-[10px] font-medium">Profile</span>
        </button>
    </div>

</div>

<!-- BOTTOM MODALS -->

<!-- Trade Modal -->
<div id="giftcardTradeModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex justify-center items-end">
  <div class="w-full max-w-md bg-surface-light dark:bg-surface-dark rounded-t-3xl p-5 pb-8 shadow-xl transform translate-y-full transition-transform duration-300">
    <div class="flex items-center justify-between mb-4">
      <h3 id="modalGiftcardTitle" class="text-lg font-bold">Trade Gift Card</h3>
      <button id="closeTradeModal" class="text-slate-500 dark:text-slate-400">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <form id="giftcardForm" class="flex flex-col gap-4" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <input type="hidden" name="giftcard_id" id="modalGiftcardId" required>
      <div class="text-center">
        <img id="modalGiftcardImage" src="" alt="" class="mx-auto h-12 object-contain">
      </div>
      <div>
        <label for="country" class="text-sm font-medium mb-1 block">Country</label>
        <select name="country" id="country" class="w-full rounded-lg border px-3 py-2 bg-surface-light dark:bg-surface-dark outline-none" required>
          <option value="">Select Country</option>
        </select>
      </div>
      <div>
        <label for="card_type" class="text-sm font-medium mb-1 block">Card Type</label>
        <select name="card_type" id="card_type" class="w-full rounded-lg border px-3 py-2 bg-surface-light dark:bg-surface-dark outline-none" required disabled>
          <option value="">Select Country First</option>
        </select>
      </div>
      <div>
        <label for="amount" class="text-sm font-medium mb-1 block">
          Amount (<span id="inputCurrencySymbol">$</span>)
        </label>
        <input type="number" name="amount" id="amount" class="w-full rounded-lg border px-3 py-2 bg-surface-light dark:bg-surface-dark outline-none" min="0" step="any" required>
      </div>
      <div>
        <label for="card_image" class="text-sm font-medium mb-1 block">Upload Card Image</label>
        <input type="file" name="card_images[]" id="card_image" class="w-full rounded-lg border px-3 py-2 bg-surface-light dark:bg-surface-dark outline-none" accept="image/*" multiple required>
        <div id="previewContainer" class="flex flex-wrap mt-2 gap-2"></div>
      </div>
      <div class="flex justify-between text-sm font-medium">
        <span>Rate: ₦<span id="rateDisplay">0.00</span></span>
        <span>Final Value: ₦<span id="finalValueDisplay">0.00</span></span>
      </div>
      <button type="submit" class="bg-primary text-background-dark rounded-lg py-2 font-bold w-full mt-2">Submit Trade</button>
    </form>
  </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex justify-center items-end">
  <div class="w-full max-w-md bg-surface-light dark:bg-surface-dark rounded-t-3xl p-5 pb-6 shadow-xl transform translate-y-full transition-transform duration-300">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold">Confirm Trade Details</h3>
      <button id="closeConfirmModal" class="text-slate-500 dark:text-slate-400">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div id="confirmSummary" class="text-sm space-y-1 mb-4"></div>
    <div class="flex flex-col gap-2">
      <button id="confirmSubmitBtn" class="bg-primary text-background-dark rounded-lg py-2 font-bold w-full">Confirm & Submit</button>
      <button id="cancelConfirm" class="bg-slate-200 dark:bg-slate-700 rounded-lg py-2 text-sm w-full">Cancel</button>
    </div>
  </div>
</div>

<!-- JS -->
<script>
// Bottom modal helpers
function openModal(modal) {
  modal.classList.remove('hidden');
  setTimeout(() => modal.firstElementChild.classList.remove('translate-y-full'), 10);
}
function closeModal(modal) {
  modal.firstElementChild.classList.add('translate-y-full');
  setTimeout(() => modal.classList.add('hidden'), 300);
}
// Close modal on backdrop click
document.querySelectorAll('#giftcardTradeModal, #confirmModal').forEach(modal => {
  modal.addEventListener('click', e => {
    if(e.target === modal) closeModal(modal);
  });
});

const giftcardItems = document.querySelectorAll('.giftcard-item');
const tradeModal = document.getElementById('giftcardTradeModal');
const confirmModal = document.getElementById('confirmModal');

const modalGiftcardId = document.getElementById('modalGiftcardId');
const modalGiftcardTitle = document.getElementById('modalGiftcardTitle');
const modalGiftcardImage = document.getElementById('modalGiftcardImage');
const countrySelect = document.getElementById('country');
const cardTypeSelect = document.getElementById('card_type');
const rateDisplay = document.getElementById('rateDisplay');
const finalValueDisplay = document.getElementById('finalValueDisplay');
const amountInput = document.getElementById('amount');
const cardImageInput = document.getElementById('card_image');
const previewContainer = document.getElementById('previewContainer');
const giftcardForm = document.getElementById('giftcardForm');
const confirmSummary = document.getElementById('confirmSummary');
const confirmBtn = document.getElementById('confirmSubmitBtn');

// Open trade modal
giftcardItems.forEach(item => {
  item.addEventListener('click', () => {
    const id = item.dataset.id;
    const name = item.dataset.name;
    const image = item.dataset.image || '';

    modalGiftcardId.value = id;
    modalGiftcardTitle.textContent = `Trade: ${name}`;
    modalGiftcardImage.src = image;
    modalGiftcardImage.alt = name;

    countrySelect.innerHTML = '<option value="">Select Country</option>';
    cardTypeSelect.innerHTML = '<option value="">Select Country First</option>';
    cardTypeSelect.disabled = true;

    amountInput.value = '';
    rateDisplay.textContent = '0.00';
    finalValueDisplay.textContent = '0.00';
    previewContainer.innerHTML = '';

    fetchCountries(id);
    openModal(tradeModal);
  });
});

// Close buttons
document.getElementById('closeTradeModal').addEventListener('click', () => closeModal(tradeModal));
document.getElementById('closeConfirmModal').addEventListener('click', () => closeModal(confirmModal));
document.getElementById('cancelConfirm').addEventListener('click', () => closeModal(confirmModal));

// Fetch countries
function fetchCountries(cardId) {
  axios.get(`get_giftcard_countries.php?card_id=${cardId}`)
    .then(res => {
      countrySelect.innerHTML = '<option value="">Select Country</option>';
      (res.data.countries || []).forEach(c => {
        countrySelect.innerHTML += `<option value="${c}">${c}</option>`;
      });
    }).catch(() => countrySelect.innerHTML = '<option value="">Select Country</option>');
}

// Fetch rate
function fetchRate() {
  const cardId = modalGiftcardId.value;
  const country = countrySelect.value;
  const type = cardTypeSelect.value;
  const amount = parseFloat(amountInput.value);

  if(!cardId || !country || !type || isNaN(amount) || amount <= 0){
    rateDisplay.textContent = '0.00';
    finalValueDisplay.textContent = '0.00';
    return;
  }

  axios.post('get_giftcard_rate.php', { giftcard_id: cardId, country, card_type: type })
    .then(res => {
      const rate = parseFloat(res.data.rate)||0;
      rateDisplay.textContent = rate.toLocaleString(undefined,{minimumFractionDigits:2});
      finalValueDisplay.textContent = (rate*amount).toLocaleString(undefined,{minimumFractionDigits:2});
      document.getElementById('inputCurrencySymbol').textContent = res.data.currency_symbol || '$';
    }).catch(() => { rateDisplay.textContent='0.00'; finalValueDisplay.textContent='0.00'; });
}

// Listeners
countrySelect.addEventListener('change', () => {
  cardTypeSelect.disabled = !countrySelect.value;
  if(countrySelect.value) cardTypeSelect.innerHTML = '<option value="">Select Card Type</option><option value="physical">Physical</option><option value="e-code">E-code</option>';
  fetchRate();
});
cardTypeSelect.addEventListener('change', fetchRate);
amountInput.addEventListener('input', fetchRate);
cardImageInput.addEventListener('change', () => {
  previewContainer.innerHTML = '';
  Array.from(cardImageInput.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.className = 'rounded border h-20 object-cover';
      previewContainer.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
});

// Submit trade -> open confirm modal
giftcardForm.addEventListener('submit', e => {
  e.preventDefault();
  confirmSummary.innerHTML = `
    <p><strong>Gift Card:</strong> ${modalGiftcardTitle.textContent.replace('Trade: ','')}</p>
    <p><strong>Country:</strong> ${countrySelect.value}</p>
    <p><strong>Type:</strong> ${cardTypeSelect.value}</p>
    <p><strong>Amount:</strong> ${document.getElementById('inputCurrencySymbol').textContent}${Number(amountInput.value).toLocaleString(undefined,{minimumFractionDigits:2})}</p>
    <p><strong>Rate:</strong> ₦${Number(rateDisplay.textContent.replace(/,/g,'')).toLocaleString(undefined,{minimumFractionDigits:2})}</p>
    <p><strong>Final Value:</strong> ₦${Number(finalValueDisplay.textContent.replace(/,/g,'')).toLocaleString(undefined,{minimumFractionDigits:2})}</p>
  `;
  closeModal(tradeModal);
  setTimeout(() => openModal(confirmModal), 300);
});

// Confirm & submit
confirmBtn.addEventListener('click', () => {
  closeModal(confirmModal);
  const formData = new FormData(giftcardForm);
  axios.post('submit_giftcard_trade.php', formData)
    .then(res => {
      if(res.data.success){
        giftcardForm.reset();
        previewContainer.innerHTML='';
        rateDisplay.textContent='0.00';
        finalValueDisplay.textContent='0.00';
        countrySelect.innerHTML='<option value="">Select Country</option>';
        cardTypeSelect.innerHTML='<option value="">Select Country First</option>';
        cardTypeSelect.disabled=true;
        Swal.fire('Trade Queued!','Your trade is being processed. Please wait ~30 mins.','success');
      } else Swal.fire('Error',res.data.error||'Submission failed','error');
    }).catch(()=>Swal.fire('Network Error','Something went wrong. Try again.','error'));
});

// Search & toggle
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("giftcardSearch");
    const giftcards = document.querySelectorAll(".giftcard-item");
    const toggleBtn = document.getElementById("toggleGiftcards");
    let showAll = false;

    function updateList() {
        let searchValue = searchInput.value.toLowerCase();
        let visible = 0;
        giftcards.forEach(card => {
            let name = card.dataset.name.toLowerCase();
            if (name.includes(searchValue)) {
                visible++;
                card.style.display = (!showAll && visible > 5) ? "none" : "flex";
            } else card.style.display="none";
        });
        toggleBtn.style.display = visible>5?"inline-block":"none";
    }

    searchInput.addEventListener("keyup", function () { showAll=false; toggleBtn.textContent="Show All"; updateList(); });
    toggleBtn.addEventListener("click", function () { showAll=!showAll; toggleBtn.textContent=showAll?"Show Less":"Show All"; updateList(); });
    updateList();
});
</script>
</body>
</html>
