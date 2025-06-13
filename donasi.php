<?php
require_once("auth.php");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Donor Darah - BloodCare</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    rel="stylesheet" />
  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-hover {
      transition: all 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .blood-drop {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.1);
      }
    }

    .blood-type {
      font-weight: bold;
      font-size: 1.2rem;
    }

    .compatible {
      border: 2px solid #10b981;
      background-color: #ecfdf5;
    }

    .incompatible {
      border: 2px solid #ef4444;
      background-color: #fef2f2;
      opacity: 0.6;
    }

    .modal {
      display: none;
    }

    .modal.active {
      display: flex;
    }
  </style>
</head>

<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <i class="fas fa-tint text-red-500 text-2xl blood-drop"></i>
          <span class="ml-2 text-xl font-bold text-gray-800">BloodCare</span>
        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <a href="#" class="hover:text-red-500 font-medium px-3 py-2 rounded-md">Beranda</a>
            <a href="artikel.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Artikel</a>
            <a href="donasi.php" class="text-gray-600 text-red-500 px-3 py-2 rounded-md transition">Donor</a>

            <?php if (isset($_SESSION["username"])): ?>
              <a href="#" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Profile</a>
            <?php else: ?>
              <a href="auth/login.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Login</a>
              <a href="auth/registrasi.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Register</a>
            <?php endif ?>
          </div>
        </div>
        <button class="md:hidden">
          <i class="fas fa-bars text-gray-600"></i>
        </button>
      </div>
    </div>
  </nav>

  <!-- Header Section -->
  <section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Cari Donor Darah</h1>
        <p class="text-xl opacity-90">
          Temukan donor darah yang kompatibel dengan kebutuhan Anda
        </p>
      </div>
    </div>
  </section>

  <!-- Search Section -->
  <section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-gray-50 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
          Pilih Golongan Darah yang Dibutuhkan
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
          <button
            onclick="filterByBloodType('A+')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">A+</div>
            <div class="text-sm text-gray-500">Tipe A Positif</div>
          </button>
          <button
            onclick="filterByBloodType('A-')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">A-</div>
            <div class="text-sm text-gray-500">Tipe A Negatif</div>
          </button>
          <button
            onclick="filterByBloodType('B+')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">B+</div>
            <div class="text-sm text-gray-500">Tipe B Positif</div>
          </button>
          <button
            onclick="filterByBloodType('B-')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">B-</div>
            <div class="text-sm text-gray-500">Tipe B Negatif</div>
          </button>
          <button
            onclick="filterByBloodType('AB+')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">AB+</div>
            <div class="text-sm text-gray-500">Tipe AB Positif</div>
          </button>
          <button
            onclick="filterByBloodType('AB-')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">AB-</div>
            <div class="text-sm text-gray-500">Tipe AB Negatif</div>
          </button>
          <button
            onclick="filterByBloodType('O+')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">O+</div>
            <div class="text-sm text-gray-500">Tipe O Positif</div>
          </button>
          <button
            onclick="filterByBloodType('O-')"
            class="blood-type-btn bg-white hover:bg-red-50 border-2 border-gray-200 hover:border-red-300 p-4 rounded-lg text-center transition">
            <div class="blood-type text-red-600">O-</div>
            <div class="text-sm text-gray-500">Tipe O Negatif</div>
          </button>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
            <select
              id="locationFilter"
              onchange="applyFilters()"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
              <option value="">Pilih Lokasi</option>
              <option value="jakarta">Jakarta</option>
              <option value="bandung">Bandung</option>
              <option value="surabaya">Surabaya</option>
              <option value="medan">Medan</option>
              <option value="semarang">Semarang</option>
            </select>
          </div>
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Urgensi</label>
            <select
              id="urgencyFilter"
              onchange="applyFilters()"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
              <option value="">Semua</option>
              <option value="urgent">Mendesak</option>
              <option value="normal">Normal</option>
            </select>
          </div>
        </div>

        <div
          id="selectedBloodType"
          class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
          <div class="flex items-center justify-between">
            <div>
              <span class="text-blue-800 font-semibold">Golongan darah dipilih:
              </span>
              <span
                id="selectedType"
                class="text-blue-900 font-bold text-lg"></span>
            </div>
            <button
              onclick="clearFilter()"
              class="text-blue-600 hover:text-blue-800">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div
            id="compatibilityInfo"
            class="mt-2 text-sm text-blue-700"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Donor List -->
  <section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">
          Daftar Permintaan Donor
        </h2>
        <div class="text-gray-600">
          <span id="resultCount">Menampilkan semua permintaan</span>
        </div>
      </div>

      <div
        id="donorList"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Donor requests will be populated by JavaScript -->
      </div>

      <div id="noResults" class="text-center py-12 hidden">
        <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">
          Tidak ada donor yang ditemukan
        </h3>
        <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
      </div>
    </div>
  </section>

  <!-- Contact Modal -->
  <div
    id="contactModal"
    class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md mx-4">
      <div class="text-center mb-6">
        <i class="fas fa-phone text-green-500 text-4xl mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800">Hubungi Donor</h3>
      </div>
      <div id="modalContent" class="space-y-4">
        <!-- Modal content will be populated by JavaScript -->
      </div>
      <div class="flex gap-4 mt-6">
        <button
          onclick="closeModal()"
          class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 rounded-lg font-semibold transition">
          Tutup
        </button>
        <button
          id="callButton"
          class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition">
          <i class="fas fa-phone mr-2"></i>Hubungi
        </button>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center mb-4">
            <i class="fas fa-tint text-red-500 text-2xl"></i>
            <span class="ml-2 text-xl font-bold">BloodCare</span>
          </div>
          <p class="text-gray-400">
            Platform donor darah terpercaya untuk menyelamatkan nyawa.
          </p>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Layanan</h3>
          <ul class="space-y-2 text-gray-400">
            <li>
              <a href="#" class="hover:text-white transition">Cari Donor</a>
            </li>
            <li>
              <a href="#" class="hover:text-white transition">Daftar Donor</a>
            </li>
            <li>
              <a href="#" class="hover:text-white transition">Darurat</a>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Informasi</h3>
          <ul class="space-y-2 text-gray-400">
            <li>
              <a href="artikel.html" class="hover:text-white transition">Artikel</a>
            </li>
            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
            <li>
              <a href="#" class="hover:text-white transition">Bantuan</a>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Kontak</h3>
          <ul class="space-y-2 text-gray-400">
            <li><i class="fas fa-phone mr-2"></i> (021) 555-0123</li>
            <li><i class="fas fa-envelope mr-2"></i> info@bloodcare.id</li>
            <li>
              <i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia
            </li>
          </ul>
        </div>
      </div>
      <hr class="border-gray-700 my-8" />
      <div class="text-center text-gray-400">
        <p>&copy; 2025 BloodCare. Semua hak dilindungi.</p>
      </div>
    </div>
  </footer>

  <script>
    // Sample donor data
    const donorRequests = [{
        id: 1,
        name: "Ahmad Wijaya",
        bloodType: "A+",
        location: "jakarta",
        urgency: "urgent",
        hospital: "RS Cipto Mangunkusumo",
        contact: "0812-3456-7890",
        needed: "2 kantong",
        deadline: "24 jam",
        story: "Membutuhkan transfusi darah untuk operasi jantung",
      },
      {
        id: 2,
        name: "Siti Nurhaliza",
        bloodType: "O-",
        location: "bandung",
        urgency: "urgent",
        hospital: "RS Hasan Sadikin",
        contact: "0813-2345-6789",
        needed: "3 kantong",
        deadline: "12 jam",
        story: "Kecelakaan lalu lintas, membutuhkan darah segera",
      },
      {
        id: 3,
        name: "Budi Santoso",
        bloodType: "B+",
        location: "surabaya",
        urgency: "normal",
        hospital: "RS Dr. Soetomo",
        contact: "0814-3456-7890",
        needed: "1 kantong",
        deadline: "3 hari",
        story: "Persiapan operasi hernia",
      },
      {
        id: 4,
        name: "Maya Sari",
        bloodType: "AB+",
        location: "medan",
        urgency: "urgent",
        hospital: "RS Adam Malik",
        contact: "0815-4567-8901",
        needed: "2 kantong",
        deadline: "6 jam",
        story: "Komplikasi persalinan",
      },
      {
        id: 5,
        name: "Dedi Kurniawan",
        bloodType: "O+",
        location: "jakarta",
        urgency: "normal",
        hospital: "RS Fatmawati",
        contact: "0816-5678-9012",
        needed: "1 kantong",
        deadline: "2 hari",
        story: "Operasi usus buntu",
      },
      {
        id: 6,
        name: "Rina Oktavia",
        bloodType: "A-",
        location: "semarang",
        urgency: "urgent",
        hospital: "RS Kariadi",
        contact: "0817-6789-0123",
        needed: "4 kantong",
        deadline: "8 jam",
        story: "Thalasemia mayor, transfusi rutin",
      },
      {
        id: 7,
        name: "Agus Setiawan",
        bloodType: "B-",
        location: "bandung",
        urgency: "normal",
        hospital: "RS Borromeus",
        contact: "0818-7890-1234",
        needed: "1 kantong",
        deadline: "5 hari",
        story: "Operasi kanker lambung",
      },
      {
        id: 8,
        name: "Lisa Permata",
        bloodType: "AB-",
        location: "jakarta",
        urgency: "urgent",
        hospital: "RS Pondok Indah",
        contact: "0819-8901-2345",
        needed: "2 kantong",
        deadline: "4 jam",
        story: "Leukemia akut",
      },
    ];

    // Blood type compatibility
    const bloodCompatibility = {
      "A+": ["A+", "A-", "O+", "O-"],
      "A-": ["A-", "O-"],
      "B+": ["B+", "B-", "O+", "O-"],
      "B-": ["B-", "O-"],
      "AB+": ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"],
      "AB-": ["A-", "B-", "AB-", "O-"],
      "O+": ["O+", "O-"],
      "O-": ["O-"],
    };

    let currentFilter = "";
    let filteredRequests = [...donorRequests];
    let currentContactId = null;

    // Initialize page
    document.addEventListener("DOMContentLoaded", function() {
      displayDonorRequests(donorRequests);
      updateResultCount(donorRequests.length);
    });

    function filterByBloodType(bloodType) {
      currentFilter = bloodType;

      // Update UI to show selected blood type
      document.getElementById("selectedBloodType").classList.remove("hidden");
      document.getElementById("selectedType").textContent = bloodType;

      // Show compatibility info
      const compatibleTypes = bloodCompatibility[bloodType];
      document.getElementById(
        "compatibilityInfo"
      ).innerHTML = `Dapat menerima donor dari: <strong>${compatibleTypes.join(
          ", "
        )}</strong>`;

      // Filter requests
      applyFilters();
    }

    function clearFilter() {
      currentFilter = "";
      document.getElementById("selectedBloodType").classList.add("hidden");

      // Reset button styles
      document.querySelectorAll(".blood-type-btn").forEach((btn) => {
        btn.classList.remove("bg-red-500", "text-white");
        btn.classList.add("bg-white", "hover:bg-red-50");
      });

      // Reset other filters
      document.getElementById("locationFilter").value = "";
      document.getElementById("urgencyFilter").value = "";

      // Show all requests
      displayDonorRequests(donorRequests);
      updateResultCount(donorRequests.length);
    }

    function applyFilters() {
      let filtered = [...donorRequests];

      // Filter by blood type compatibility
      if (currentFilter) {
        const compatibleTypes = bloodCompatibility[currentFilter];
        filtered = filtered.filter((request) =>
          compatibleTypes.includes(request.bloodType)
        );
      }

      // Filter by location
      const locationFilter = document.getElementById("locationFilter").value;
      if (locationFilter) {
        filtered = filtered.filter(
          (request) => request.location === locationFilter
        );
      }

      // Filter by urgency
      const urgencyFilter = document.getElementById("urgencyFilter").value;
      if (urgencyFilter) {
        filtered = filtered.filter(
          (request) => request.urgency === urgencyFilter
        );
      }

      filteredRequests = filtered;
      displayDonorRequests(filtered);
      updateResultCount(filtered.length);
    }

    function displayDonorRequests(requests) {
      const donorList = document.getElementById("donorList");
      const noResults = document.getElementById("noResults");

      if (requests.length === 0) {
        donorList.style.display = "none";
        noResults.classList.remove("hidden");
        return;
      }

      donorList.style.display = "grid";
      noResults.classList.add("hidden");

      donorList.innerHTML = requests
        .map((request) => {
          const isCompatible = !currentFilter ||
            bloodCompatibility[currentFilter].includes(request.bloodType);
          const cardClass = isCompatible ? "compatible" : "incompatible";
          const urgencyColor =
            request.urgency === "urgent" ?
            "bg-red-100 text-red-600" :
            "bg-yellow-100 text-yellow-600";
          const urgencyText =
            request.urgency === "urgent" ? "Mendesak" : "Normal";

          return `
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover ${cardClass}">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">${
                                  request.name
                                }</h3>
                                <p class="text-gray-600">${request.hospital}</p>
                            </div>
                            <div class="text-right">
                                <div class="blood-type text-red-600 text-2xl">${
                                  request.bloodType
                                }</div>
                                <span class="text-sm px-2 py-1 rounded-full ${urgencyColor}">${urgencyText}</span>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                <span class="capitalize">${
                                  request.location
                                }</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tint w-4 mr-2"></i>
                                <span>Dibutuhkan: ${request.needed}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock w-4 mr-2"></i>
                                <span>Deadline: ${request.deadline}</span>
                            </div>
                        </div>
                        
                        <p class="text-gray-700 text-sm mb-4">${
                          request.story
                        }</p>
                        
                        ${
                          isCompatible
                            ? `<button onclick="contactDonor(${request.id})" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition">
                                <i class="fas fa-phone mr-2"></i>Hubungi Sekarang
                            </button>`
                            : `<button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                                <i class="fas fa-times mr-2"></i>Golongan Darah Tidak Kompatibel
                            </button>`
                        }
                    </div>
                `;
        })
        .join("");
    }

    function contactDonor(id) {
      const request = donorRequests.find((r) => r.id === id);
      if (!request) return;

      currentContactId = id;
      const modalContent = document.getElementById("modalContent");
      modalContent.innerHTML = `
                <div class="text-center">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">${request.name}</h4>
                    <p class="text-gray-600 mb-4">${request.hospital}</p>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Golongan Darah:</span>
                        <span class="font-semibold text-red-600">${request.bloodType}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibutuhkan:</span>
                        <span class="font-semibold">${request.needed}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Deadline:</span>
                        <span class="font-semibold">${request.deadline}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kontak:</span>
                        <span class="font-semibold text-blue-600">${request.contact}</span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pastikan Anda memenuhi syarat donor darah sebelum menghubungi.
                    </p>
                </div>
            `;

      // Update call button with phone number
      const callButton = document.getElementById("callButton");
      callButton.onclick = () => makeCall(request.contact);

      document.getElementById("contactModal").classList.add("active");
    }

    function closeModal() {
      document.getElementById("contactModal").classList.remove("active");
      currentContactId = null;
    }

    function makeCall(phoneNumber) {
      // In a real application, this would initiate a phone call
      // For demonstration, we'll show an alert
      alert(`Menghubungi ${phoneNumber}...`);

      // You could also open the phone app on mobile devices:
      // window.location.href = `tel:${phoneNumber}`;

      closeModal();
    }

    function updateResultCount(count) {
      const resultCount = document.getElementById("resultCount");
      if (count === donorRequests.length) {
        resultCount.textContent = "Menampilkan semua permintaan";
      } else {
        resultCount.textContent = `Menampilkan ${count} dari ${donorRequests.length} permintaan`;
      }
    }

    // Close modal when clicking outside
    document
      .getElementById("contactModal")
      .addEventListener("click", function(e) {
        if (e.target === this) {
          closeModal();
        }
      });

    // Close modal with Escape key
    document.addEventListener("keydown", function(e) {
      if (e.key === "Escape") {
        closeModal();
      }
    });
  </script>
</body>

</html>