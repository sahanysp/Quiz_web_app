function showInlineError(input, message) {
  const errorId = `${input.id}-error`;
  let errorElement = document.getElementById(errorId);

  if (!errorElement) {
    errorElement = document.createElement('small');
    errorElement.id = errorId;
    errorElement.style.color = '#dc3545';
    errorElement.style.display = 'block';
    errorElement.style.marginTop = '0.25rem';
    input.insertAdjacentElement('afterend', errorElement);
  }

  errorElement.textContent = message;
}

function clearInlineError(input) {
  const errorElement = document.getElementById(`${input.id}-error`);
  if (errorElement) {
    errorElement.textContent = '';
  }
}

function attachValidation(formId, rules) {
  const form = document.getElementById(formId);
  if (!form) {
    return;
  }

  form.addEventListener('submit', (event) => {
    let isValid = true;

    rules.forEach((rule) => {
      const input = document.getElementById(rule.id);
      if (!input) {
        return;
      }

      clearInlineError(input);
      const value = input.value.trim();

      if (rule.required && value.length === 0) {
        showInlineError(input, `${rule.label} is required.`);
        isValid = false;
        return;
      }

      if (rule.minLength && value.length < rule.minLength) {
        showInlineError(input, `${rule.label} must be at least ${rule.minLength} characters.`);
        isValid = false;
        return;
      }

      if (rule.type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          showInlineError(input, 'Please enter a valid email address.');
          isValid = false;
        }
      }
    });

    if (!isValid) {
      event.preventDefault();
    }
  });
}
