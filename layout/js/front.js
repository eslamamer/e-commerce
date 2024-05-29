// Select all elements with the class "live" and attach event listeners
document.querySelectorAll('.live').forEach(function(element) {
    element.addEventListener('keyup', function() {
        // Retrieve the target element selector from the data-class attribute
        var targetSelector = this.getAttribute('data-class');
        
        // Select the target element
        var targetElement = document.querySelector(targetSelector);
        
        // Check if the target element exists
        if (targetElement) {
            // Update the text content of the target element with the input value
            targetElement.textContent = this.value;
        } else {
            // Handle the case where the target element does not exist
            console.error('Target element not found:', targetSelector);
        }
    });
});
