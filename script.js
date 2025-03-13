document.addEventListener("DOMContentLoaded", function () {
    // Populate countries
    const countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];

    const languages = ["Afrikaans", "Albanian", "Amharic", "Arabic", "Armenian", "Azerbaijani", "Basque", "Bengali", "Bosnian", "Bulgarian", "Burmese", "Catalan", "Cebuano", "Chichewa", "Chinese", "Corsican", "Croatian", "Czech", "Danish", "Dutch", "English", "Esperanto", "Estonian", "Filipino", "Finnish", "French", "Galician", "Georgian", "German", "Greek", "Gujarati", "Haitian Creole", "Hausa", "Hawaiian", "Hebrew", "Hindi", "Hmong", "Hungarian", "Icelandic", "Igbo", "Indonesian", "Irish", "Italian", "Japanese", "Javanese", "Kannada", "Kazakh", "Khmer", "Kinyarwanda", "Korean", "Kurdish", "Lao", "Latin", "Latvian", "Lithuanian", "Luxembourgish", "Macedonian", "Malagasy", "Malay", "Malayalam", "Maltese", "Maori", "Marathi", "Mongolian", "Nepali", "Norwegian", "Pashto", "Persian", "Polish", "Portuguese", "Punjabi", "Romanian", "Russian", "Samoan", "Serbian", "Sesotho", "Shona", "Sindhi", "Sinhala", "Slovak", "Slovenian", "Somali", "Spanish", "Sundanese", "Swahili", "Swedish", "Tajik", "Tamil", "Telugu", "Thai", "Turkish", "Ukrainian", "Urdu", "Uzbek", "Vietnamese", "Welsh", "Xhosa", "Yiddish", "Yoruba", "Zulu"];

    let countrySelect = document.getElementById("staff-country");
    let language_spokenSelect = document.getElementById("staff-language");

    countries.forEach(country => {
        let option = document.createElement("option");
        option.value = country;
        option.textContent = country;
        countrySelect.appendChild(option);
    });

    languages.forEach(language => {
        let option = document.createElement("option");
        option.value = language;
        option.textContent = language;
        language_spokenSelect.appendChild(option);
    });
    document.getElementById("view-staff-btn").addEventListener("click", function () {
        fetch("http://localhost/staff_management_system/view_staff.php")
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById("staff-list");
                tableBody.innerHTML = ""; // Clear previous data

                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='5'>No staff records found</td></tr>";
                    return;
                }

                data.forEach(staff => {
                    let row = `<tr>
                        <td>${staff.id}</td>
                        <td>${staff.name}</td>
                        <td>${staff.id_number}</td>
                        <td>${staff.country}</td>
                        <td>${staff.language_spoken}</td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error("Error fetching staff data:", error));
    });
    

    // Handle staff addition
    document.getElementById("staff-form").addEventListener("submit", function (event) {
        event.preventDefault();

        let name = document.getElementById("staff-name").value.trim();
        let id_number = document.getElementById("staff-id").value.trim();
        let country = countrySelect.value;
        let language_spoken = language_spokenSelect.value;

        if (!name || !id_number || country === "Select country" || language_spoken === "Select language") {
            alert("Please fill in all fields correctly.");
            return;
        }

        fetch("http://localhost/staff_management_system/add_staff.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `name=${encodeURIComponent(name)}&id_number=${encodeURIComponent(id_number)}&country=${encodeURIComponent(country)}&language_spoken=${encodeURIComponent(language_spoken)}`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                location.reload();
            }
        })
        .catch(error => console.error("Error:", error));
    });

    // Login handler
    document.getElementById("login-btn").addEventListener("click", function () {
        let username = document.getElementById("username").value.trim();
        let password = document.getElementById("password").value.trim();

        if (!username || !password) {
            document.getElementById("error-msg").innerText = "Please fill in all fields.";
            return;
        }

        fetch("http://localhost/staff_management_system/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                window.location.href = "index.html";
            } else {
                document.getElementById("error-msg").innerText = data.message;
            }
        })
        .catch(error => {
            document.getElementById("error-msg").innerText = "Server error. Try again later.";
        });
    });
});
// Set timeout duration (1 minute)
const IDLE_TIMEOUT = 60000; // 1 minute (60,000 milliseconds)
let idleTimer;

// Function to log out the user
function logoutUser() {
    alert("You have been logged out due to inactivity.");
    window.location.href = "login.html"; // Change this to your actual login page URL
}

// Reset idle timer on user activity
function resetIdleTimer() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(logoutUser, IDLE_TIMEOUT);
}

// Detect user activity
document.addEventListener("mousemove", resetIdleTimer);
document.addEventListener("keypress", resetIdleTimer);
document.addEventListener("touchstart", resetIdleTimer);
document.addEventListener("scroll", resetIdleTimer);

// Start the timer initially
resetIdleTimer();

