import { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { clearHistory } from '../api/client'

function Dashboard() {
  const navigate = useNavigate()
  const { user, loading, logout, checkSession } = useAuth()
  const [session, setSession] = useState(null)
  const [sessionLoading, setSessionLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    const loadSession = async () => {
      try {
        const response = await checkSession()
        setSession(response)
      } catch (requestError) {
        setError(requestError.message)
      } finally {
        setSessionLoading(false)
      }
    }
    loadSession()
  }, [])

  const handleLogout = async () => {
    try {
      await logout()
      navigate('/')
    } catch (requestError) {
      setError(requestError.message)
    }
  }

  const handleClearHistory = async () => {
    if (!window.confirm('Are you sure you want to clear your quiz history?')) {
      return
    }
    
    try {
      await clearHistory()
      setSession((prev) => ({ ...prev, attempts: [] }))
    } catch (requestError) {
      setError(requestError.message)
    }
  }

  if (sessionLoading || loading) {
    return <section className="container page-section"><p>Loading dashboard...</p></section>
  }

  if (error) {
    return <section className="container page-section"><div className="alert alert-danger">{error}</div></section>
  }

  return (
    <section className="container page-section">
      <div className="row justify-content-center">
        <div className="col-lg-10">
          <div className="card shadow-sm border-0">
            <div className="card-body p-4 p-md-5">
              <div className="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                  <h2 className="mb-1">Welcome, {user?.username || 'User'}</h2>
                  <p className="text-muted mb-0">Your recent quiz attempts are shown below.</p>
                </div>
                <div className="d-flex gap-2">
                  {session?.attempts?.length > 0 && (
                    <button className="btn btn-outline-warning" onClick={handleClearHistory}>
                      <i className="bi bi-trash me-2"></i>Clear History
                    </button>
                  )}
                  <button className="btn btn-outline-danger" onClick={handleLogout}>
                    <i className="bi bi-box-arrow-right me-2"></i>Logout
                  </button>
                </div>
              </div>

              {session?.attempts?.length ? (
                <div className="table-responsive">
                  <table className="table table-striped">
                    <thead>
                      <tr>
                        <th>Score</th>
                        <th>Total Questions</th>
                        <th>Submitted At</th>
                      </tr>
                    </thead>
                    <tbody>
                      {session.attempts.map((attempt, index) => (
                        <tr key={`${attempt.submitted_at}-${index}`}>
                          <td>{attempt.score}</td>
                          <td>{attempt.total_questions}</td>
                          <td>{attempt.submitted_at}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <p className="mb-0">No attempts found yet. Try a quiz to create your first record.</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}

export default Dashboard
