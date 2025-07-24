function copyToClipboard(cell) {
    let value;
    if (cell.getAttribute('data-value')) {
        value = cell.getAttribute('data-value');
    } else {
        value = cell.textContent.trim();
    }

    navigator.clipboard.writeText(value)
        .then(() => {
            const successMessage = document.createElement('div');
            successMessage.textContent = 'Copied to clipboard';
            successMessage.style.position = 'fixed';
            successMessage.style.top = '15%';
            successMessage.style.left = '50%';
            successMessage.style.backgroundColor = '#4CAF50'; // Green
            successMessage.style.color = '#fff';
            successMessage.style.padding = '8px 16px';
            successMessage.style.transform = 'translate(-50%, -50%)';
            successMessage.style.borderRadius = '4px';
            successMessage.style.fontSize = '14px'; // Smaller font size
            successMessage.style.zIndex = '9999';
            successMessage.style.opacity = 1;
            successMessage.style.transition = 'opacity 0.5s ease-out'; // Smooth hiding animation
            document.body.appendChild(successMessage);

            setTimeout(() => {
                successMessage.style.opacity = 0; // Fade out
                setTimeout(() => {
                    successMessage.remove();
                }, 500); // Remove the element after fading out
            }, 500);
        })
        .catch((err) => {
            console.error('Failed to copy text:', err);
        });
}


const form = document.getElementById('config-form');

form.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent normal form submission

    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'config/config.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById("success-message").style.display = "block";
            setTimeout(() => {
                configModal.hide();
                document.getElementById("success-message").style.display = "none";
            }, 1000); // Wait 2 seconds before hiding the modal
        }
    };

    xhr.send(formData);
});


const configBtn = document.getElementById('config-btn');
const configModal = new bootstrap.Modal(document.getElementById('config-modal'));

// Function to fetch and populate config data
const populateConfigData = () => {
    const url = 'config/config.json';
    const cacheBust = new URLSearchParams({ t: Date.now() }).toString(); // Generate a cache-busting query string
    fetch(`${url}?${cacheBust}`) // Add the cache-busting query string to the URL
        .then(response => response.json())
        .then(data => {
            document.getElementById('telegramBotToken').value = data.telegramBotToken;
            document.getElementById('telegramChatId').value = data.telegramChatId;
        });
}

configBtn.addEventListener('click', () => {
    configModal.show();
    populateConfigData(); // Call the function to populate data every time the modal is shown
});

// Add event listener to close the modal when clicking outside
document.addEventListener('click', (e) => {
    if (e.target === configModal) {
        configModal.hide();
    }
});


