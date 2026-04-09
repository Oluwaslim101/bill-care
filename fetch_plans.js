document.addEventListener("DOMContentLoaded", function () {
    fetchDSTVPlans();
});

function fetchDSTVPlans() {
    const tvPlans = {
        "TV_ID": {
            "DStv": [
                {
                    "ID": "dstv",
                    "PRODUCT": [
                        { "PACKAGE_ID": "dstv-padi", "PACKAGE_NAME": "DStv Padi N4,400", "PACKAGE_AMOUNT": "4400.00" },
                        { "PACKAGE_ID": "dstv-yanga", "PACKAGE_NAME": "DStv Yanga N6,000", "PACKAGE_AMOUNT": "6000.00" },
                        { "PACKAGE_ID": "dstv-confam", "PACKAGE_NAME": "DStv Confam N11,000", "PACKAGE_AMOUNT": "11000.00" },
                        { "PACKAGE_ID": "dstv79", "PACKAGE_NAME": "DStv Compact N19,000", "PACKAGE_AMOUNT": "19000.00" },
                        { "PACKAGE_ID": "dstv3", "PACKAGE_NAME": "DStv Premium N44,500", "PACKAGE_AMOUNT": "44500.00" },
                        { "PACKAGE_ID": "dstv6", "PACKAGE_NAME": "DStv Asia N14,900", "PACKAGE_AMOUNT": "14900.00" }
                        // Add more plans as needed
                    ]
                }
            ]
        }
    };

    const dstvPlans = tvPlans.TV_ID.DStv[0].PRODUCT; 
    const dropdown = document.getElementById("dstv-plans-dropdown");

    if (!dropdown) {
        console.error("Dropdown element not found");
        return;
    }

    dstvPlans.forEach(plan => {
        let option = document.createElement("option");
        option.value = plan.PACKAGE_ID;
        option.textContent = `${plan.PACKAGE_NAME} - N${plan.PACKAGE_AMOUNT}`;
        dropdown.appendChild(option);
    });
}