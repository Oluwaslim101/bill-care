<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Airtime Top-up UI</title>
<!-- Google Fonts: Manrope -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Config -->
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec80",
                        "background-light": "#f6f8f7",
                        "background-dark": "#102219",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1c3026",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        /* Custom scrollbar hiding for clean UI */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-gray-50 dark:bg-black font-display antialiased">
<!-- Main Mobile Container -->
<!-- Airtime Purchase Modal (Tailwind, Dark/Light, Template Style) -->
<div id="airtimeModal" class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 backdrop-blur-sm hidden">
  <div class="w-full max-w-md rounded-2xl bg-surface-light dark:bg-surface-dark shadow-2xl p-4 pb-32 relative">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold text-[#111814] dark:text-white flex-1 text-center">Buy Airtime</h3>
      <button onclick="closeAirtimeModal()" class="text-gray-500 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <div class="flex flex-col space-y-6 overflow-y-auto no-scrollbar">

      <!-- Beneficiary Segmented Control -->
      <div class="w-full">
        <div class="flex h-12 w-full items-center justify-center rounded-xl bg-gray-200 dark:bg-surface-dark p-1">
          <label class="relative flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 text-sm font-bold transition-all duration-200">
            <input checked class="peer invisible w-0 absolute" name="beneficiary-type" type="radio" value="My Number"/>
            <span class="z-10 text-gray-500 dark:text-gray-400 peer-checked:text-black dark:peer-checked:text-black relative">My Number</span>
            <span class="absolute inset-0 bg-white dark:bg-primary shadow-sm rounded-lg scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200"></span>
          </label>
          <label class="relative flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 text-sm font-bold transition-all duration-200">
            <input class="peer invisible w-0 absolute" name="beneficiary-type" type="radio" value="New Beneficiary"/>
            <span class="z-10 text-gray-500 dark:text-gray-400 peer-checked:text-black dark:peer-checked:text-black relative">Others</span>
            <span class="absolute inset-0 bg-white dark:bg-primary shadow-sm rounded-lg scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200"></span>
          </label>
        </div>
      </div>

      <!-- Phone Number Input -->
      <div class="space-y-2">
        <h3 class="text-[#111814] dark:text-white text-base font-bold leading-tight">Phone Number</h3>
        <div class="flex w-full items-stretch rounded-xl bg-surface-light dark:bg-surface-dark shadow-sm border border-transparent focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all">
          <input id="airtimePhone" class="flex w-full min-w-0 flex-1 resize-none bg-transparent text-[#111814] dark:text-white h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-4 rounded-l-xl text-lg font-medium outline-none border-none ring-0 focus:ring-0" placeholder="000 000 0000" type="tel"/>
          <button class="text-primary flex items-center justify-center px-4 rounded-r-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
            <span class="material-symbols-outlined">contacts</span>
          </button>
        </div>
      </div>

      <!-- Network Selection Grid -->
      <div class="space-y-3">
        <h3 class="text-[#111814] dark:text-white text-base font-bold leading-tight">Select Network</h3>
        <div class="grid grid-cols-4 gap-3">

          <!-- MTN -->
          <label class="group cursor-pointer">
            <input checked class="peer sr-only" name="airtimeNetwork" type="radio" value="mtn"/>
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-transparent bg-surface-light dark:bg-surface-dark p-3 shadow-sm transition-all peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-md h-24 relative overflow-hidden">
              <div class="h-10 w-10 rounded-full bg-[#ffcc00] flex items-center justify-center text-black font-bold text-xs mb-2 z-10 border border-black/10">MTN</div>
              <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 z-10">MTN</span>
              <div class="absolute top-1 right-1 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-[18px] bg-white rounded-full">check_circle</span>
              </div>
            </div>
          </label>

          <!-- Airtel -->
          <label class="group cursor-pointer">
            <input class="peer sr-only" name="airtimeNetwork" type="radio" value="airtel"/>
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-transparent bg-surface-light dark:bg-surface-dark p-3 shadow-sm transition-all peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-md h-24 relative overflow-hidden">
              <div class="h-10 w-10 rounded-full bg-[#ff0000] flex items-center justify-center text-white font-bold text-xs mb-2 z-10">A</div>
              <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 z-10">Airtel</span>
              <div class="absolute top-1 right-1 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-[18px] bg-white rounded-full">check_circle</span>
              </div>
            </div>
          </label>

          <!-- Glo -->
          <label class="group cursor-pointer">
            <input class="peer sr-only" name="airtimeNetwork" type="radio" value="glo"/>
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-transparent bg-surface-light dark:bg-surface-dark p-3 shadow-sm transition-all peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-md h-24 relative overflow-hidden">
              <div class="h-10 w-10 rounded-full bg-[#00b050] flex items-center justify-center text-white font-bold text-xs mb-2 z-10">Glo</div>
              <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 z-10">Glo</span>
              <div class="absolute top-1 right-1 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-[18px] bg-white rounded-full">check_circle</span>
              </div>
            </div>
          </label>

          <!-- 9mobile -->
          <label class="group cursor-pointer">
            <input class="peer sr-only" name="airtimeNetwork" type="radio" value="9mobile"/>
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-transparent bg-surface-light dark:bg-surface-dark p-3 shadow-sm transition-all peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-md h-24 relative overflow-hidden">
              <div class="h-10 w-10 rounded-full bg-[#006400] flex items-center justify-center text-white font-bold text-xs mb-2 z-10">9m</div>
              <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 z-10">9mobile</span>
              <div class="absolute top-1 right-1 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-[18px] bg-white rounded-full">check_circle</span>
              </div>
            </div>
          </label>

        </div>
      </div>

      <!-- Amount Section -->
      <div class="space-y-3">
        <h3 class="text-[#111814] dark:text-white text-base font-bold leading-tight">Amount</h3>
        <div class="relative flex items-center justify-center rounded-2xl bg-surface-light dark:bg-surface-dark shadow-sm border-2 border-transparent focus-within:border-primary transition-all p-6">
          <span class="text-gray-400 dark:text-gray-500 text-3xl font-bold mr-2">₦</span>
          <input id="airtimeAmount" class="w-full bg-transparent text-center text-4xl font-extrabold text-[#111814] dark:text-white placeholder:text-gray-300 dark:placeholder:text-gray-600 outline-none border-none ring-0 focus:ring-0" placeholder="0.00" type="number" min="50"/>
        </div>

        <!-- Quick Amount Chips -->
        <div class="flex gap-3 overflow-x-auto no-scrollbar py-1">
          <button type="button" class="shrink-0 rounded-full border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm transition-all hover:border-primary hover:text-primary active:bg-primary/10">₦50</button>
          <button type="button" class="shrink-0 rounded-full border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm transition-all hover:border-primary hover:text-primary active:bg-primary/10">₦100</button>
          <button type="button" class="shrink-0 rounded-full border border-primary bg-primary/10 px-5 py-2.5 text-sm font-semibold text-primary dark:text-primary shadow-sm ring-1 ring-primary transition-all">₦200</button>
          <button type="button" class="shrink-0 rounded-full border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm transition-all hover:border-primary hover:text-primary active:bg-primary/10">₦500</button>
        </div>
      </div>

    </div>

    <!-- Sticky Bottom CTA -->
    <div class="absolute bottom-0 left-0 w-full p-4 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md border-t border-gray-200 dark:border-gray-800">
      <button id="airtimeContinueBtn" class="w-full group relative flex items-center justify-center overflow-hidden rounded-xl bg-primary p-4 transition-all hover:bg-[#0fd671] hover:shadow-lg active:scale-[0.98]">
        <div class="relative z-10 flex items-center gap-2 font-bold text-[#102219]">
          <span>Top Up</span>
          <span id="airtimeSummaryAmount">₦0</span>
          <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
        </div>
      </button>
    </div>

  </div>
</div>

<script>
  // Modal open/close
  function openAirtimeModal(){ document.getElementById('airtimeModal').classList.remove('hidden'); }
  function closeAirtimeModal(){ document.getElementById('airtimeModal').classList.add('hidden'); }

  // Update Top Up button amount dynamically
  const amountInput = document.getElementById('airtimeAmount');
  const topUpBtnAmount = document.getElementById('airtimeSummaryAmount');
  amountInput.addEventListener('input', ()=>{ topUpBtnAmount.textContent = '₦'+amountInput.value; });

  // Quick Amount Chips
  document.querySelectorAll('.flex button').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      amountInput.value = btn.textContent.replace('₦','');
      topUpBtnAmount.textContent = btn.textContent;
    });
  });

  // Continue button click
  document.getElementById('airtimeContinueBtn').addEventListener('click', ()=>{
    const network = document.querySelector('input[name="airtimeNetwork"]:checked').value;
    const number = document.getElementById('airtimePhone').value;
    const amount = document.getElementById('airtimeAmount').value;

    if(!network || !number || !amount || amount<50){
      alert('Please fill all fields correctly. Minimum ₦50');
      return;
    }

    console.log({network, number, amount});
    alert(`Confirm Airtime Purchase\nNetwork: ${network}\nNumber: ${number}\nAmount: ₦${amount}`);
    closeAirtimeModal();
    // here you can open your confirmation modal or make API call
  });
</script>

</div>
</div>
</body></html>