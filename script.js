document.addEventListener("DOMContentLoaded", function () {
    // List of all countries
    const countries = [
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Argentina", "Armenia", "Australia", "Austria",
        "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan",
        "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
        "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros",
        "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica",
        "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini",
        "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada",
        "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia",
        "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati",
        "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania",
        "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
        "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
        "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria",
        "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea",
        "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda",
        "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino",
        "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore",
        "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan",
        "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan",
        "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey",
        "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States",
        "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    ];

    // List of all languages
    const languages = [
        "Afrikaans", "Albanian", "Amharic", "Arabic", "Armenian", "Azerbaijani", "Basque", "Bengali", "Bosnian",
        "Bulgarian", "Burmese", "Catalan", "Cebuano", "Chichewa", "Chinese", "Corsican", "Croatian", "Czech",
        "Danish", "Dutch", "English", "Esperanto", "Estonian", "Filipino", "Finnish", "French", "Galician",
        "Georgian", "German", "Greek", "Gujarati", "Haitian Creole", "Hausa", "Hawaiian", "Hebrew", "Hindi",
        "Hmong", "Hungarian", "Icelandic", "Igbo", "Indonesian", "Irish", "Italian", "Japanese", "Javanese",
        "Kannada", "Kazakh", "Khmer", "Kinyarwanda", "Korean", "Kurdish", "Lao", "Latin", "Latvian", "Lithuanian",
        "Luxembourgish", "Macedonian", "Malagasy", "Malay", "Malayalam", "Maltese", "Maori", "Marathi",
        "Mongolian", "Nepali", "Norwegian", "Pashto", "Persian", "Polish", "Portuguese", "Punjabi", "Romanian",
        "Russian", "Samoan", "Serbian", "Sesotho", "Shona", "Sindhi", "Sinhala", "Slovak", "Slovenian",
        "Somali", "Spanish", "Sundanese", "Swahili", "Swedish", "Tajik", "Tamil", "Telugu", "Thai", "Turkish",
        "Ukrainian", "Urdu", "Uzbek", "Vietnamese", "Welsh", "Xhosa", "Yiddish", "Yoruba", "Zulu"
    ];

    let countrySelect = document.getElementById("staff-country");
    let languageSelect = document.getElementById("staff-language");

    // Populate country dropdown
    countries.forEach(country => {
        let option = document.createElement("option");
        option.value = country;
        option.textContent = country;
        countrySelect.appendChild(option);
    });

    // Populate language dropdown
    languages.forEach(language => {
        let option = document.createElement("option");
        option.value = language;
        option.textContent = language;
        languageSelect.appendChild(option);
    });

    // Handle form submission
    document.getElementById("staff-form").addEventListener("submit", function (event) {
        event.preventDefault();

        let name = document.getElementById("staff-name").value.trim();
        let idNumber = document.getElementById("staff-id").value.trim();
        let country = countrySelect.value;
        let language = languageSelect.value;

        if (!name || !idNumber || country === "Select country" || language === "Select language") {
            alert("Please fill in all fields correctly.");
            return;
        }

        let table = document.getElementById("staff-list");
        let newRow = table.insertRow();
        newRow.innerHTML = `<td>${name}</td><td>${idNumber}</td><td>${country}</td><td>${language}</td>`;

        document.getElementById("staff-form").reset();
    });
});
document.getElementById("login-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent form from refreshing the page

    let formData = new FormData(this);

    fetch("http://localhost/staff_management_system/login.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Login successful!");
            window.location.href = "dashboard.html"; // Redirect to dashboard
        } else {
            document.getElementById("error-message").innerText = data.message;
        }
    })
    .catch(error => console.error("Error:", error));
});
document.getElementById("addStaffForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append("name", document.getElementById("name").value);
    formData.append("id_number", document.getElementById("id_number").value);
    formData.append("country", document.getElementById("country").value);
    formData.append("language", document.getElementById("language").value);

    fetch("http://localhost/staff_management_system/add_staff.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("message").textContent = data.message;
        if (data.status === "success") {
            loadStaff(); // Refresh the staff list
        }
    })
    .catch(error => console.error("Error:", error));
});

document.getElementById("add-staff-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent page reload

    // Get form data
    let name = document.getElementById("name").value;
    let idNumber = document.getElementById("id_number").value;
    let country = document.getElementById("country").value;
    let languageSpoken = document.getElementById("language_spoken").value;

    // Prepare data to send
    let formData = new FormData();
    formData.append("name", name);
    formData.append("id_number", idNumber);
    formData.append("country", country);
    formData.append("language_spoken", languageSpoken);

    // Send request to the backend
    fetch("http://localhost/staff_management_system/add_staff.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Staff added successfully!");
            location.reload(); // Reload page to update staff list
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
});
