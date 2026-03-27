import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { registerUser } from '../api/client'

function Register() {
  const navigate = useNavigate()
  const [form, setForm] = useState({ username: '', email: '', password: '' })
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  const onChange = (event) => {
    const { name, value } = event.target
    setForm((prev) => ({ ...prev, [name]: value }))
  }

  const onSubmit = async (event) => {
    event.preventDefault()
    setError('')

    if (form.username.trim().length < 3) {
      setError('Username must be at least 3 characters.')
      return
    }

    if (form.password.length < 6) {
      setError('Password must be at least 6 characters.')
      return
    }

    try {
      setLoading(true)
      await registerUser(form)
      navigate('/')
    } catch (requestError) {
      setError(requestError.message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <section className="container page-section">
      <div className="row justify-content-center">
        <div className="col-md-7 col-lg-6">
          <div className="auth-card">
            <div>
              <h2 className="mb-3 fw-bold">Create Account</h2>
              <p className="text-muted">Register to track quiz performance.</p>

              {error ? <div className="alert alert-danger py-2">{error}</div> : null}

              <form onSubmit={onSubmit} noValidate>
                <div className="mb-3">
                  <label htmlFor="username" className="form-label">Username</label>
                  <input
                    id="username"
                    name="username"
                    type="text"
                    className="form-control"
                    value={form.username}
                    onChange={onChange}
                    required
                    minLength={3}
                  />
                </div>

                <div className="mb-3">
                  <label htmlFor="email" className="form-label">Email</label>
                  <input
                    id="email"
                    name="email"
                    type="email"
                    className="form-control"
                    value={form.email}
                    onChange={onChange}
                    required
                  />
                </div>

                <div className="mb-4">
                  <label htmlFor="password" className="form-label">Password</label>
                  <input
                    id="password"
                    name="password"
                    type="password"
                    className="form-control"
                    value={form.password}
                    onChange={onChange}
                    required
                    minLength={6}
                  />
                </div>

                <button type="submit" className="btn btn-primary w-100" disabled={loading}>
                  {loading ? 'Creating account...' : 'Register'}
                </button>
              </form>

              <p className="mt-3 mb-0 text-muted">
                Already registered? <Link to="/login">Login here</Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}

export default Register
