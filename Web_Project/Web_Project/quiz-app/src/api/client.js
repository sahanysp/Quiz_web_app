const DEFAULT_API_BASE =
  typeof window !== 'undefined'
    ? `${window.location.protocol}//${window.location.hostname}:8000/api`
    : 'http://127.0.0.1:8000/api'

const API_BASE = import.meta.env.VITE_API_BASE || DEFAULT_API_BASE

async function request(path, options = {}) {
  const response = await fetch(`${API_BASE}${path}`, {
    credentials: 'include',
    headers: {
      'Content-Type': 'application/json',
      ...(options.headers || {}),
    },
    ...options,
  })

  let payload = null
  try {
    payload = await response.json()
  } catch {
    payload = null
  }

  if (!response.ok) {
    const message = payload?.error || payload?.errors?.join(' ') || 'Request failed.'
    throw new Error(message)
  }

  return payload
}

export function registerUser(data) {
  return request('/register.php', {
    method: 'POST',
    body: JSON.stringify(data),
  })
}

export function loginUser(data) {
  return request('/login.php', {
    method: 'POST',
    body: JSON.stringify(data),
  })
}

export function logoutUser() {
  return request('/logout.php', {
    method: 'POST',
    body: JSON.stringify({}),
  })
}

export function submitContact(data) {
  return request('/contact.php', {
    method: 'POST',
    body: JSON.stringify(data),
  })
}

export function fetchSession() {
  return request('/session.php', {
    method: 'GET',
  })
}

export function saveQuizAttempt(data) {
  return request('/quiz-attempt.php', {
    method: 'POST',
    body: JSON.stringify(data),
  })
}

export function clearHistory() {
  return request('/clear-history.php', {
    method: 'POST',
    body: JSON.stringify({}),
  })
}
