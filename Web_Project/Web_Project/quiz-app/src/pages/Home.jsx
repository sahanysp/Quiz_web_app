import { Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'

function Home() {
  const { isAuthenticated } = useAuth()

  return (
    <>
      {/* Hero Section */}
      <section className="hero-section">
        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-8">
              <span className="hero-badge">Tech Quiz App</span>
              <h1 className="hero-title">Welcome to <span className="hero-highlight">QuizMaster</span></h1>
              <p className="hero-subtitle">
                Test your knowledge with fun tech trivia questions and track your progress instantly.
              </p>
              <div className="d-flex justify-content-center gap-3">
                {isAuthenticated ? (
                  <>
                    <Link to="/quiz" className="btn-hero-primary px-4 py-2">
                      Start Quiz
                    </Link>
                    <Link to="/contact" className="btn-hero-outline px-4 py-2">
                      How to Play
                    </Link>
                  </>
                ) : (
                  <>
                    <Link to="/login" className="btn-hero-primary px-4 py-2">
                      Login
                    </Link>
                    <Link to="/register" className="btn-hero-outline px-4 py-2">
                      Sign Up
                    </Link>
                  </>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="features-section">
        <div className="container">
          <div className="row g-4 justify-content-center">
            {/* Feature 1 */}
            <div className="col-md-4">
              <div className="feature-card">
                <div className="feature-icon mb-3">
                  <i className="bi bi-patch-question"></i>
                </div>
                <h5 className="fw-bold mb-2">10 Questions</h5>
                <p className="text-muted mb-0">Answer multiple-choice tech questions with instant feedback.</p>
              </div>
            </div>

            {/* Feature 2 */}
            <div className="col-md-4">
              <div className="feature-card">
                <div className="feature-icon mb-3">
                  <i className="bi bi-speedometer2"></i>
                </div>
                <h5 className="fw-bold mb-2">Instant Score</h5>
                <p className="text-muted mb-0">See your results immediately upon completing the quiz.</p>
              </div>
            </div>

            {/* Feature 3 */}
            <div className="col-md-4">
              <div className="feature-card">
                <div className="feature-icon mb-3">
                  <i className="bi bi-graph-up-arrow"></i>
                </div>
                <h5 className="fw-bold mb-2">Track Progress</h5>
                <p className="text-muted mb-0">Monitor your scores and improve with every attempt.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default Home
