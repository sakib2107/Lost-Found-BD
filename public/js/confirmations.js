// Global confirmation system
class ConfirmationSystem {
    constructor() {
        this.currentAction = null;
        this.currentForm = null;
    }

    // Show confirmation modal
    showConfirmation(options = {}) {
        const {
            title = 'Confirm Action',
            message = 'Are you sure you want to proceed?',
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            confirmClass = 'bg-red-500 hover:bg-red-700',
            icon = 'warning',
            onConfirm = null,
            modalId = 'confirmationModal'
        } = options;

        // Update modal content
        document.getElementById(modalId + 'Title').textContent = title;
        document.getElementById(modalId + 'Message').textContent = message;
        
        const confirmBtn = document.getElementById(modalId + 'Confirm');
        confirmBtn.textContent = confirmText;
        confirmBtn.className = `px-4 py-2 ${confirmClass} text-white text-base font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2`;
        
        // Set up confirm action
        confirmBtn.onclick = () => {
            if (onConfirm) {
                onConfirm();
            }
            this.closeModal(modalId);
        };

        // Show modal
        document.getElementById(modalId).classList.remove('hidden');
    }

    // Close modal
    closeModal(modalId = 'confirmationModal') {
        document.getElementById(modalId).classList.add('hidden');
        this.currentAction = null;
        this.currentForm = null;
    }

    // Submit helper that respects HTML5 validation while preserving confirmation flow
    submitCurrentFormWithValidation() {
        if (!this.currentForm) return;
        
        // Programmatic submit
        this.currentForm.submit();
    }

