import { useEffect, useState } from 'react'
import { Link, useLocation } from 'react-router-dom'
import { saveQuizAttempt } from '../api/client'

function Results() {
  const { state } = useLocation()
  const score = state?.score ?? 0
  const total = state?.total ?? 0
  const percentage = total > 0 ? Math.round((score / total) * 100) : 0
  const [saveMessage, setSaveMessage] = useState('')

  useEffect(() => {
    let cancelled = false

    const persistAttempt = async () => {
      if (total <= 0) {
        return
      }

      try {
        await saveQuizAttempt({ score, total_questions: total })
        if (!cancelled) {
          setSaveMessage('Result saved to your dashboard.')
        }
      } catch (error) {
        if (!cancelled) {
          // Most common case is user not logged in; keep this as info, not an error banner.
          setSaveMessage('Login to save this result in your dashboard.')
        }
      }
    }

    persistAttempt()

    return () => {
      cancelled = true
    }
  }, [score, total])

  const correct = score;
  const wrong = total - score;

  return (
    <div className="results-page bg-light">
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-lg-8">

            <div className="results-hero">
              <h1 className="results-grade">Quiz Complete</h1>

              <div className="score-box">
                <div className="score-pct">{score} / {total}</div>
                <div className="score-pct-label">Score: {percentage}%</div>
              </div>

              <div className="results-message">
                {percentage >= 80 ? 'Excellent work! You have a great understanding of the material.' :
                  percentage >= 50 ? 'Good effort! Review the topics you missed and try again.' :
                    'Keep practicing! Review the material and give it another shot.'}
              </div>

              {saveMessage && (
                <div className="alert alert-info mt-4 mb-0 py-2 border-0 shadow-sm" role="alert">
                  <i className="bi bi-info-circle me-2"></i> {saveMessage}
                </div>
              )}

              <div className="mt-5">
                <h5 className="fw-bold text-start mb-3" style={{ color: 'var(--text-muted)' }}>YOUR PERFORMANCE</h5>
                <div className="row g-3">
                  <div className="col-sm-6">
                    <div className="results-stat-item correct">
                      <div className="results-stat-value">{correct}</div>
                      <div className="results-stat-label">Correct</div>
                    </div>
                  </div>
                  <div className="col-sm-6">
                    <div className="results-stat-item wrong">
                      <div className="results-stat-value">{wrong}</div>
                      <div className="results-stat-label">Wrong</div>
                    </div>
                  </div>
                </div>
              </div>

              <div className="d-flex justify-content-center gap-3 mt-5 pt-4 border-top flex-wrap">
                <Link to="/dashboard" className="btn-outline-custom order-1 order-md-0">
                  Dashboard
                </Link>
                <Link to="/quiz" className="btn-primary-custom order-0 order-md-1">
                  Try Again
                </Link>
                <Link to="/" className="btn-outline-custom order-2">
                  Home
                </Link>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  )
}

export default Results