    // Confirm delete post
    confirmDeletePost(postId, postTitle, redirectUrl = null) {
        this.showConfirmation({
            title: 'Delete Post',
            message: `Are you sure you want to delete "${postTitle}"? This action cannot be undone.`,
            confirmText: 'Delete',
            confirmClass: 'bg-red-500 hover:bg-red-700',
            icon: 'warning',
            onConfirm: () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/posts/${postId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Confirm bulk delete posts
    confirmBulkDelete(count) {
        this.showConfirmation({
            title: 'Delete Multiple Posts',
            message: `Are you sure you want to delete ${count} selected post(s)? This action cannot be undone.`,
            confirmText: 'Delete All',
            confirmClass: 'bg-red-500 hover:bg-red-700',
            icon: 'warning',
            onConfirm: () => {
                document.getElementById('bulk-delete-form').submit();
            }
        });
    }

    // Confirm status update
    confirmStatusUpdate(postTitle, newStatus, currentStatus = null) {
        const statusText = newStatus === 'resolved' ? 'mark as resolved' : 'mark as active';
        let message = `Are you sure you want to ${statusText} "${postTitle}"?`;
        
        // Special warning when changing from resolved to active
        if (currentStatus === 'resolved' && newStatus === 'active') {
            message = `Are you sure you want to mark "${postTitle}" as active? This will delete all existing claims and found notifications for this post.`;
        }
        
        this.showConfirmation({
            title: 'Update Status',
            message: message,
            confirmText: 'Update',
            confirmClass: 'bg-blue-500 hover:bg-blue-700',
            icon: newStatus === 'active' && currentStatus === 'resolved' ? 'warning' : 'info',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm claim submission
    confirmClaim(postTitle) {
        this.showConfirmation({
            title: 'Submit Claim',
            message: `Are you sure you want to claim "${postTitle}"? The owner will be notified.`,
            confirmText: 'Submit Claim',
            confirmClass: 'bg-green-500 hover:bg-green-700',
            icon: 'info',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm found notification
    confirmFoundNotification(postTitle) {
        this.showConfirmation({
            title: 'Notify Owner',
            message: `Are you sure you want to notify the owner that you found "${postTitle}"?`,
            confirmText: 'Send Notification',
            confirmClass: 'bg-orange-500 hover:bg-orange-700',
            icon: 'info',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm claim response (accept/reject)
    confirmClaimResponse(action, claimerName) {
        const actionText = action === 'accept' ? 'accept' : 'reject';
        const actionColor = action === 'accept' ? 'bg-green-500 hover:bg-green-700' : 'bg-red-500 hover:bg-red-700';
        
        this.showConfirmation({
            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Claim`,
            message: `Are you sure you want to ${actionText} the claim from ${claimerName}?`,
            confirmText: action.charAt(0).toUpperCase() + action.slice(1),
            confirmClass: actionColor,
            icon: action === 'accept' ? 'info' : 'warning',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm comment deletion
    confirmDeleteComment() {
        this.showConfirmation({
            title: 'Delete Comment',
            message: 'Are you sure you want to delete this comment? This action cannot be undone.',
            confirmText: 'Delete',
            confirmClass: 'bg-red-500 hover:bg-red-700',
            icon: 'warning',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm logout
    confirmLogout() {
        this.showConfirmation({
            title: 'Logout',
            message: 'Are you sure you want to logout?',
            confirmText: 'Logout',
            confirmClass: 'bg-gray-500 hover:bg-gray-700',
            icon: 'info',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm clear conversation
    confirmClearConversation() {
        this.showConfirmation({
            title: 'Clear Conversation',
            message: 'Are you sure you want to clear this conversation? This will remove it from your inbox but the other user will still see it unless they also clear it.',
            confirmText: 'Clear Conversation',
            confirmClass: 'bg-red-500 hover:bg-red-700',
            icon: 'warning',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }

    // Confirm profile image removal
    confirmRemoveProfileImage() {
        this.showConfirmation({
            title: 'Remove Profile Picture',
            message: 'Are you sure you want to remove your profile picture?',
            confirmText: 'Remove',
            confirmClass: 'bg-red-500 hover:bg-red-700',
            icon: 'warning',
            onConfirm: () => {
                this.submitCurrentFormWithValidation();
            }
        });
    }
}

// Initialize global confirmation system
const confirmationSystem = new ConfirmationSystem();

// Helper function to validate form fields
function validateForm(form) {
    let isValid = true;
    let firstInvalidField = null;
    
    // Get all required fields
    const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    // Check each required field
    requiredFields.forEach(field => {
        const value = field.value ? field.value.trim() : '';
        
        if (!value) {
            isValid = false;
            // Add visual feedback
            field.classList.add('border-red-500');
            
            // Remember first invalid field to focus
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            
            // Remove red border when user starts typing
            field.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            }, { once: true });
        } else {
            // Remove red border if field is now valid
            field.classList.remove('border-red-500');
        }
    });
    
    // If invalid, focus first invalid field and show browser validation
    if (!isValid) {
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        
        // Try to show browser validation messages
        if (typeof form.reportValidity === 'function') {
            form.reportValidity();
        }
        
        return false;
    }
    
    // Check HTML5 validation as final step
    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
        if (typeof form.reportValidity === 'function') {
            form.reportValidity();
        }
        return false;
    }
    
    return true;
}

// Global helper functions
function closeModal(modalId) {
    confirmationSystem.closeModal(modalId);
}

function confirmDeletePost(postId, postTitle) {
    confirmationSystem.confirmDeletePost(postId, postTitle);
}

function confirmBulkDelete(count) {
    confirmationSystem.confirmBulkDelete(count);
}

function confirmStatusUpdate(form, postTitle, newStatus, currentStatus = null) {
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmStatusUpdate(postTitle, newStatus, currentStatus);
}

function confirmClaim(form, postTitle) {
    // Validate form before showing confirmation
    if (!validateForm(form)) {
        return false;
    }
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmClaim(postTitle);
}

function confirmFoundNotification(form, postTitle) {
    // Validate form before showing confirmation
    if (!validateForm(form)) {
        return false;
    }
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmFoundNotification(postTitle);
}

function confirmClaimResponse(form, action, claimerName) {
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmClaimResponse(action, claimerName);
}

function confirmDeleteComment(form) {
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmDeleteComment();
}

function confirmLogout(form) {
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmLogout();
}

function confirmClearConversation(form) {
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmClearConversation();
}

function confirmRemoveProfileImage(form) {
    confirmationSystem.currentForm = form;
    confirmationSystem.confirmRemoveProfileImage();
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        if (event.target === modal) {
            confirmationSystem.closeModal(modal.id);
        }
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const visibleModals = document.querySelectorAll('[id$="Modal"]:not(.hidden)');
        visibleModals.forEach(modal => {
            confirmationSystem.closeModal(modal.id);
        });
    }
});